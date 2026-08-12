from __future__ import annotations
import base64, hashlib, json, os, shutil, urllib.request, zipfile
from pathlib import Path
REPO=os.environ.get('GITHUB_REPOSITORY','hosseinghiedar-cmyk/Avanik-Travel'); REF=os.environ.get('GITHUB_SHA',''); API='https://api.github.com'
ROOT=Path(__file__).resolve().parents[1]; BUILD=ROOT/'.hosting-build'/'avanik'; OUT=ROOT/'dist'/'Avanik-Travel-v0.4.0-Hosting-Release.zip'; MAX_COMPONENT=120

def api_get(path):
    req=urllib.request.Request(API+path,headers={'Accept':'application/vnd.github+json','X-GitHub-Api-Version':'2022-11-28','Authorization':f"Bearer {os.environ.get('GITHUB_TOKEN','')}",'User-Agent':'avanik-cpanel-release-builder'})
    with urllib.request.urlopen(req,timeout=60) as r:return json.loads(r.read().decode())

def main():
    if not REF: raise SystemExit('GITHUB_SHA is required')
    if BUILD.exists(): shutil.rmtree(BUILD)
    BUILD.mkdir(parents=True); OUT.parent.mkdir(parents=True,exist_ok=True)
    if OUT.exists(): OUT.unlink()
    commit=api_get(f'/repos/{REPO}/commits/{REF}'); tree=api_get(f"/repos/{REPO}/git/trees/{commit['commit']['tree']['sha']}?recursive=1")
    if tree.get('truncated'): raise SystemExit('Git tree response was truncated')
    selected=[e for e in tree['tree'] if e.get('type')=='blob' and e.get('path','').startswith('wordpress/avanik/')]
    mapping={}
    for e in selected:
        rel=e['path'][len('wordpress/avanik/'):]; original=Path(rel).name
        if len(original)>MAX_COMPONENT:
            new=f"avanik_{hashlib.sha1(rel.encode()).hexdigest()[:12]}{Path(original).suffix.lower()}"; mapping[original]=new; rel=str(Path(rel).with_name(new))
        target=BUILD/rel; target.parent.mkdir(parents=True,exist_ok=True)
        blob=api_get(f"/repos/{REPO}/git/blobs/{e['sha']}"); target.write_bytes(base64.b64decode(blob['content'].replace('\n','')))
    for rel in ['INSTALL-HOSTING-v0.4.0.md','RELEASE-MANIFEST-v0.4.0.json']:
        p=BUILD/rel
        if p.exists(): p.unlink()
    for p in BUILD.rglob('*'):
        if not p.is_file(): continue
        try:text=p.read_text(encoding='utf-8')
        except UnicodeDecodeError:continue
        for old,new in mapping.items():text=text.replace(old,new)
        p.write_text(text,encoding='utf-8')
    with zipfile.ZipFile(OUT,'w',zipfile.ZIP_DEFLATED) as z:
        for p in BUILD.rglob('*'):
            if p.is_file():z.write(p,(Path('avanik')/p.relative_to(BUILD)).as_posix())
    print(f'Created: {OUT}'); print(f'Source files packaged: {len(selected)}'); print(f'Long filename components shortened: {len(mapping)}')
if __name__=='__main__':main()
