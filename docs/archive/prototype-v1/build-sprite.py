# Builds prototype/assets/icons/sprite.html from the SVGs exported from Figma
# and injects it into index.html between the SPRITE markers.
#
# Every .svg in assets/icons/ becomes a <symbol id="i-<filename>">. Hard-coded
# fills/strokes are rewritten to currentColor so the icons follow the theme.
# A couple of UI glyphs Figma doesn't ship (menu, close, chevron) are defined
# inline below.
#
# Run:  python build-sprite.py
import re, pathlib

HERE = pathlib.Path(__file__).parent
ICONS = HERE / "assets" / "icons"

# lucide-style, 24x24, stroke = currentColor
HAND = {
    "menu": '<path d="M3 6h18M3 12h18M3 18h18"/>',
    "close": '<path d="M18 6 6 18M6 6l12 12"/>',
    "chevron-down": '<path d="m6 9 6 6 6-6"/>',
}

SVG_OPEN = re.compile(r"^<svg[^>]*>", re.S)
VIEWBOX = re.compile(r'viewBox="([^"]+)"')


def from_file(path):
    raw = path.read_text(encoding="utf-8")
    vb = VIEWBOX.search(raw).group(1)
    inner = SVG_OPEN.sub("", raw).replace("</svg>", "").strip()
    inner = re.sub(r'stroke="(?!none)[^"]*"', 'stroke="currentColor"', inner)
    inner = re.sub(r'fill="(?!none)[^"]*"', 'fill="currentColor"', inner)
    inner = re.sub(r'\s*id="[^"]*"', "", inner)
    return f'<symbol id="i-{path.stem}" viewBox="{vb}" fill="none">{inner}</symbol>'


def hand(name, body):
    return (
        f'<symbol id="i-{name}" viewBox="0 0 24 24" fill="none" stroke="currentColor" '
        f'stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{body}</symbol>'
    )


parts = [from_file(p) for p in sorted(ICONS.glob("*.svg"))]
parts += [hand(n, b) for n, b in HAND.items()]

sprite = (
    '<svg class="u-sprite" aria-hidden="true" focusable="false" '
    'xmlns="http://www.w3.org/2000/svg">\n  '
    + "\n  ".join(parts)
    + "\n</svg>"
)

out = ICONS / "sprite.html"
out.write_text(sprite, encoding="utf-8")

index = HERE / "index.html"
if index.exists():
    html = index.read_text(encoding="utf-8")
    html, n = re.subn(
        r"<!--SPRITE:START-->.*?<!--SPRITE:END-->",
        lambda m: "<!--SPRITE:START-->" + sprite + "<!--SPRITE:END-->",
        html,
        flags=re.S,
    )
    index.write_text(html, encoding="utf-8")
    print("index.html sprite block updated" if n else "WARNING: markers not found in index.html")

print(f"wrote {out} ({len(sprite)} bytes, {len(parts)} symbols)")
print("  " + ", ".join(sorted([p.stem for p in ICONS.glob('*.svg')] + list(HAND))))
