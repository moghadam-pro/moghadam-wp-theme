# Roadmap

Each phase ends with a version bump, a changelog entry, a tag, and a push to
both branches.

| Phase | Version | Scope | Status |
| --- | --- | --- | --- |
| 0 | 1.0.0 | Clean WordPress starter theme | ✅ Done |
| 1 | 1.1.0 | Page template system | ✅ Done |
| 2 | 1.2.0 | Settings panel and Variables | ⬜ Next |
| 3 | 1.3.0 | Dark and light mode runtime | ⬜ Planned |
| 4 | 1.4.0 | Style guide wired to real variables | ⬜ Planned |
| — | — | Portfolio companion plugin | ⬜ Deferred |

---

## Phase 0 — Starter theme (1.0.0) ✅

Full template hierarchy, responsive layout, RTL support, CSS custom property
theming, `theme.json`, Customizer accent colour, menus, widget areas,
accessibility fundamentals, GPLv3 licence, translation template.

## Phase 1 — Page template system (1.1.0) ✅

Four selectable page templates — Canvas, Full Width, Home Page, Theme — beside
the WordPress default. Layout helpers in `inc/layout.php` so templates declare a
layout instead of hard-coding classes. Canvas behaviour in `inc/canvas.php`.
Style guide token definitions in `inc/style-guide.php`.

## Phase 2 — Settings panel and Variables (1.2.0) ⬜

A top-level **Moghadam** menu in the dashboard sidebar, built on the WordPress
Settings API, structured in sections so more can be added over time.

First section: **Variables** — the colour palette, the typography scale, and a
statement of where each token is used, held as two complete sets (light and
dark). Values are stored in a single option array and emitted as CSS custom
properties in `wp_head`, which is all the existing stylesheet needs to re-theme
the entire site.

Open questions for this phase:

- Does Variables replace the Customizer accent colour control, or coexist with
  it? Two places setting the same value would be a trap.
- Should `theme.json` be generated from the stored settings, so the block
  editor palette follows automatically instead of being maintained by hand?

## Phase 3 — Dark and light mode runtime (1.3.0) ⬜

The token sets from phase 2 become live. `prefers-color-scheme` picks the
default; a control lets the visitor override it; the choice persists in
`localStorage`. A small inline script in `<head>` applies the stored choice
before first paint — see
[D-006](02-decisions.md#d-006-dark-and-light-modes--automatic-with-a-toggle).

Also in scope: `add_editor_style()`, so the block editor renders content with
the same tokens as the front end, in whichever mode is active.

## Phase 4 — Style guide wired to real variables (1.4.0) ⬜

`inc/style-guide.php` starts reading the stored settings instead of returning
static definitions, so the `Theme` template shows the values actually in use and
cannot drift. Both modes rendered side by side.

## Deferred — Portfolio companion plugin

A separate plugin registering the `portfolio` post type and its taxonomy, so the
content is independent of the theme. The theme will then supply
`single-portfolio.php` and `archive-portfolio.php`. See
[D-005](02-decisions.md#d-005-portfolio-deferred-to-a-plugin).
