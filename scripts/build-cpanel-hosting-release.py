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
    try:text=path.read_text(encoding='utf-8')
    except UnicodeDecodeError:return []
    pattern=r'\b(?:final\s+|abstract\s+)?class\s+([A-Za-z_][A-Za-z0-9_]*)|\binterface\s+([A-Za-z_][A-Za-z0-9_]*)|\btrait\s+([A-Za-z_][A-Za-z0-9_]*)'
    return [next(g for g in m.groups() if g) for m in re.finditer(pattern,text)]

def phase_number(path):
    try:text=path.read_text(encoding='utf-8')
    except UnicodeDecodeError:return -1
    nums=[int(x) for x in re.findall(r'Phase\s*(\d{3})',text,re.I)]
    return max(nums) if nums else -1

def remove_duplicate_symbols():
    symbols={}
    for p in BUILD.rglob('*.php'):
        for symbol in declared_symbols(p):symbols.setdefault(symbol,[]).append(p)
    removed=[]
    for symbol,paths in symbols.items():
        if len(paths)<=1:continue
        keep=max(paths,key=lambda p:(phase_number(p),str(p)))
        for p in paths:
            if p!=keep:
                p.unlink();removed.append((symbol,str(p.relative_to(BUILD)),str(keep.relative_to(BUILD))))
    remaining={}
    for p in BUILD.rglob('*.php'):
        for symbol in declared_symbols(p):remaining.setdefault(symbol,[]).append(p)
    dup={k:v for k,v in remaining.items() if len(v)>1}
    if dup:raise SystemExit('Duplicate PHP class/interface/trait remains: '+', '.join(sorted(dup)))
    return removed

def verify_theme():
    required=['style.css','functions.php','header.php','footer.php','front-page.php','inc/PhaseLoader.php','inc/ThemeSetup.php','assets/css/avanik-theme.css','assets/js/avanik-demo.js','assets/images/avanik-logo.svg','assets/images/avanik-hero.svg']
    for rel in required:
        if not (BUILD/rel).is_file():raise SystemExit(f'Missing required theme asset: {rel}')
    style=(BUILD/'style.css').read_text(encoding='utf-8')
    if not re.search(r'^Version:\s*0\.4\.0\s*$',style,re.M):raise SystemExit('Theme version metadata is not 0.4.0')
    text=(BUILD/'functions.php').read_text(encoding='utf-8')
    # Source functions.php is authoritative: it must retain the demo installer and its ThemeSetup bootstrap.
    if 'ThemeSetup.php' not in text or 'ThemeSetup::register' not in text:raise SystemExit('Demo ThemeSetup bootstrap is missing from functions.php')
    if 'Theme.php' not in text or 'Theme::boot' not in text:raise SystemExit('Theme bootstrap is missing from functions.php')
    for rel in re.findall(r"require_once\s+__DIR__\s*\.\s*['\"]([^'\"]+)['\"]",text):
        if not (BUILD/rel.lstrip('/')).exists():raise SystemExit('Missing require_once target: '+rel)
    declared=set()
    for p in BUILD.rglob('*.php'):declared.update(declared_symbols(p))
    refs=set(re.findall(r'\\Avanik\\([A-Za-z_][A-Za-z0-9_]*)::',text))
    missing=sorted(refs-declared)
    if missing:raise SystemExit('Missing registered class(es): '+', '.join(missing))

def main():
    if not REF:raise SystemExit('GITHUB_SHA is required')
    if BUILD.exists():shutil.rmtree(BUILD)
    BUILD.mkdir(parents=True);OUT.parent.mkdir(parents=True,exist_ok=True)
    if OUT.exists():OUT.unlink()
    commit=api_get(f'/repos/{REPO}/commits/{REF}')
    tree=api_get(f"/repos/{REPO}/git/trees/{commit['commit']['tree']['sha']}?recursive=1")
    if tree.get('truncated'):raise SystemExit('Git tree response was truncated')
    selected=[e for e in tree['tree'] if e.get('type')=='blob' and e.get('path','').startswith('wordpress/avanik/')]
    if not selected:raise SystemExit('No WordPress theme files found')
    mapping={};prepared=[]
    for e in selected:
        rel=e['path'][len('wordpress/avanik/'):]
        name=Path(rel).name
        if len(name)>MAX_COMPONENT:
            new=f"avanik_{hashlib.sha1(rel.encode()).hexdigest()[:12]}{Path(name).suffix.lower()}"
            mapping[name]=new;rel=str(Path(rel).with_name(new))
        prepared.append((e,rel))
    with ThreadPoolExecutor(max_workers=16) as pool:
        futures=[pool.submit(download,e) for e,_ in prepared]
        for future in as_completed(futures):
            e,data=future.result();rel=next(r for x,r in prepared if x is e);target=BUILD/rel;target.parent.mkdir(parents=True,exist_ok=True);target.write_bytes(data)
    for rel in ['INSTALL-HOSTING-v0.4.0.md','RELEASE-MANIFEST-v0.4.0.json']:
        p=BUILD/rel
        if p.exists():p.unlink()
    # Replace renamed filenames in every text source, including PHP require_once references.
    for p in BUILD.rglob('*'):
        if not p.is_file():continue
        try:text=p.read_text(encoding='utf-8')
        except UnicodeDecodeError:continue
        for old,new in mapping.items():text=text.replace(old,new)
        p.write_text(text,encoding='utf-8')
    removed=remove_duplicate_symbols();verify_theme()
    with zipfile.ZipFile(OUT,'w',zipfile.ZIP_DEFLATED) as z:
        for p in BUILD.rglob('*'):
            if p.is_file():z.write(p,(Path('avanik')/p.relative_to(BUILD)).as_posix())
    print(f'Created: {OUT}')
    print(f'Source files selected: {len(selected)}')
    print(f'Long filename components shortened: {len(mapping)}')
    print(f'Duplicate symbols removed: {len(removed)}')

if __name__=='__main__':main()
