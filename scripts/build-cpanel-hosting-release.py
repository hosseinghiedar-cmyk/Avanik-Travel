from __future__ import annotations

import hashlib
import os
import re
import shutil
import zipfile
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
SOURCE = ROOT / "wordpress" / "avanik"
BUILD = ROOT / ".hosting-build" / "avanik"
OUT = ROOT / "dist" / "Avanik-Travel-v0.4.0-Hosting-Release.zip"

# Keep enough headroom for cPanel/WordPress extraction paths.
MAX_COMPONENT = 120


def main() -> None:
    if BUILD.exists():
        shutil.rmtree(BUILD)
    BUILD.parent.mkdir(parents=True, exist_ok=True)
    OUT.parent.mkdir(parents=True, exist_ok=True)
    if OUT.exists():
        OUT.unlink()

    shutil.copytree(SOURCE, BUILD)

    mapping: dict[str, str] = {}
    for path in sorted(BUILD.rglob("*"), key=lambda p: len(p.name), reverse=True):
        if not path.is_file():
            continue
        if len(path.name) <= MAX_COMPONENT:
            continue
        digest = hashlib.sha1(str(path.relative_to(BUILD)).encode("utf-8")).hexdigest()[:12]
        new_name = f"avanik_{digest}{path.suffix.lower()}"
        target = path.with_name(new_name)
        while target.exists():
            digest = hashlib.sha1((str(path) + new_name).encode("utf-8")).hexdigest()[:12]
            new_name = f"avanik_{digest}{path.suffix.lower()}"
            target = path.with_name(new_name)
        mapping[path.name] = new_name
        path.rename(target)

    # Rewrite filename references while keeping PHP class names untouched.
    if mapping:
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

    # Remove development-only material from the install package.
    for relative in ["INSTALL-HOSTING-v0.4.0.md", "RELEASE-MANIFEST-v0.4.0.json"]:
        p = BUILD / relative
        if p.exists():
            p.unlink()

    with zipfile.ZipFile(OUT, "w", compression=zipfile.ZIP_DEFLATED) as zf:
        for path in sorted(BUILD.rglob("*")):
            if path.is_file():
                arcname = Path("avanik") / path.relative_to(BUILD)
                zf.write(path, arcname.as_posix())

    print(f"Created: {OUT}")
    print(f"Renamed long filename components: {len(mapping)}")
    for old, new in sorted(mapping.items()):
        print(f"{old} -> {new}")


if __name__ == "__main__":
    main()
