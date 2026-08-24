# Architecture

As of theme version 1.1.0.

## File layout

```
moghadam/
├── style.css                   Theme header only — no rules
├── rtl.css                     RTL corrections, loaded only when is_rtl()
├── theme.json                  Block editor palette, sizes, layout widths
├── functions.php               Constants, then requires each inc/ module
├── inc/
│   ├── setup.php               Theme supports, menus, widget areas, body classes
│   ├── enqueue.php             Front-end styles and scripts
│   ├── layout.php              Layout helpers and the sidebar gate
│   ├── canvas.php              Canvas template behaviour
│   ├── style-guide.php         Design token definitions for the style guide
│   ├── template-tags.php       Output helpers used inside templates
│   └── customizer.php          Customizer settings and live preview
├── page-templates/             Selectable page templates
├── template-parts/content/     Reusable content partials
├── assets/css/main.css         All front-end styling
├── assets/js/navigation.js     Mobile menu toggle
├── assets/js/customizer.js     Customizer live preview
└── languages/moghadam.pot      Translation template
```

## Loading order

`functions.php` defines three constants and then requires each module. Order
matters in one place: `layout.php` must load before `canvas.php`, because
`moghadam_has_sidebar()` calls `moghadam_is_canvas()`.

```php
MOGHADAM_VERSION   // asset cache-busting, matches style.css
MOGHADAM_DIR       // get_template_directory()
MOGHADAM_URI       // get_template_directory_uri()
```

Every constant is guarded with `if ( ! defined( ... ) )` so a child theme can
override it before the parent loads.

## Module responsibilities

### `inc/setup.php`

Everything registered on `after_setup_theme`: text domain, theme supports
(`title-tag`, `post-thumbnails`, `html5`, `custom-logo`, `align-wide`,
`responsive-embeds`, `wp-block-styles`, selective refresh), two nav menus
(`primary`, `footer`), two widget areas (`sidebar-1`, `footer-1`), the content
width, the pingback header, and extra body classes.

### `inc/enqueue.php`

`style.css` carries only the theme header; the actual rules are in
`assets/css/main.css`, enqueued with `moghadam-style` as a dependency so the
order is explicit. `rtl.css` is enqueued only when `is_rtl()` returns true.
`comment-reply` loads only where threaded comments are actually open.

### `inc/layout.php`

Templates declare a layout instead of hard-coding classes:

```php
<main id="primary" <?php moghadam_main_class( 'full-width' ); ?>>
```

| Function | Purpose |
| --- | --- |
| `moghadam_get_layouts()` | The four known layouts and their descriptions |
| `moghadam_get_main_class( $layout, $extra )` | Builds the class list |
| `moghadam_main_class( $layout, $extra )` | Echoes `class="…"` |
| `moghadam_has_sidebar()` | Whether the sidebar renders |

Layouts are `default`, `full-width`, `home` and `styleguide`; each produces a
`site-main--{slug}` class that `main.css` keys off. An unknown slug falls back
to `default` rather than emitting a broken class.

`sidebar.php` calls `moghadam_has_sidebar()` and returns early, so no template
needs its own guard.

### `inc/canvas.php`

Detects the Canvas template on `template_redirect` and applies the exceptions
described in [D-008](02-decisions.md#d-008-canvas-keeps-seo-drops-presentation).
Dequeuing runs on `wp_enqueue_scripts` at priority 100, late enough that
anything registered normally has already been added.

### `inc/style-guide.php`

Three filterable functions returning token definitions — colours, typography,
spacing — consumed by the `Theme` template. Currently static definitions
mirroring `:root` in `main.css`; phase 4 will source them from the stored
settings instead.

## Hooks and filters

### Actions

| Hook | Fires |
| --- | --- |
| `moghadam_home_before_content` | Before the Home Page template's content |
| `moghadam_home_after_content` | After the Home Page template's content |

### Filters

| Filter | Controls |
| --- | --- |
| `moghadam_content_width` | The global content width in pixels |
| `moghadam_main_class` | Classes on the `main` element |
| `moghadam_has_sidebar` | Whether the sidebar renders |
| `moghadam_canvas_dequeue_handles` | Assets stripped on Canvas |
| `moghadam_style_guide_colors` | Colour tokens in the style guide |
| `moghadam_style_guide_typography` | Typography tokens in the style guide |
| `moghadam_style_guide_spacing` | Spacing and sizing tokens |

## Styling model

`assets/css/main.css` is organised in fourteen numbered sections listed in a
table of contents at the top of the file. Keep that list accurate when adding a
section.

All design values are CSS custom properties on `:root`, prefixed
`--moghadam-`. Nothing in the stylesheet hard-codes a colour, a font stack or a
spacing value; every rule reads a token. This is what makes the Variables
settings section possible: overriding a token in `<head>` re-themes the whole
site with no stylesheet changes.

Direction-sensitive rules use CSS logical properties — `margin-inline`,
`inset-inline-start`, `padding-block`, `text-align: start` — so RTL mostly flips
for free. `rtl.css` only corrects the handful of rules that cannot use them,
namely the float-based `.alignleft` and `.alignright`.

`theme.json` mirrors the same palette, font sizes and layout widths for the
block editor. **The two files must be changed together**, or the editor and the
front end will drift apart.
