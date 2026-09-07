# Conventions

## PHP

WordPress Coding Standards throughout.

- Tabs for indentation. Spaces inside parentheses: `foo( $bar )`.
- `defined( 'ABSPATH' ) || exit;` at the top of every PHP file.
- Every function prefixed `moghadam_`; every constant prefixed `MOGHADAM_`.
- Every user-facing string wrapped with the `moghadam` text domain.
- Escape on output, always: `esc_html()`, `esc_attr()`, `esc_url()`. Where
  already-safe HTML is echoed, annotate the exception rather than leaving it
  unexplained:
  ```php
  echo $time_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
  ```
- Pluggable template tags are wrapped in `if ( ! function_exists( ... ) )` so a
  child theme can replace them.
- No closing `?>` at the end of a pure-PHP file.
- Docblock on every file and every function, with `@since` on new public API.

## CSS

- All design values are custom properties on `:root`, prefixed `--moghadam-`.
  No hard-coded colour, font stack or spacing value anywhere else.
- Logical properties for anything direction-sensitive: `margin-inline`,
  `inset-inline-start`, `padding-block`, `text-align: start`. `rtl.css` is a
  last resort, not the first tool.
- `main.css` opens with a numbered table of contents; keep it accurate when
  adding or renumbering a section.
- BEM-ish modifiers for layout variants: `.site-main--full-width`.
- Mobile styles live in the single `max-width: 782px` block at the end,
  matching the WordPress admin breakpoint.

## JavaScript

- No dependencies, no build step, no bundler.
- IIFE with `'use strict';`.
- Enqueued in the footer with `MOGHADAM_VERSION` for cache busting.
- Inline scripts are avoided. The one planned exception is the theme-mode
  script in phase 3, which must run before first paint to prevent a colour
  flash. That exception is recorded in
  [D-006](02-decisions.md#d-006-dark-and-light-modes--automatic-with-a-toggle).

## Internationalisation

- Text domain `moghadam` on every string, no exceptions.
- `languages/moghadam.pot` is regenerated whenever strings change.
- `_n()` for plurals; `/* translators: */` comments above any string with
  placeholders.
- Strings are English. See [D-004](02-decisions.md#d-004-repository-language).

## Git

### Branches

| Branch | Contains |
| --- | --- |
| `main` | The shippable theme. Nothing else. |
| `docs` | This documentation. Orphan branch, no shared history. |

The two never merge. Only `CHANGELOG.md` and the version number are kept in
sync between them.

### Commits

- Author is always `Sayid Moghadam <i@moghadam.pro>`.
- No co-author trailers, no tool attribution, no generated-by markers.
- Subject line in the imperative, under ~72 characters.
- Release commits are prefixed with the version: `v1.1.0 - …`.

### Releases

At the end of every phase, in order:

1. Bump the version in **both** `style.css` (`Version:`) and `functions.php`
   (`MOGHADAM_VERSION`). They must never disagree.
2. Add a `CHANGELOG.md` entry under a new version heading, using the
   Keep a Changelog categories (Added, Changed, Fixed, Removed).
3. Regenerate `languages/moghadam.pot` if any string changed.
4. Update the theme `README.md` if public behaviour changed.
5. Commit, tag annotated as `vX.Y.Z`, push `main` with `--follow-tags`.
6. Mirror `CHANGELOG.md` and the version to the `docs` branch, add the session
   record, update the roadmap status, and push.

Versioning is [semantic](https://semver.org/): a phase that adds capability
without breaking anything is a minor bump.

## Working method

- One phase at a time. Nothing starts before the previous phase is pushed.
- Decisions that change the architecture are agreed before implementation and
  recorded in the [decision log](02-decisions.md).
- Every session is recorded in [`sessions/`](../sessions/), including the
  questions asked and the answers given.
