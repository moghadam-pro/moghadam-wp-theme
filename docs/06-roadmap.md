# Roadmap

Each phase ends with a version bump, a changelog entry, a tag, and a push to
both branches.

| Phase | Version | Scope | Status |
| --- | --- | --- | --- |
| 0 | 1.0.0 | Clean WordPress starter theme | ✅ Done |
| 1 | 1.1.0 | Page template system | ✅ Done |
| 2 | 1.2.0 | Settings panel and Variables | ✅ Done |
| 3 | 1.3.0 | Dark and light mode runtime | ⬜ Next |
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

## Phase 2 — Settings panel and Variables (1.2.0) ✅

A top-level **Moghadam** menu built on the Settings API, driven by a tab
registry so further sections can be added without touching the rendering code.

**Variables** holds every design token: colours as two complete sets, light and
dark; typography and spacing shared by both; each listed with its custom
property name and a description of where it applies. Values live in one option
array and are emitted as custom properties attached inline to the main
stylesheet.

Both open questions were resolved:

- **Variables replaces the Customizer accent control.** One value settable from
  two screens is a trap. → [D-011](02-decisions.md#d-011-variables-is-the-single-source-of-truth-for-design-tokens)
- **`theme.json` is filtered at runtime, not rewritten.** The file stays as the
  default; stored values are overlaid via `wp_theme_json_data_theme`, so the
  editor palette follows the settings and cannot drift.
  → [D-012](02-decisions.md#d-012-themejson-is-filtered-at-runtime-not-rewritten)

Dark mode already follows the operating system as a consequence of how both
sets are written. → [D-013](02-decisions.md#d-013-both-colour-sets-are-always-written)

## Phase 3 — Dark and light mode runtime (1.3.0) ⬜

The automatic half already works. What remains is the visitor's override: a
control that sets `data-theme` on the root element, persistence in
`localStorage`, and a small inline script in `<head>` that applies the stored
choice before first paint — see
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
