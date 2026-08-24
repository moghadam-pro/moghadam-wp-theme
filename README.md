# Moghadam

A clean, lightweight and standards-compliant WordPress starter theme, built for [moghadam.pro](https://moghadam.pro/).

No frameworks, no build step, no bloat — just semantic HTML5, accessible markup and modern CSS.

| | |
|---|---|
| **Version** | 1.2.0 |
| **Requires WordPress** | 6.0+ |
| **Requires PHP** | 7.4+ |
| **License** | [GPL v3 or later](https://www.gnu.org/licenses/gpl-3.0.html) |
| **Text Domain** | `moghadam` |

## Features

- Semantic HTML5 templates covering the full WordPress template hierarchy
- Five page templates, including a Canvas template for hand-written markup
- Fully responsive layout with a mobile navigation toggle
- Right-to-left (RTL) ready — built on CSS logical properties
- Theme settings screen with editable design tokens for colors, typography and spacing
- Light and dark palettes, following the visitor's system preference
- Theming through CSS custom properties (colors, typography, spacing)
- `theme.json` support: color palette, font sizes, layout widths, wide/full alignment
- Two menu locations (primary, footer) and two widget areas (sidebar, footer)
- Custom logo, post thumbnails, block styles, responsive embeds
- Accessible: skip link, screen-reader text, visible focus styles, reduced-motion support
- Translation ready via the bundled `.pot` file
- Zero dependencies and no build tooling required

## Installation

### From a ZIP archive

1. Download this repository as a ZIP.
2. In WordPress go to **Appearance → Themes → Add New → Upload Theme**.
3. Select the ZIP file, install it, then click **Activate**.

### Via Git

```bash
cd wp-content/themes
git clone https://github.com/moghadam-pro/moghadam-wp-theme.git moghadam
```

Then activate **Moghadam** from **Appearance → Themes**.

## Structure

```
moghadam/
├── assets/
│   ├── css/main.css          # Main stylesheet
│   ├── css/admin.css         # Settings screen styles
│   ├── js/navigation.js      # Mobile menu toggle
│   ├── js/customizer.js      # Customizer live preview
│   └── js/admin.js           # Settings screen behaviour
├── inc/
│   ├── setup.php             # Theme supports, menus, widget areas
│   ├── enqueue.php           # Scripts and styles
│   ├── variables.php         # Design token schema, CSS and theme.json bridge
│   ├── settings.php          # Dashboard settings screen
│   ├── layout.php            # Layout helpers
│   ├── canvas.php            # Canvas template behaviour
│   ├── style-guide.php       # Style guide token definitions
│   ├── template-tags.php     # Custom template tags
│   └── customizer.php        # Customizer settings
├── page-templates/
│   ├── template-canvas.php     # Canvas
│   ├── template-full-width.php # Full Width
│   ├── template-home.php       # Home Page
│   └── template-styleguide.php # Theme (style guide)
├── template-parts/content/   # Reusable content templates
├── languages/moghadam.pot    # Translation template
├── 404.php
├── CHANGELOG.md
├── archive.php
├── comments.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── rtl.css
├── search.php
├── searchform.php
├── sidebar.php
├── single.php
├── style.css                 # Theme header
└── theme.json
```

## Page templates

Content is written in the standard WordPress editor. Templates only supply
structure and styling; pick one per page under **Page → Template** in the
editor sidebar.

| Template | Header / footer | Content width | Sidebar |
| --- | --- | --- | --- |
| Default Template | Yes | Content (800px) | Yes |
| Full Width | Yes | Container (1100px) | No |
| Home Page | Yes | Container, full-bleed capable | No |
| Theme | Yes | Container | No |
| Canvas | No | Unstyled | No |

### Canvas

Canvas exists for pages whose HTML, CSS and JS are written by hand and must
render exactly as authored. The theme contributes nothing to the page: its
stylesheets, its scripts and the `theme.json` global styles are all dequeued,
there is no site chrome, the admin bar is suppressed, and `wpautop` is switched
off so markup is not reformatted.

What is deliberately kept is everything SEO depends on. `wp_head()` and
`wp_footer()` run in full, so the title tag, canonical URL, meta description,
Open Graph and Twitter tags, JSON-LD schema and any other plugin output appear
exactly as they do elsewhere on the site.

Adjust what gets stripped with the `moghadam_canvas_dequeue_handles` filter:

```php
add_filter( 'moghadam_canvas_dequeue_handles', function ( $handles ) {
	$handles['styles'][] = 'wp-block-library';
	return $handles;
} );
```

### Theme (style guide)

Renders every design token — colours, typography, spacing and sizing — next to
live examples of each styled element, using the values currently in effect. Any
content written in the editor appears above the generated sections.

## Settings

**Moghadam** in the dashboard sidebar. One tab today, **Variables**; more
sections will be added there over time.

### Variables

Every design token the theme uses, editable with a description of where each one
applies:

| Group | Per mode | Tokens |
| --- | --- | --- |
| Colors | Yes | Accent, Text, Muted, Background, Surface, Border |
| Typography | No | Body, heading and monospace fonts, base size, line height |
| Spacing and sizing | No | Five spacing steps, corner radius, content and container widths |

Colors are defined twice, once per mode. Both sets are written to every page and
which one applies is decided at runtime:

```css
:root { /* light */ }

@media (prefers-color-scheme: dark) {
	:root:not([data-theme="light"]) { /* dark */ }
}

:root[data-theme="dark"] { /* dark */ }
```

So dark mode already follows the visitor's operating system. The control that
lets them override it arrives in 1.3.0, and this structure means the override
will win in both directions.

### One source of truth

The token schema in `inc/variables.php` is read by the settings screen, by the
generated front-end CSS, and by the block editor palette. There is nowhere else
to set a colour:

- `assets/css/main.css` declares the tokens on `:root` as fallbacks and never
  hard-codes a value.
- `theme.json` supplies editor defaults, overlaid at runtime from the stored
  settings through the `wp_theme_json_data_theme` filter (WordPress 6.1+).
- The Customizer sets no colours at all.

Values are validated on save. Colours must be valid hex, sizes must be a CSS
length or a `calc()`-style expression, and free text is stripped of anything that
could break out of a declaration or load an external resource. Anything that
fails validation falls back to its default.

### Extending the settings screen

```php
add_filter( 'moghadam_settings_tabs', function ( $tabs ) {
	$tabs['layout'] = array(
		'label'    => 'Layout',
		'callback' => 'my_render_layout_tab',
	);
	return $tabs;
} );
```

## Customization

### Child themes

The theme is child-theme friendly. Create a directory next to it containing a `style.css` with:

```css
/*
Theme Name: Moghadam Child
Template: moghadam
*/
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## License

Moghadam is free software, released under the GNU General Public License v3 or later. See [LICENSE](LICENSE) for the full text.
