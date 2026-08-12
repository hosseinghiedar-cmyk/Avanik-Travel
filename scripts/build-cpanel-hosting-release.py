from __future__ import annotations

import base64
import hashlib
import json
import os
import shutil
import urllib.request
import zipfile
from pathlib import Path

REPO = os.environ.get("GITHUB_REPOSITORY", "hosseinghiedar-cmyk/Avanik-Travel")
REF = os.environ.get("GITHUB_SHA", "")
API = "https://api.github.com"
ROOT = Path(__file__).resolve().parents[1]
BUILD = ROOT / ".hosting-build" / "avanik"
OUT = ROOT / "dist" / "Avanik-Travel-v0.4.0-Hosting-Release.zip"
MAX_COMPONENT = 120


def api_get(path: str) -> dict:
    token = os.environ.get("GITHUB_TOKEN", "")
    req = urllib.request.Request(API + path, headers={
        "Accept": "application/vnd.github+json",
        "X-GitHub-Api-Version": "2022-11-28",
        "Authorization": f"Bearer {token}",
        "User-Agent": "avanik-cpanel-release-builder",
    })
    with urllib.request.urlopen(req, timeout=60) as r:
        return json.loads(r.read().decode("utf-8"))


def main() -> None:
    if not REF:
        raise SystemExit("GITHUB_SHA is required")
    if BUILD.exists():
        shutil.rmtree(BUILD)
    BUILD.mkdir(parents=True, exist_ok=True)
    OUT.parent.mkdir(parents=True, exist_ok=True)
    if OUT.exists():
        OUT.unlink()

    commit = api_get(f"/repos/{REPO}/commits/{REF}")
    tree_sha = commit["commit"]["tree"]["sha"]
    tree = api_get(f"/repos/{REPO}/git/trees/{tree_sha}?recursive=1")
    if tree.get("truncated"):
        raise SystemExit("Git tree response was truncated; refusing to build an incomplete hosting package")

    selected = [e for e in tree.get("tree", []) if e.get("type") == "blob" and e.get("path", "").startswith("wordpress/avanik/")]
    for entry in selected:
        rel = entry["path"][len("wordpress/avanik/"):]
        target = BUILD / rel
        target.parent.mkdir(parents=True, exist_ok=True)
        blob = api_get(f"/repos/{REPO}/git/blobs/{entry['sha']}")
        data = base64.b64decode(blob["content"].replace("\n", ""))
        target.write_bytes(data)

    for relative in ["INSTALL-HOSTING-v0.4.0.md", "RELEASE-MANIFEST-v0.4.0.json"]:
        p = BUILD / relative
        if p.exists():
            p.unlink()

    mapping: dict[str, str] = {}
    for path in sorted(BUILD.rglob("*"), key=lambda p: len(p.name), reverse=True):
        if not path.is_file() or len(path.name) <= MAX_COMPONENT:
            continue
        digest = hashlib.sha1(str(path.relative_to(BUILD)).encode("utf-8")).hexdigest()[:12]
        new_name = f"avanik_{digest}{path.suffix.lower()}"
        target = path.with_name(new_name)
        mapping[path.name] = new_name
        path.rename(target)

    for path in BUILD.rglob("*"):
        if not path.is_file():
            continue
        try:
            text = path.read_text(encoding="utf-8")
        except UnicodeDecodeError:
            continue
        original = text
        for old, new in mapping.items():
            text = text.replace(old, new)
        if text != original:
            path.write_text(text, encoding="utf-8")

    with zipfile.ZipFile(OUT, "w", compression=zipfile.ZIP_DEFLATED) as zf:
        for path in sorted(BUILD.rglob("*")):
            if path.is_file():
                zf.write(path, (Path("avanik") / path.relative_to(BUILD)).as_posix())

    print(f"Created: {OUT}")
    print(f"Source files packaged: {len(selected)}")
    print(f"Long filename components shortened: {len(mapping)}")


if __name__ == "__main__":
    main()
