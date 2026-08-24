# Decision log

Every decision that shapes the theme, with its reasoning and the alternatives
that were rejected. Newest decisions are appended at the bottom.

---

## D-001: Clean slate

**Date:** 2026-08-24 · **Status:** Done · **Version:** 1.0.0

The repository previously held an unrelated Vite/React project, with
`node_modules/` committed (11,138 tracked files). All of it was removed and
replaced with a WordPress theme.

**Note:** the old tree remains in Git history. Only the working tree was
emptied; history was not rewritten, so nothing was lost.

---

## D-002: `Theme URI`, not `Theme URL`

**Date:** 2026-08-24 · **Status:** Done · **Version:** 1.0.0

The supplied style.css header block used `Theme URL:`. WordPress only recognises
`Theme URI:`; with `URL` the theme link never renders in the dashboard. Changed
to `Theme URI` and flagged to the owner.

---

## D-003: Author identity

**Date:** 2026-08-24 · **Status:** Standing rule

All commits are authored as `Sayid Moghadam <i@moghadam.pro>`. No co-author
trailers, no tool attribution, no generated-by markers anywhere in the
repository or its history.

---

## D-004: Repository language

**Date:** 2026-08-24 · **Status:** Standing rule

Nothing in the repository is written in Persian. Code comments, documentation,
commit messages and user-facing theme strings are English.

The i18n infrastructure stays in place regardless: `load_theme_textdomain()`,
the `moghadam` text domain on every string, and `languages/moghadam.pot`. So
does `rtl.css`, which only loads when `is_rtl()` is true. These are standard
WordPress practice and not Persian-specific.

**Rejected:** removing the `.pot` file and RTL stylesheet. Explicitly kept at
the owner's instruction.

---

## D-005: Portfolio deferred to a plugin

**Date:** 2026-08-24 · **Status:** Deferred

The originally planned `Single Portfolio` and `Archive Portfolio` templates need
a `portfolio` custom post type. Three options were weighed:

| Option | Trade-off |
| --- | --- |
| Register the CPT in the theme | Simplest, single repository. But switching themes hides the content. |
| Companion plugin in the same repository | Correct per WordPress practice — content outlives the theme — at the cost of two deliverables. |
| Rely on CPT UI / ACF | Lightest theme, but the content model lives in dashboard settings rather than in version control. |

**Decision:** neither for now. Portfolio is dropped from the current scope
entirely; a dedicated plugin will be written for it later. Both portfolio
templates were removed from the phase plan.

---

## D-006: Dark and light modes — automatic with a toggle

**Date:** 2026-08-24 · **Status:** Planned for phase 3

The theme follows the visitor's operating system preference through
`prefers-color-scheme`, and additionally offers a control to override it. The
choice persists in `localStorage`.

**Implication:** a small inline script must run in `<head>`, before first paint,
to apply the stored choice. Without it the page paints in the system theme and
then flips — a visible flash. This is the one place the theme accepts inline
JavaScript, and the reason is recorded here so it is not "cleaned up" later.

**Rejected:** system preference only (no visitor control); a fixed mode chosen
by the site owner; owner-set default with a toggle.

---

## D-007: The "Theme" template is a style guide

**Date:** 2026-08-24 · **Status:** Done in phase 1, extended in phase 4

The `Theme` page template renders every design token — colours, typography,
spacing, sizing — beside live examples of each styled element, using the values
currently in effect. It is the page the owner opens after changing a setting to
see the result of the change in one place.

It pairs directly with the **Variables** settings section: phase 4 will feed the
style guide from the stored settings rather than from a static definition, so it
can never drift from reality.

**Rejected:** treating `Theme` as a second full-width template; treating it as a
marketing landing page for the theme itself.

---

## D-008: Canvas keeps SEO, drops presentation

**Date:** 2026-08-24 · **Status:** Done · **Version:** 1.1.0

The Canvas template renders hand-written HTML, CSS and JS with no influence from
the theme. Drawing that line precisely:

**Removed** — the theme's own stylesheets (`moghadam-style`, `moghadam-main`,
`moghadam-rtl`), the theme's scripts, and `global-styles` plus
`classic-theme-styles`, both of which are generated from the theme's
`theme.json`. Also the site header, footer and sidebar; `wpautop` and
`shortcode_unautop`, so markup is not reformatted; and the admin bar, which
otherwise injects CSS and pushes the document down by 32 pixels.

**Kept** — `wp_head()` and `wp_footer()` in full. Every SEO signal therefore
behaves identically to any other page: title tag, canonical URL, meta
description, Open Graph and Twitter cards, JSON-LD schema, and any other plugin
output. Plugin assets are also left alone, since a plugin may be exactly what
the page depends on.

The `moghadam_canvas_dequeue_handles` filter exists so the line can be moved
without editing the theme.

**Rejected:** stripping `wp_head()` for a truly empty document — that would
break SEO, which the owner named as a requirement.

---

## D-009: Documentation on a separate orphan branch

**Date:** 2026-08-24 · **Status:** Standing rule

Documentation lives on the `docs` branch, created with `--orphan` so it shares
no commit history with `main`. `main` holds the shippable theme and nothing
else — no documentation directory, no session logs.

Documentation is written in English, matching [D-004](#d-004-repository-language).

`CHANGELOG.md` and the version number are the two things kept in sync across
both branches.

**Rejected:** a Persian docs branch; a bilingual branch; naming the branch
`documentation`.

---

## D-010: Version and changelog per phase

**Date:** 2026-08-24 · **Status:** Standing rule

Each completed phase gets a minor version bump, a `CHANGELOG.md` entry, an
annotated Git tag, and a push to both branches. See
[conventions](05-conventions.md#releases).
