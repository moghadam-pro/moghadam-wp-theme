# Changelog

All notable changes to this theme are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.5.0] - 2026-08-30

### Added

- **Related posts** under every article. Each other post is scored on what it
  shares with the one being read — a tag in common is worth three, a category
  one — and the four best are shown, newest first on a tie. It is one query
  against the term relationships table however large the site gets, and it
  falls back to the newest posts in the same categories when a post has too
  few relatives (`moghadam_related_posts()`).
- **Comment spam guard**, entirely in the theme: a field bots fill in and
  people never see, plus a signed timestamp so a comment posted within four
  seconds of the page loading is refused. Nothing is sent anywhere and nothing
  about the commenter is stored.
- A **previous/next** pair rebuilt as two cards carrying the featured image.

### Changed

- Article typography reworked: the space above a heading now belongs to the
  heading, quotes carry an accent rule, code blocks and inline code get a
  surface and a border, lists have real markers and leading, and figures and
  captions are spaced as a unit.
- Archives, the blog index and search results are a row listing with a small
  thumbnail, date, category, title and trimmed excerpt.
- The sidebar sticks alongside a long article instead of scrolling away.
- Short pages fill the window so the footer stays at the bottom.

### Removed

- The date and byline under a single post title are now visually hidden. They
  stay in the markup with their microformat classes for search engines; the
  sidebar is where a reader sees them.
- The categories and tags repeated below a post; the sidebar already lists
  them.

## [1.4.0] - 2026-08-30

### Added

- **Post sidebar** built from blocks rather than widgets, so the same set can
  be the site default and still be overridden on any single post
  (`inc/post-sidebar.php`). Ships with About this post (date, author and
  avatar, reading time, comment count, categories and tags), Search, All
  categories, and a per-post free text block.
- A **Sidebar** box on posts and pages: keep the site default or choose the
  blocks for that post alone.
- **Appearance > Customize > Post Sidebar** for the site-wide default.
- New pages start on the **Full Width** template.

### Changed

- Single posts and default-template pages lay the content out beside the
  sidebar instead of stacking an unstyled widget area underneath it.
- The hero drops its terminal block when its own content is taller than the
  space it has. It is a fit test, not a breakpoint: a short laptop screen
  needs it and a phone does not.

### Removed

- The **Theme** style guide template and `inc/style-guide.php`.
- The **Home Page** template. The front page is rendered by `front-page.php`;
  the template only duplicated it.

### Fixed

- Reveal animations on short pages never played. A trigger at `top 88%` on a
  page barely taller than the window asks for a scroll position past the end
  of the document, so the footer's rule, colophon and social row sat at
  opacity 0 forever. Anything unreachable now plays as soon as the layout
  settles.

## [1.3.0] - 2026-08-29

### Added

- **The Moghadam.pro design layer**, applied theme-wide rather than only to the
  front page: header, footer, background guide lines, typography, colour tokens
  and the reveal animations all load on every request
  (`inc/design.php`, `assets/css/design.css`, `assets/js/design.js`).
- **Appearance > Customize > Edit Home.** A panel with one section per home
  section, generated from a single schema (`inc/home/home-settings.php`) so the
  editor and the templates cannot disagree about what exists. Seventy-eight
  fields across Social Links, Hero, Marquee, About, Case Studies, Visual Work,
  How I Work, Call to Action and Footer.
- **Social links**, defined once in the Customizer as label / URL / SVG blocks
  and reused by the hero and the footer, each choosing which of them to show
  (`inc/social.php`).
- **Case study rows from posts** (`inc/case-studies.php`). The four newest posts
  in the chosen category fill section 03: the title becomes the row name, the
  post tags become the bracketed line, a Case Study meta box supplies the two
  right-hand lines, and the row links to the single post.
- **Portfolio bridge** for section 04 (`inc/portfolio.php`). Detects the
  installed portfolio plugin, renders every filter with its own newest items so
  each filter always shows a full set, and hides the section entirely when no
  plugin answers. Unknown plugins can register through the
  `moghadam_portfolio_source` filter.
- **SVG sprite** built from `assets/icons` and printed once per request, with
  fills and strokes rewritten to `currentColor` (`inc/icons.php`).
- A third menu location, **Footer More Links**, feeding the footer dropdown.
- `front-page.php`, and a `moghadam_footer_end` hook so the home page can wrap
  the footer in its deferred `#rest` container.

### Changed

- `header.php` and `footer.php` now render the new design: the floating pill
  header, the in-hero bar, the mobile drawer and the footer with its dropdown.
- The Home Page template renders the design sections instead of block content.
- Menus in the header, footer, footer dropdown and drawer all come from
  WordPress nav menu locations.

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
