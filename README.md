# Moghadam

A clean, lightweight and standards-compliant WordPress starter theme, built for [moghadam.pro](https://moghadam.pro/).

No frameworks, no build step, no bloat — just semantic HTML5, accessible markup and modern CSS.

| | |
|---|---|
| **Version** | 1.0.0 |
| **Requires WordPress** | 6.0+ |
| **Requires PHP** | 7.4+ |
| **License** | [GPL v3 or later](https://www.gnu.org/licenses/gpl-3.0.html) |
| **Text Domain** | `moghadam` |

## Features

- Semantic HTML5 templates covering the full WordPress template hierarchy
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
│   ├── template-tags.php     # Custom template tags
│   └── customizer.php        # Customizer settings
├── template-parts/content/   # Reusable content templates
├── languages/moghadam.pot    # Translation template
├── 404.php
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

## Translation

The theme is fully internationalized under the `moghadam` text domain. `languages/moghadam.pot` is the template — copy it to `fa_IR.po`, translate, compile to `fa_IR.mo` and drop both in `languages/`.

## License

Moghadam is free software, released under the GNU General Public License v3 or later. See [LICENSE](LICENSE) for the full text.
