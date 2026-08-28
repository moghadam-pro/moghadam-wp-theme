# Moghadam.pro — static prototype

HTML/CSS/JS build of the Figma page **Moghadam.pro**
(`YYI60QGk4QepyFUld188Jw`, page `0:1`). Standalone — it does not touch the
WordPress theme in the repo root.

## Run

```bash
python -m http.server 5173 --directory prototype
```

Then open <http://localhost:5173>. (There is also a `prototype` entry in
`.claude/launch.json`.) A server is needed — `file://` blocks the SVG sprite
and the Google Fonts request.

## Files

```
prototype/
  index.html              markup + inlined SVG sprite (generated block)
  css/style.css           tokens, layout, components, responsive
  js/main.js              all behaviour
  build-sprite.py         regenerates the sprite and re-injects it into index.html
  assets/icons/*.svg      exported from Figma (logotype, sun, social, arrow)
  assets/icons/sprite.html generated — do not edit by hand
  assets/img/portrait.png  duotone portrait, cut from the Figma render
```

Dependencies come from CDN: **GSAP 3.12 + ScrollTrigger** and **Lenis 1.1**.
If either fails to load the page degrades to a plain, fully scrollable
document (the `js`/`lock` classes on `<html>` are removed).

## Design tokens

All Figma variables are CSS custom properties in `:root`
(`--natural-900 … --natural-25`, `--white-100/40/30`, `--veryperi-400`,
`--darkblue-500`). Two values that were **not** variables in Figma are now
tokenised here: `--yellow: #eeaa00` and the page background `--bg: #fffcfa`.

A full dark palette lives under `[data-theme="dark"]` and drives the theme
switch that the design already had in the header. Figma has no dark artboard,
so those values are derived, not copied.

Layout numbers are 1:1 with Figma: container 1200 (120 side margins at
1440), 16px inner padding, 12 guide lines at ~109px, section top padding 120,
case rows 734 / 60 / 406, work cards 4 x 288 + 3 x 16.

## Interactions

| Behaviour | Where |
|---|---|
| Hero only on first load; the rest mounts on the first scroll intent (wheel / touch / arrow key / clicking "Scroll Down") | `reveal()`, `armFirstScroll()` |
| Header morphs from the in-hero bar to the floating pill, animated | `setupHeader()` |
| Two marquee bars, opposite directions, slow (38 px/s; the yellow row drifts ~18% slower). Pauses on hover | `setupMarquees()` + `@keyframes marquee-l/-r` |
| Case-study rows: the black row is the hover state — background, title, metadata and arrow all transition in | CSS `.case-row:hover` |
| Section 05 pins and the left column steps through 4 blocks (fade out / fade in) before the page continues; snaps to each step | `setupPinnedSteps()` |
| Per-section reveal on scroll; headlines animate word by word | `observeSection()`, `splitWords()` |
| Hero terminal types itself out | `runTerminal()` |
| Live Istanbul clock, theme toggle, works filter, mobile drawer | `main.js` sections 1, 2, 7, 10 |

`prefers-reduced-motion` disables typing, word staggers, snapping and smooth
scroll; everything stays readable.

## Responsive

* **≥1280** — the Figma desktop design.
* **1024–1279** — fluid container, tighter type scale, case rows flex.
* **≤1023** — hamburger + full-screen drawer, single-column About / How,
  2-up works grid, case rows stack, **section 05 pin is disabled** and all four
  steps are shown stacked.
* **≤767** — single-column works grid, smaller terminal.

## Known gaps / assumptions

1. **Sticky-header corner radius** is `12px` — the Figma MCP hit its plan rate
   limit before that frame could be rendered, so it is a judgement call
   consistent with the 8px buttons and square tags.
2. **Nav mapping.** Figma's first-load header has About / Portfolio / Services /
   Contact but there is no Services section, and the `home` + sticky headers
   only list three links. Here: hero keeps the four, `Services → #how`,
   `Portfolio → #cases`; the pill keeps the designed three.
3. **Contrast.** The yellow buttons use `#111112` text, not the white from
   Figma — white on `#eeaa00` is ≈1.9:1 and fails WCAG AA. The white text on
   the yellow case-study section is untouched (it is decorative/hover-revealed)
   but is worth revisiting.
4. **Icons.** Logotype, sun, LinkedIn, Dribbble, Upwork, Figma, GitHub and the
   arrow are the real Figma exports. The other 9 footer socials, moon,
   download-cloud, menu and close are hand-written in the same lucide style —
   swap them for exports when the Figma quota resets, then re-run
   `python build-sprite.py`.
5. **Portrait** is cut from a Figma render, so its transparency above the orange
   plate is a colour-key, not a real alpha channel. Replace
   `assets/img/portrait.png` with the original asset when available.
6. **Visual works** cards are the `001`–`007` placeholders from the design;
   `data-cat` values are assigned so the filter can be demonstrated.
7. `#rest` must never receive a CSS transform — it would become the containing
   block for ScrollTrigger's `position: fixed` pin and break section 05.
