---
title: 'Page Settings'
description: 'Frontmatter fields for the reader home page and section pages.'
---

## Reader Home Settings

The `section-list.md` frontmatter controls the reader identity and card layout on the home page. These fields can be set in the Admin Panel by opening the reader home page.

| Field | Description |
|-------|-------------|
| `title` | Reader title displayed in the header |
| `subtitle` | Optional subtitle shown below the title in italics |
| `authors` | Author name(s) shown below the subtitle |
| `edition` | Optional edition line (e.g. `First Edition, 2025`) |
| `license` | CC license label shown as a badge (e.g. `CC BY 4.0`) |
| `license_url` | URL for the license badge link |
| `attribution_text` | Full attribution statement shown in the footer when OER attribution is enabled |
| `cover_image` | Filename of a cover image uploaded to the reader home media folder |
| `start_button_text` | Label for the button linking to the first section (e.g. `Start Reading`, `Browse Projects`, `View Guides`). Leave empty to hide. |
| `prev_next_position` | Where to display Prev/Next navigation on section pages: `both` (default), `top`, or `bottom` |
| `show_oer_attribution` | Display the CC license and attribution text in the footer of every page (`true` or `false`) |
| `section_label` | Label used for sections throughout the reader (e.g. `Chapter`, `Unit`, `Module`). Leave empty to use the language default. |
| `part_label` | Label used for part headings on the reader home page when using the `part-N-section-M` folder naming pattern (e.g. `Theme`, `Project`). Leave empty to use the default (`Part`). |
| `parts` | Optional list of custom part titles — see the Folder Structure guide page |
| `cards_per_row` | Number of section cards per row (1–3) |
| `card_icon` | Default icon for all cards (Tabler icon path) |
| `card_image_layout` | Card image position: `side` or `top` |
| `card_description_lines` | Maximum description lines per card (2, 3, or 0 for no limit) |

Page content written in `section-list.md` appears above the cards by default. To also display content **below** the cards, add `===` on its own line as a delimiter:

```markdown
This text appears above the section cards.

===

This text appears below the section cards.
```

## Section Page Settings

The `section-page.md` frontmatter controls each section's landing page and card appearance.

| Field | Description |
|-------|-------------|
| `section_number` | Section number shown in the page header; inherits to all sub-pages within the section |
| `description` | Description shown on the section card |
| `icon` | Tabler icon path for the section card |
| `image` | Filename of a card image uploaded to this page's media folder |
| `author` | Author name(s) shown on the section card |
| `learning_objectives` | Markdown list rendered as a Learning Objectives block at the top of the page |
| `badge_label` | Optional status badge label (e.g. `New`, `Draft`) |
| `badge_color` | Optional badge colour (`blue`, `green`, `yellow`, `red`, `purple`, `plain`) |

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
