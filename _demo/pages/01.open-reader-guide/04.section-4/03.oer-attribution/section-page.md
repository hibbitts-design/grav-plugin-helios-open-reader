---
title: 'OER Attribution & Print'
description: 'Displaying a CC license attribution block in the reader footer and producing a clean print output.'
---

## OER Attribution

[definition title="OER Attribution"]
A statement that identifies the author, title, and license of an openly licensed work. Creative Commons recommends the **TASL** format[^1]: Title, Author, Source (URL), License. Helios Open Reader displays this in the footer when the OER Attribution Block is enabled.
[/definition]

The OER attribution block displays a CC license statement in the footer of every page, drawn from the reader home page frontmatter. Enable it via **Admin → Pages → Reader Home → OER Attribution tab → Show OER Attribution Block**.

The relevant frontmatter fields in `section-list.md`:

| Field | Example |
|-------|---------|
| `license` | `CC BY 4.0` |
| `license_url` | `https://creativecommons.org/licenses/by/4.0/` |
| `attribution_text` | `This work by Jane Smith is licensed under CC BY 4.0.` |

The `attribution_text` field supports basic HTML for links and formatting.[^2]

## Print Output

The plugin includes a print stylesheet (`print.css`) with:

- Navigation chrome hidden (sidebar, header, prev/next)
- Colors reset for both light and dark themes
- Page breaks controlled at heading boundaries
- Absolute link URLs displayed inline after each link
- Consistent page margins across browsers

Use your browser's **Print** function (or **Save as PDF**) to produce a clean, readable PDF of any reader page.

> [!TIP]
> For individual sections, print each section page or sub-page separately. There is no single-click "export all" function, but browser print-to-PDF works well for individual pages.

[^1]: For full attribution guidelines, see the Creative Commons wiki: https://wiki.creativecommons.org/wiki/Best_practices_for_attribution
[^2]: Supported tags include `<a>`, `<em>`, `<strong>`, and `<br>`.
