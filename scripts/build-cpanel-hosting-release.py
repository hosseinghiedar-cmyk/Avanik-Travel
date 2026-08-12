from __future__ import annotations
import base64, hashlib, json, os, re, shutil, urllib.request, zipfile
from concurrent.futures import ThreadPoolExecutor, as_completed
from pathlib import Path

REPO=os.environ.get('GITHUB_REPOSITORY','hosseinghiedar-cmyk/Avanik-Travel')
REF=os.environ.get('GITHUB_SHA','')
API='https://api.github.com'
ROOT=Path(__file__).resolve().parents[1]
BUILD=ROOT/'.hosting-build'/'avanik'
OUT=ROOT/'dist'/'Avanik-Travel-v0.4.0-Hosting-Release.zip'
MAX_COMPONENT=120


def api_get(path):
    req=urllib.request.Request(API+path,headers={'Accept':'application/vnd.github+json','X-GitHub-Api-Version':'2022-11-28','Authorization':f"Bearer {os.environ.get('GITHUB_TOKEN','')}",'User-Agent':'avanik-cpanel-release-builder'})
    with urllib.request.urlopen(req,timeout=60) as r:
        return json.loads(r.read().decode())


def download(entry):
    blob=api_get(f"/repos/{REPO}/git/blobs/{entry['sha']}")
    return entry,base64.b64decode(blob['content'].replace('\n',''))


def declared_symbols(path):
    try:
        text=path.read_text(encoding='utf-8')
    except UnicodeDecodeError:
        return []
    pattern=r'\b(?:final\s+|abstract\s+)?class\s+([A-Za-z_][A-Za-z0-9_]*)|\binterface\s+([A-Za-z_][A-Za-z0-9_]*)|\btrait\s+([A-Za-z_][A-Za-z0-9_]*)'
    return [next(g for g in m.groups() if g) for m in re.finditer(pattern,text)]


def phase_number(path):
    try:
        text=path.read_text(encoding='utf-8')
    except UnicodeDecodeError:
        return -1
    nums=[int(x) for x in re.findall(r'Phase\s*(\d{3})',text,re.I)]
    return max(nums) if nums else -1


def remove_duplicate_symbols():
    symbols={}
    for p in BUILD.rglob('*.php'):
        for symbol in declared_symbols(p):
            symbols.setdefault(symbol,[]).append(p)
    removed=[]
    for symbol,paths in symbols.items():
        if len(paths)<=1:
            continue
        # Prefer the implementation associated with the latest project phase.
        keep=max(paths,key=lambda p:(phase_number(p),str(p)))
        for p in paths:
            if p!=keep:
                p.unlink()
                removed.append((symbol,str(p.relative_to(BUILD)),str(keep.relative_to(BUILD))))
    # Fail closed: duplicate declarations must never reach the hosting ZIP.
    remaining={}
    for p in BUILD.rglob('*.php'):
        for symbol in declared_symbols(p):
            remaining.setdefault(symbol,[]).append(p)
    dup={k:v for k,v in remaining.items() if len(v)>1}
    if dup:
        raise SystemExit('Duplicate PHP class/interface/trait remains: '+', '.join(sorted(dup)))
    return removed


def verify_dependencies():
    php_files=list(BUILD.rglob('*.php'))
    declared=set()
    for p in php_files:
        declared.update(declared_symbols(p))
    functions=BUILD/'functions.php'
    if not functions.exists():
        raise SystemExit('functions.php missing')
    text=functions.read_text(encoding='utf-8')
    missing=[]
    for raw in re.findall(r"require_once\s+__DIR__\s*\.\s*['\"]([^'\"]+)['\"]",text):
        rel=raw.lstrip('/')
        if not (BUILD/rel).exists():
            missing.append(rel)
    if missing:
        raise SystemExit('Missing require_once target(s): '+', '.join(missing))
    refs=set(re.findall(r'\\Avanik\\([A-Za-z_][A-Za-z0-9_]*)::',text))
    missing_classes=sorted(refs-declared)
    if missing_classes:
        raise SystemExit('Missing registered class(es): '+', '.join(missing_classes))


def main():
    if not REF:
        raise SystemExit('GITHUB_SHA is required')
    if BUILD.exists():
        shutil.rmtree(BUILD)
    BUILD.mkdir(parents=True)
    OUT.parent.mkdir(parents=True,exist_ok=True)
    if OUT.exists():
        OUT.unlink()
    commit=api_get(f'/repos/{REPO}/commits/{REF}')
    tree=api_get(f"/repos/{REPO}/git/trees/{commit['commit']['tree']['sha']}?recursive=1")
    if tree.get('truncated'):
        raise SystemExit('Git tree response was truncated')
    selected=[e for e in tree['tree'] if e.get('type')=='blob' and e.get('path','').startswith('wordpress/avanik/')]
    mapping={}
    prepared=[]
    for e in selected:
        rel=e['path'][len('wordpress/avanik/'):]
        original=Path(rel).name
        if len(original)>MAX_COMPONENT:
            new=f"avanik_{hashlib.sha1(rel.encode()).hexdigest()[:12]}{Path(original).suffix.lower()}"
            mapping[original]=new
            rel=str(Path(rel).with_name(new))
        prepared.append((e,rel))
    with ThreadPoolExecutor(max_workers=16) as pool:
        futures=[pool.submit(download,e) for e,_ in prepared]
        for future in as_completed(futures):
            e,data=future.result()
            rel=next(r for x,r in prepared if x is e)
            target=BUILD/rel
            target.parent.mkdir(parents=True,exist_ok=True)
            target.write_bytes(data)
    for rel in ['INSTALL-HOSTING-v0.4.0.md','RELEASE-MANIFEST-v0.4.0.json']:
        p=BUILD/rel
        if p.exists():
            p.unlink()
    for p in BUILD.rglob('*'):
        if not p.is_file():
            continue
        try:
            text=p.read_text(encoding='utf-8')
        except UnicodeDecodeError:
            continue
        for old,new in mapping.items():
            text=text.replace(old,new)
        p.write_text(text,encoding='utf-8')
    removed=remove_duplicate_symbols()
    verify_dependencies()
    with zipfile.ZipFile(OUT,'w',zipfile.ZIP_DEFLATED) as z:
        for p in BUILD.rglob('*'):
            if p.is_file():
                z.write(p,(Path('avanik')/p.relative_to(BUILD)).as_posix())
    print(f'Created: {OUT}')
    print(f'Source files selected: {len(selected)}')
    print(f'Long filename components shortened: {len(mapping)}')
    print(f'Duplicate symbols removed: {len(removed)}')

if __name__=='__main__':
    main()
