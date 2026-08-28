# Builds prototype/assets/icons/sprite.html from the SVGs exported from Figma
# plus a set of hand-written lucide-style icons for the ones Figma didn't expose.
# Run:  python build-sprite.py     (re-run after adding/replacing an .svg)
import re, pathlib

HERE = pathlib.Path(__file__).parent
ICONS = HERE / "assets" / "icons"

# name in sprite  ->  exported file
FROM_FIGMA = {
    "logotype": "logotype.svg",
    "sun": "theme-sun.svg",
    "linkedin": "linkedin.svg",
    "dribbble": "dribbble.svg",
    "upwork": "upwork.svg",
    "figma": "figma.svg",
    "github": "github.svg",
    "arrow-up-right": "arrow-up-right.svg",
}

# lucide-style, 24x24, stroke = currentColor
HAND = {
    "moon": '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/>',
    "menu": '<path d="M3 6h18M3 12h18M3 18h18"/>',
    "close": '<path d="M18 6 6 18M6 6l12 12"/>',
    "download-cloud": '<path d="M12 13v8m-4-4 4 4 4-4M20 16.58A5 5 0 0 0 18 7h-1.26A8 8 0 1 0 4 15.25"/>',
    "twitter": '<path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 12 3 3c2.2 2.6 5.6 4.1 9 4-.9-4.2 5-6.6 7-3h3Z"/>',
    "facebook": '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
    "youtube": '<path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><path d="m9.75 15.02 5.75-3.27-5.75-3.27v6.54z"/>',
    "instagram": '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37Z"/><path d="M17.5 6.5h.01"/>',
    "pinterest": '<path d="M12 2a10 10 0 0 0-3.65 19.31c-.09-.78-.17-1.98.04-2.83l1.16-4.93s-.3-.6-.3-1.48c0-1.39.81-2.43 1.81-2.43.85 0 1.26.64 1.26 1.41 0 .86-.55 2.14-.83 3.33-.24 1 .5 1.81 1.48 1.81 1.78 0 3.15-1.88 3.15-4.58 0-2.4-1.72-4.07-4.18-4.07a4.33 4.33 0 0 0-4.52 4.34c0 .86.33 1.78.75 2.28a.3.3 0 0 1 .07.29l-.28 1.14c-.05.18-.15.22-.34.13-1.25-.58-2.03-2.4-2.03-3.87 0-3.15 2.29-6.04 6.6-6.04 3.46 0 6.16 2.47 6.16 5.77 0 3.44-2.17 6.22-5.19 6.22-1.01 0-1.96-.53-2.29-1.15l-.62 2.38c-.23.87-.84 1.96-1.25 2.62A10 10 0 1 0 12 2Z"/>',
    "telegram": '<path d="m22 3-2.6 17.2a1 1 0 0 1-1.55.66l-4.86-3.45-2.5 2.42a.75.75 0 0 1-1.27-.5l-.15-3.9L20.1 5.6a.35.35 0 0 0-.42-.55L6.7 12.5 2.6 11.2a1 1 0 0 1-.03-1.9L20.75 2.1A1 1 0 0 1 22 3Z"/>',
    "medium": '<path d="M6.5 7.5a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Zm8.25 0c-1.24 0-2.25 2.02-2.25 4.5s1.01 4.5 2.25 4.5S17 14.48 17 12s-1.01-4.5-2.25-4.5Zm5.5.5c-.55 0-1 1.79-1 4s.45 4 1 4 1-1.79 1-4-.45-4-1-4Z"/>',
    "whatsapp": '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"/><path d="M9 9.5c0 2.5 2 4.5 4.5 4.5"/>',
    "reddit": '<circle cx="12" cy="12" r="10"/><path d="M8.5 13.5h.01M15.5 13.5h.01M9 16.5c1.8 1.2 4.2 1.2 6 0"/><path d="M12 7.5 13 3l3.5.8"/><path d="M18.5 4.3h.01"/>',
    "behance": '<path d="M2 6.5h5.5a2.75 2.75 0 0 1 0 5.5H2Zm0 5.5h6a2.75 2.75 0 0 1 0 5.5H2Z"/><path d="M14.5 13.5h7a3.5 3.5 0 1 0-7 0c0 2 1.5 3.5 3.5 3.5 1.4 0 2.4-.5 3-1.4"/><path d="M15.5 6.5h5"/>',
    "logomark": '<path d="M2 20V9.5C2 6.46 3.9 4.5 6.7 4.5c2.1 0 3.6 1.1 4.3 2.9.7-1.8 2.2-2.9 4.3-2.9 2.8 0 4.7 1.96 4.7 5V20"/>',
}

SVG_OPEN = re.compile(r"^<svg[^>]*>", re.S)
VIEWBOX = re.compile(r'viewBox="([^"]+)"')


def from_figma(name, filename):
    raw = (ICONS / filename).read_text(encoding="utf-8")
    vb = VIEWBOX.search(raw).group(1)
    inner = SVG_OPEN.sub("", raw).replace("</svg>", "").strip()
    inner = re.sub(r'stroke="(?!none)[^"]*"', 'stroke="currentColor"', inner)
    inner = re.sub(r'fill="(?!none)[^"]*"', 'fill="currentColor"', inner)
    inner = re.sub(r'\s*id="[^"]*"', "", inner)
    return f'<symbol id="i-{name}" viewBox="{vb}" fill="none">{inner}</symbol>'


def hand(name, body):
    return (
        f'<symbol id="i-{name}" viewBox="0 0 24 24" fill="none" stroke="currentColor" '
        f'stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{body}</symbol>'
    )


parts = [from_figma(n, f) for n, f in FROM_FIGMA.items()]
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
    html = re.sub(
        r"<!--SPRITE:START-->.*?<!--SPRITE:END-->",
        "<!--SPRITE:START-->" + sprite + "<!--SPRITE:END-->",
        html,
        flags=re.S,
    )
    index.write_text(html, encoding="utf-8")
    print("index.html sprite block updated")

print(f"wrote {out} ({len(sprite)} bytes, {len(parts)} symbols)")
