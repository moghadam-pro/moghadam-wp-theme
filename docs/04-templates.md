# Templates

## Page templates

Selected per page from **Page → Template** in the block editor sidebar. All
files live in `page-templates/`, which WordPress scans because
`WP_Theme::get_post_templates()` looks one directory level deep.

Each file declares itself with two headers:

```php
/**
 * Template Name: Full Width
 * Template Post Type: page
 */
```

| Template | File | Header / footer | Content width | Sidebar |
| --- | --- | --- | --- | --- |
| Default Template | `page.php` | Yes | Content (800px) | Yes |
| Full Width | `template-full-width.php` | Yes | Container (1100px) | No |
| Home Page | `template-home.php` | Yes | Container, full-bleed capable | No |
| Theme | `template-styleguide.php` | Yes | Container | No |
| Canvas | `template-canvas.php` | No | Unstyled | No |

### Default Template

`page.php`. Not a named template — it is what WordPress uses when nothing is
selected. Content is capped at the reading measure (`--moghadam-content`,
800px) and the sidebar renders if it has widgets.

### Full Width

Header and footer, no sidebar, content at the full container width. `.alignwide`
and `.alignfull` blocks still escape the cap, and full-bleed blocks break out of
the container padding.

### Home Page

The landing layout. Content comes from the editor exactly like any other page;
the template adds only structure and two action hooks:

```php
do_action( 'moghadam_home_before_content' );
do_action( 'moghadam_home_after_content' );
```

Home-specific sections can be attached to those hooks later without touching
the page content.

### Theme (style guide)

Renders every design token next to live examples of each styled element, using
the values in effect at the time. Sections: colours (swatch, token name, where
it is used), typography (token table plus a rendered type scale), spacing and
sizing (proportional bars), and elements (buttons, forms, blockquote, lists,
code block, table).

Content written in the editor appears above the generated sections, so notes can
be kept on the same page.

### Canvas

For pages whose HTML, CSS and JS are written by hand and must render exactly as
authored — a one-off landing page, an experiment, an embedded application.

**The theme contributes nothing.** No stylesheets, no scripts, no `theme.json`
global styles, no header, footer or sidebar. The admin bar is suppressed because
it injects CSS and offsets the document. `wpautop` and `shortcode_unautop` are
removed, so paragraph tags are not inserted into hand-written markup.

**SEO is untouched.** `wp_head()` and `wp_footer()` run in full — title tag,
canonical URL, meta description, Open Graph, Twitter cards, JSON-LD schema, and
any plugin output all behave as on any other page. Plugin assets are left
enqueued, since a plugin may be what the page depends on.

Write the markup in a **Custom HTML** block, or switch the editor to the code
editor (`Ctrl` + `Shift` + `Alt` + `M`) and write the whole document body.

To change what gets stripped:

```php
add_filter( 'moghadam_canvas_dequeue_handles', function ( $handles ) {
	$handles['styles'][] = 'wp-block-library';
	return $handles;
} );
```

## Post templates

| View | File | Layout |
| --- | --- | --- |
| Single Post | `single.php` | Default, with post navigation and comments |
| Archive Post | `archive.php` | Default, with archive title, description and pagination |
| Blog index | `index.php` | Default |
| Search results | `search.php` | Default |
| 404 | `404.php` | Default |

Each renders through a partial in `template-parts/content/`, so markup is
defined once: `content.php`, `content-single.php`, `content-page.php`,
`content-search.php` and `content-none.php`.

## Adding a template

1. Create the file in `page-templates/` with `Template Name:` and
   `Template Post Type: page` headers.
2. Add a layout slug to `moghadam_get_layouts()` in `inc/layout.php` if the
   existing four do not fit.
3. Add the matching `.site-main--{slug}` rules to section 4a of `main.css`.
4. Document it here and in the theme's `README.md`.
