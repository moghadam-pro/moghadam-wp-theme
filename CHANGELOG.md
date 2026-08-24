# Changelog

All notable changes to this theme are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.0] - 2026-08-24

### Added

- **Theme settings screen.** A top-level **Moghadam** menu in the dashboard,
  built on the Settings API and driven by a tab registry (`inc/settings.php`),
  so further sections can be added without touching the rendering code. Includes
  a "Restore defaults" action.
- **Variables.** Every design token the theme exposes is now editable:
  - Colors, defined twice — once for light, once for dark.
  - Typography and spacing/sizing, shared by both modes.
  - Each token is listed with its CSS custom property name and a description of
    where it is used.
- `inc/variables.php`, holding the token schema that the settings screen, the
  front-end CSS and the block editor palette all read from, so the three cannot
  drift apart.
- Generated custom properties are attached inline to `moghadam-main`, in three
  blocks: the light set on bare `:root`, the dark set behind
  `prefers-color-scheme` guarded against an explicit light choice, and the dark
  set again under `:root[data-theme="dark"]`. Dark mode therefore already
  follows the operating system; the runtime toggle arrives in 1.3.0.
- Block editor palette and layout widths are filtered from the stored settings
  via `wp_theme_json_data_theme`, so `theme.json` provides the defaults and the
  settings provide the live values. Requires WordPress 6.1; on 6.0 the static
  `theme.json` values apply unchanged.
- Settings screen styles and colour picker wiring
  (`assets/css/admin.css`, `assets/js/admin.js`).
- New filters: `moghadam_variables_schema`, `moghadam_variables_css`,
  `moghadam_settings_tabs`, `moghadam_settings_capability`.

### Changed

- The Customizer no longer sets the accent colour. Colour, typography and
  spacing are configured only in **Moghadam → Variables**, which is now the
  single source of truth. The Customizer keeps the site title and tagline live
  preview, which belong to WordPress rather than to the theme.

### Security

- Every stored value is validated on save. Colours must be valid hex; sizes must
  be a CSS length, a unitless number, or a `calc()`/`clamp()`/`min()`/`max()`/
  `var()` expression built from safe characters; free-text values are stripped of
  anything that could terminate a declaration, open a rule, or pull in an
  external resource. Unknown groups and tokens are discarded rather than stored,
  and a value that fails validation falls back to its default.

## [1.1.0] - 2026-08-24

### Added

- **Page template system.** Four selectable templates alongside the WordPress
  default, registered from `page-templates/`:
  - **Canvas** — a bare HTML document for hand-written markup. No theme CSS, no
    JS, no header, footer or sidebar, and paragraph auto-formatting disabled so
    the markup arrives untouched. `wp_head()` and `wp_footer()` still run, so
    SEO meta, canonical URLs, Open Graph data, schema and plugin output are
    unaffected.
  - **Full Width** — header and footer, no sidebar, content at container width.
  - **Home Page** — the landing layout, with `moghadam_home_before_content` and
    `moghadam_home_after_content` action hooks.
  - **Theme** — a style guide page rendering every design token and styled
    element using the live values.
- Layout helpers in `inc/layout.php`: `moghadam_main_class()`,
  `moghadam_get_layouts()` and `moghadam_has_sidebar()`, with the
  `moghadam_main_class` and `moghadam_has_sidebar` filters.
- Style guide token definitions in `inc/style-guide.php`, filterable through
  `moghadam_style_guide_colors`, `moghadam_style_guide_typography` and
  `moghadam_style_guide_spacing`.
- Canvas behaviour in `inc/canvas.php`, including the
  `moghadam_canvas_dequeue_handles` filter for adjusting what gets stripped.
- Layout variant and style guide styles in `assets/css/main.css`.

### Changed

- `index.php`, `single.php`, `page.php`, `archive.php`, `search.php` and
  `404.php` now declare their layout through `moghadam_main_class()` instead of
  hard-coded classes.
- `sidebar.php` defers to `moghadam_has_sidebar()`, so templates that opt out of
  the sidebar no longer need their own guard.

## [1.0.0] - 2026-08-24

### Added

- Initial release: semantic HTML5 templates covering the WordPress template
  hierarchy, responsive layout with a mobile navigation toggle, RTL support
  built on CSS logical properties, CSS custom property theming, `theme.json`
  with palette, font sizes and layout widths, a Customizer accent colour with
  live preview, two menu locations, two widget areas, and accessibility
  fundamentals (skip link, screen reader text, focus styles, reduced motion).

[1.2.0]: https://github.com/moghadam-pro/moghadam-wp-theme/releases/tag/v1.2.0
[1.1.0]: https://github.com/moghadam-pro/moghadam-wp-theme/releases/tag/v1.1.0
[1.0.0]: https://github.com/moghadam-pro/moghadam-wp-theme/releases/tag/v1.0.0
