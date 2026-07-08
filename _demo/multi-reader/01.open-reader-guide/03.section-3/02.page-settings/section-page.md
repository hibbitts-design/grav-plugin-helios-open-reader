---
title: 'Page Settings'
---

## Readers List Settings

In multi-publication mode (the skeleton default), the `reader-list.md` frontmatter in `00.readers/` controls the readers home page shown before any publication is opened. Global settings for section label, Prev/Next position, and OER attribution set here apply to all publications, and can be overridden per publication in its own `section-list.md`.

| Field | Description |
|-------|-------------|
| `title` | Publications list title displayed in the header |
| `subtitle` | Optional collection tagline displayed below the title |
| `prev_next_position` | Prev/Next position on section pages: `both` (default), `top`, or `bottom` |
| `show_oer_attribution` | Show CC license footer on all pages |
| `section_label` | Section label for all publications (e.g. `Chapter`). Overridable per publication. |
| `license` / `license_url` / `attribution_text` | CC license label, URL, and full attribution statement |
| `cards_per_row` | Publication cards per row (1–3); default is 2 |
| `card_icon` | Default icon for reader cards |
| `card_image_layout` | Card image position: `side` or `top` (default: `top`) |
| `card_description_lines` | Max description lines per card |

## Publication Home Settings

The `section-list.md` frontmatter controls the publication identity and card layout on its home page. In single-publication mode it is the reader home; in multi-publication mode it is the publication home inside each publication folder. These fields can be set in the Admin Panel by opening the relevant page.

| Field | Description |
|-------|-------------|
| `title` | Reader title displayed in the header |
| `subtitle` | Optional subtitle shown below the title in italics; used as the description on the publication card in the readers list |
| `authors` | Author name(s) shown below the subtitle; also shown on the publication card in the readers list |
| `edition` | Optional edition line (e.g. `First Edition, 2025`); also shown on the publication card in the readers list |
| `last_updated` | Optional date displayed on the publication card in the readers list. Set via the **Last Updated** field in the Admin panel. |
| `license` | CC license label shown as a badge (e.g. `CC BY 4.0`) |
| `license_url` | URL for the license badge link |
| `attribution_text` | Full attribution statement shown in the footer when OER attribution is enabled |
| `cover_image` | Filename of a cover image uploaded to the reader home media folder |
| `group` | Group label for organizing this publication under a heading on the readers list page (e.g. `Textbooks`, `Guides`). Publications without a group appear first. |
| `start_button_text` | Label for the button linking to the first section (e.g. `Start Reading`, `Browse Projects`, `View Guides`). Leave empty to hide. |
| `prev_next_position` | Where to display Prev/Next navigation on section pages: `both` (default), `top`, or `bottom` |
| `show_oer_attribution` | Display the CC license and attribution text in the footer of every page (`true` or `false`) |
| `section_label` | Label used for sections throughout the reader (e.g. `Chapter`, `Unit`, `Module`). Leave empty to use the language default. |
| `part_label` | Label used for part headings on the reader home page when using the `part-N-section-M` folder naming pattern (e.g. `Theme`, `Project`). Leave empty to use the default (`Part`). |
| `parts` | Optional list of custom part titles — see the Folder Structure guide page |
| `cards_per_row` | Number of section cards per row (1–3); default is 1 |
| `card_icon` | Default icon for all cards (Tabler icon path) |
| `card_image_layout` | Card image position: `side` or `top` (default: `side`) |
| `card_description_lines` | Maximum description lines per card (2, 3, or 0 for no limit) |

Page content written in `section-list.md` appears above the cards by default. To also display content **below** the cards, add `===` on its own line as a delimiter:

```markdown
This text appears above the section cards.

===

This text appears below the section cards.
```

## Reader Section Settings

The `section.md` frontmatter controls each section's landing page and card appearance.

| Field | Description |
|-------|-------------|
| `section_number` | Section number shown in the page header; inherits to all sub-pages within the section |
| `description` | Description shown on the section card |
| `icon` | Tabler icon path for the section card |
| `image` | Filename of a card image uploaded to this page's media folder |
| `author` | Author name(s) shown on the section card |
| `learning_objectives` | Markdown list rendered as a Learning Objectives block at the top of the section landing page |
| `badge_label` | Optional status badge label (e.g. `New`, `Draft`) |
| `badge_color` | Optional badge colour (`blue`, `green`, `yellow`, `red`, `purple`, `plain`) |

Individual content pages within a section (`section-page.md`) have no custom frontmatter — add content directly in the editor.

```yaml
---
title: What is Open Education?
section_number: 1
icon: tabler/school.svg
image: section-cover.jpg
author: Jane Smith
description: An introduction to open education principles and the 5Rs framework.
badge_label: New
badge_color: green
learning_objectives: |
  - Define open education and explain its core principles
  - Identify the 5Rs of open educational resources
---
```
