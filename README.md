# Moghadam

A clean, lightweight and standards-compliant WordPress starter theme, built for [moghadam.pro](https://moghadam.pro/).

No frameworks, no build step, no bloat — just semantic HTML5, accessible markup and modern CSS.

| | |
|---|---|
| **Version** | 1.1.0 |
| **Requires WordPress** | 6.0+ |
| **Requires PHP** | 7.4+ |
| **License** | [GPL v3 or later](https://www.gnu.org/licenses/gpl-3.0.html) |
| **Text Domain** | `moghadam` |

## Features

- Semantic HTML5 templates covering the full WordPress template hierarchy
- Five page templates, including a Canvas template for hand-written markup
- Fully responsive layout with a mobile navigation toggle
- Right-to-left (RTL) ready — built on CSS logical properties
- Theming through CSS custom properties (colors, typography, spacing)
- `theme.json` support: color palette, font sizes, layout widths, wide/full alignment
- Customizer accent color with live preview
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
│   ├── js/navigation.js      # Mobile menu toggle
│   └── js/customizer.js      # Customizer live preview
├── inc/
│   ├── setup.php             # Theme supports, menus, widget areas
│   ├── enqueue.php           # Scripts and styles
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

## Customization

### Colors and typography

All design values are CSS custom properties defined at the top of `assets/css/main.css`:

```css
:root {
	--moghadam-color-accent: #2563eb;
	--moghadam-color-text: #1f2328;
	--moghadam-font-body: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
	--moghadam-container: 1100px;
	--moghadam-content: 800px;
}
```

Override them in a child theme, or change the accent color from **Appearance → Customize → Theme Colors**.

The block editor reads its palette, font sizes and layout widths from `theme.json` — keep the two in sync when you change values.

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
