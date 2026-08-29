# Moghadam.pro — static prototype

HTML/CSS/JS build of the Moghadam.pro home page. Standalone — it does not touch
the WordPress theme in the repo root.

## Run

```bash
python -m http.server 5173 --directory prototype
```

Then open <http://localhost:5173>. (There is also a `prototype` entry in
`.claude/launch.json`.) A server is needed — `file://` blocks the SVG sprite and
the Google Fonts request.

## Files

```
prototype/
  index.html               markup + inlined SVG sprite (generated block)
  css/style.css            tokens, layout, components, responsive
  js/main.js               all behaviour
  build-sprite.py          rebuilds the sprite from assets/icons/*.svg and
                           re-injects it into index.html
  assets/icons/*.svg       the exported icon set — edit/replace freely
  assets/icons/sprite.html generated — do not edit by hand
  assets/img/about-img@2x.png  About-section portrait (560x420, shown at 280x210)
```

Dependencies come from CDN: **GSAP 3.12 + ScrollTrigger** and **Lenis 1.1**. If
either fails to load the page degrades to a plain, fully scrollable document
(the `js`/`lock` classes on `<html>` are removed).

`style.css` and `main.js` are linked with a `?v=` query — bump it when you want
to force a cache refresh.

## Icons

Every `.svg` in `assets/icons/` becomes a `<symbol id="i-<filename>">`.
Hard-coded fills and strokes are rewritten to `currentColor`, so icons inherit
the theme. To add or replace one, drop the file in and run:

```bash
python prototype/build-sprite.py
```

`menu`, `close` and `chevron-down` are defined inline in the script because
they are UI glyphs, not part of the design export.

## Design tokens

Figma variables are CSS custom properties in `:root`
(`--natural-900 … --natural-25`, `--white-100/40/30`, `--veryperi-400`). Values
Figma hard-coded are tokenised here: `--yellow: #eeaa00`, `--bg: #fffdfa`,
`--moon: #9fbafe`.

A full dark palette lives under `[data-theme="dark"]` and drives the header
theme switch. There is no dark artboard in Figma, so those values are derived.

Layout numbers match the design: container 1200 (120 side margins at 1440),
16px inner padding, section block padding a fixed **120px top and bottom**
(hero, marquee, CTA and footer set their own), case rows 734 / 60 / 406, work
cards 4 × 288 + 3 × 16.

### Background grid

Twelve identical 1px guide lines at container width sit behind **every**
section, on a 109px pitch. They come from one repeating gradient whose interval
is `calc((100% - 1px) / 11)`, so the twelfth line lands flush on the right edge
— no border is used, because a border would paint on top of the last gradient
line and read twice as dark.

The colour comes from a per-section `--grid-line`, always a faint step off that
section's background:

| Section background | `--grid-line` |
|---|---|
| light (`#fffdfa`) | `rgba(17,17,18,.055)` |
| yellow (case studies) | `rgba(255,255,255,.12)` |
| dark (CTA) | `rgba(255,255,255,.05)` |
| dark theme | `rgba(255,255,255,.05)` |

The grid is hidden below 768px.

## Interactions

| Behaviour | Where |
|---|---|
| Hero only on first load; the rest mounts on the first scroll intent (wheel / touch / arrow key / clicking "Scroll Down") | `reveal()`, `armFirstScroll()` |
| Header morphs from the in-hero bar to the floating pill (fully rounded, `backdrop-filter: blur(18px) saturate(160%)`) | `setupHeader()` |
| Scroll hint: a soft band of colour drifts down the hairline and fades out | `@keyframes scrollfill` |
| Two marquee bars, opposite directions, slow (20 px/s; the yellow row 15% slower). Pauses on hover | `setupMarquees()` |
| Case-study rows sit on the same hairline as the background grid; the black row is the hover state — background, borders, title, metadata and arrow all transition in | CSS `.case-row:hover` |
| Section 05 fills the viewport with its content centred, pins, steps through 4 blocks (fade out / fade in) with a snap, then releases to the CTA and footer | `setupPinnedSteps()` + `@media (min-width:1024px) .how` |
| Footer "More Links" is a dropdown anchored to its trigger, closed by outside click or Escape | `setupFooterMore()` |
| Live Istanbul clock, theme toggle, work filter, mobile drawer | `main.js` §1, §2, §6, §10 |

### Per-section load motion

`main.js` defines a small reveal vocabulary (`§4`) and a `CHOREO` map so each
section arrives in a way that suits its own shape. Everything is
transform / opacity / clip-path only, runs **once**, and is scoped to a single
timeline per section — no scrubbing, no filters, nothing that forces layout.

| `data-anim` | Motion | Used by |
|---|---|---|
| `fade` | y + opacity | body copy, links |
| `lines` | word-by-word stagger | every headline |
| `wipe` | `clip-path` from the left | the portrait |
| `draw-x` | `scaleX` from the left | the SKILLS rule, the footer rule |
| `slide-l` / `slide-r` | x + opacity, opposite ways | the two marquee bars |
| `cards` | grid-ordered stagger | the work grid |
| `skills` | fast list stagger | the SKILLS list |
| `stagger` | children fade-up | footer links, social row |
| `row` | clip wipe + the centre line drawing down | case-study rows |

`prefers-reduced-motion` skips every reveal and leaves the content in place.

## Responsive

* **≥1280** — the desktop design.
* **1024–1279** — fluid container, tighter type scale, case rows flex.
* **≤1023** — hamburger + full-screen drawer, single-column About / How, 2-up
  work grid, case rows stack, **section 05 pin is disabled** and all four steps
  show stacked.
* **≤767** — single-column work grid, no background grid, no terminal, filter
  separators dropped so the filter row can wrap.

## Notes and assumptions

1. **The terminal is a placeholder.** The five static lines in `index.html` are
   marked with a comment — replace them with the shortcode from the terminal
   plugin. Hidden below 768px. No JS drives it.
2. **The SKILLS list was transcribed from a screenshot** and is worth a
   proofread — a couple of entries were hard to read at that resolution.
3. **Portrait** is the supplied 2× export. Replace
   `assets/img/about-img@2x.png` with another 560 × 420 file to change it; the
   CSS always renders it at 280 × 210.
4. **Contrast.** The yellow buttons use white text as designed — that is ≈1.9:1
   against `#eeaa00` and fails WCAG AA. Same for the faded white text on the
   yellow case-study section. Worth revisiting before launch.
5. **Work cards** are the `001`–`007` placeholders; `data-cat` values are
   assigned so the filter can be demonstrated.
6. `svg` deliberately has **no** `max-width: 100%` in the reset. With an
   explicit box and no intrinsic size, that percentage is circular inside a
   grid or flex track and browsers resolve icons at half width.
7. `#rest` must never receive a CSS transform — it would become the containing
   block for ScrollTrigger's `position: fixed` pin and break section 05.

---

**Archived.** This prototype lives on the `docs` branch only. The design now
ships inside the theme itself on `main` — `assets/css/design.css`,
`assets/js/design.js`, `template-parts/home/` and `inc/` — and that copy is the
source of truth. Fixes made after the port are not backported here.
