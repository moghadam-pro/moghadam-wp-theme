# Project brief

## Purpose

A bespoke WordPress theme for **moghadam.pro**, the personal site of Sayid
Moghadam (Senior Product Designer). The site introduces its owner and presents
his work.

## Core principle

> The content is authored in the standard WordPress block editor. The theme
> supplies structure and styling only.

This is the constraint every other decision is measured against. The theme does
not generate content, does not lock content into theme-specific shortcodes or
meta fields, and does not require a page builder. If the theme were removed
tomorrow, the content would still be intact and readable.

## Scope

**In scope**

- A set of page templates the author picks per page from the editor sidebar.
- A theme settings screen in the WordPress dashboard, growing section by
  section, starting with **Variables** (colours, typography, and where each
  token is used).
- Light and dark modes defined in those variables and adjustable from settings.
- Full SEO integrity on every template, including the unstyled Canvas template.

**Out of scope**

- A Portfolio custom post type. Deferred to a dedicated companion plugin, so
  the content survives independently of the theme. See
  [decision D-005](02-decisions.md#d-005-portfolio-deferred-to-a-plugin).
- Any Persian-language content in the repository. The theme is fully
  internationalised and RTL-capable, but ships English strings only. See
  [D-004](02-decisions.md#d-004-repository-language).
- Framework dependencies, build steps, and bundled JavaScript libraries.

## Non-negotiables

1. **No build step.** The theme runs from source. Editing a file is enough.
2. **No dependencies.** No framework, no CDN, no npm at runtime.
3. **WordPress Coding Standards.** Escaping on output, text domain on every
   user-facing string, `defined( 'ABSPATH' ) || exit;` at the top of every PHP
   file.
4. **Accessibility.** Skip link, screen reader text, visible focus, honoured
   reduced-motion preference.
5. **Careful and principled.** Changes are incremental, each phase is reviewed,
   versioned, changelogged and pushed before the next begins.
