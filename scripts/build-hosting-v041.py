from __future__ import annotations
import hashlib, os, re, shutil, tarfile, urllib.request, zipfile
from pathlib import Path

REPO=os.environ.get('GITHUB_REPOSITORY','hosseinghiedar-cmyk/Avanik-Travel')
REF=os.environ.get('GITHUB_SHA','HEAD')
ROOT=Path(__file__).resolve().parents[1]
BUILD=ROOT/'.hosting-build-v041'/'avanik'
OUT=ROOT/'dist'/'Avanik-Travel-v0.4.0-Hosting-Release.zip'
MAX_NAME=120

def main():
    if BUILD.parent.exists(): shutil.rmtree(BUILD.parent)
    BUILD.mkdir(parents=True)
    OUT.parent.mkdir(parents=True,exist_ok=True)
    if OUT.exists(): OUT.unlink()
    archive=ROOT/'.hosting-source-v041.tar.gz'
    url=f'https://codeload.github.com/{REPO}/tar.gz/{REF}'
    req=urllib.request.Request(url,headers={'User-Agent':'avanik-hosting-builder-v041'})
    with urllib.request.urlopen(req,timeout=120) as r: archive.write_bytes(r.read())
    renamed={}; copied=0
    with tarfile.open(archive,'r:gz') as tar:
        for m in tar:
            if not m.isfile() or '/wordpress/avanik/' not in m.name: continue
            rel=m.name.split('/wordpress/avanik/',1)[1]
            rp=Path(rel)
            if len(rp.name)>MAX_NAME:
                new=f'avanik_{hashlib.sha1(rel.encode()).hexdigest()[:12]}{rp.suffix.lower()}'
                renamed[rp.name]=new; rp=rp.with_name(new)
            target=BUILD/rp; target.parent.mkdir(parents=True,exist_ok=True)
            src=tar.extractfile(m)
            if src is None: continue
            target.write_bytes(src.read()); copied+=1
    for p in BUILD.rglob('*'):
        if not p.is_file(): continue
        try:s=p.read_text(encoding='utf-8')
        except UnicodeDecodeError:continue
        for old,new in renamed.items(): s=s.replace(old,new)
        p.write_text(s,encoding='utf-8')
    required=['style.css','functions.php','header.php','footer.php','front-page.php','inc/Theme.php','inc/ThemeSetup.php','inc/ThemeSettings.php','assets/css/avanik-reference-v041.css','assets/js/avanik-reference-v041.js','assets/images/avanik-logo.svg','assets/images/hero-istanbul.svg']
    missing=[x for x in required if not (BUILD/x).is_file()]
    if missing: raise SystemExit('Missing required theme files: '+', '.join(missing))
    functions=(BUILD/'functions.php').read_text(encoding='utf-8')
    front=(BUILD/'front-page.php').read_text(encoding='utf-8')
    settings=(BUILD/'inc/ThemeSettings.php').read_text(encoding='utf-8')
    if 'avanik-reference-v041.css' not in functions or 'avanik-reference-v041.js' not in functions: raise SystemExit('v0.4.1 assets are not enqueued')
    if 'avanik-passenger-field' not in front or 'hero_eyebrow' not in front: raise SystemExit('Reference search/hero wiring missing')
    if 'تنظیمات قالب' not in settings or 'add_menu_page' not in settings: raise SystemExit('Persian theme settings menu missing')
    bad=[str(p.relative_to(BUILD)) for p in BUILD.rglob('*') if p.is_file() and len(p.name)>MAX_NAME]
    if bad: raise SystemExit('Long filename remains: '+', '.join(bad))
    with zipfile.ZipFile(OUT,'w',zipfile.ZIP_DEFLATED) as z:
        for p in BUILD.rglob('*'):
            if p.is_file(): z.write(p,(Path('avanik')/p.relative_to(BUILD)).as_posix())
    archive.unlink(missing_ok=True); shutil.rmtree(BUILD.parent,ignore_errors=True)
    print(f'PASS: packaged {copied} files; shortened {len(renamed)} long names; output={OUT}')

if __name__=='__main__': main()
