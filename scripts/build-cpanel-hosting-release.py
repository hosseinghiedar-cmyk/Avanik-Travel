from __future__ import annotations
import hashlib, os, re, shutil, tarfile, urllib.request, zipfile
from pathlib import Path
REPO=os.environ.get('GITHUB_REPOSITORY','hosseinghiedar-cmyk/Avanik-Travel');REF=os.environ.get('GITHUB_SHA','HEAD');ROOT=Path(__file__).resolve().parents[1];BUILD=ROOT/'.hosting-build'/'avanik';OUT=ROOT/'dist'/'Avanik-Travel-v0.4.0-Hosting-Release.zip';MAX_COMPONENT=120
def download_source_archive():
 url=f'https://codeload.github.com/{REPO}/tar.gz/{REF}';archive=ROOT/'.hosting-source.tar.gz';req=urllib.request.Request(url,headers={'User-Agent':'avanik-cpanel-release-builder'});
 with urllib.request.urlopen(req,timeout=120) as r:archive.write_bytes(r.read())
 return archive
def declared_symbols(path):
 try:text=path.read_text(encoding='utf-8')
 except UnicodeDecodeError:return []
 pattern=r'\b(?:final\s+|abstract\s+)?class\s+([A-Za-z_][A-Za-z0-9_]*)|\binterface\s+([A-Za-z_][A-Za-z0-9_]*)|\btrait\s+([A-Za-z_][A-Za-z0-9_]*)';return [next(g for g in m.groups() if g) for m in re.finditer(pattern,text)]
def phase_number(path):
 try:text=path.read_text(encoding='utf-8')
 except UnicodeDecodeError:return -1
 nums=[int(x) for x in re.findall(r'Phase\s*(\d{3})',text,re.I)];return max(nums) if nums else -1
def remove_duplicate_symbols():
 symbols={}
 for p in BUILD.rglob('*.php'):
  for symbol in declared_symbols(p):symbols.setdefault(symbol,[]).append(p)
 removed=[]
 for symbol,paths in symbols.items():
  if len(paths)<=1:continue
  keep=max(paths,key=lambda p:(phase_number(p),str(p)))
  for p in paths:
   if p!=keep:p.unlink();removed.append((symbol,str(p.relative_to(BUILD)),str(keep.relative_to(BUILD))))
 remaining={}
 for p in BUILD.rglob('*.php'):
  for symbol in declared_symbols(p):remaining.setdefault(symbol,[]).append(p)
 dup={k:v for k,v in remaining.items() if len(v)>1}
 if dup:raise SystemExit('Duplicate PHP class/interface/trait remains: '+', '.join(sorted(dup)))
 return removed
def verify_theme():
 required=['style.css','functions.php','header.php','footer.php','front-page.php','inc/PhaseLoader.php','inc/Theme.php','inc/ThemeSetup.php','inc/ThemeSettings.php','assets/css/avanik-theme.css','assets/css/avanik-v049.css','assets/js/avanik-demo.js','assets/js/avanik-v049.js','assets/images/avanik-logo.svg','assets/images/avanik-logo-v2.svg','assets/images/avanik-hero.svg']
 for rel in required:
  if not (BUILD/rel).is_file():raise SystemExit(f'Missing required theme asset: {rel}')
 style=(BUILD/'style.css').read_text(encoding='utf-8')
 if not re.search(r'^Version:\s*0\.4\.0\s*$',style,re.M):raise SystemExit('Theme version metadata is not 0.4.0')
 text=(BUILD/'functions.php').read_text(encoding='utf-8')
 if 'ThemeSettings.php' not in text or 'settingsClass' not in text:raise SystemExit('ThemeSettings bootstrap is missing')
 if 'ThemeSetup' not in text or 'setupClass' not in text:raise SystemExit('ThemeSetup bootstrap is missing')
 if 'Theme.php' not in text or 'themeClass' not in text:raise SystemExit('Theme bootstrap is missing')
 settings_text=(BUILD/'inc/ThemeSettings.php').read_text(encoding='utf-8')
 if 'add_menu_page' not in settings_text or 'تنظیمات قالب' not in settings_text:raise SystemExit('Persian Avanik admin menu is missing')
 for p in BUILD.rglob('*.php'):
  if len(p.name)>MAX_COMPONENT:raise SystemExit('Long filename remains after packaging: '+str(p.relative_to(BUILD)))
def main():
 if BUILD.exists():shutil.rmtree(BUILD)
 BUILD.mkdir(parents=True);OUT.parent.mkdir(parents=True,exist_ok=True)
 if OUT.exists():OUT.unlink()
 archive=download_source_archive();mapping={};count=0;source_count=0
 with tarfile.open(archive,'r:gz') as tar:
  for member in tar:
   if not member.isfile():continue
   marker='/wordpress/avanik/'
   if marker not in member.name:continue
   rel=member.name.split(marker,1)[1]
   if rel in {'INSTALL-HOSTING-v0.4.0.md','RELEASE-MANIFEST-v0.4.0.json'}:continue
   rel_path=Path(rel);name=rel_path.name
   if len(name)>MAX_COMPONENT:
    new=f"avanik_{hashlib.sha1(rel.encode()).hexdigest()[:12]}{rel_path.suffix.lower()}";mapping[name]=new;rel_path=rel_path.with_name(new);count+=1
   target=BUILD/rel_path;target.parent.mkdir(parents=True,exist_ok=True);source=tar.extractfile(member)
   if source is None:continue
   target.write_bytes(source.read());source_count+=1
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
 archive.unlink(missing_ok=True);print(f'Created: {OUT}');print(f'Source files copied: {source_count}');print(f'Long filename components shortened: {count}');print(f'Duplicate symbols removed: {len(removed)}')
if __name__=='__main__':main()
