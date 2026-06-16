---
title: 'Folder Structure'
description: 'How reader content is organized, and how to show, hide, and add sections.'
---

All reader content lives within `user/pages/`. The skeleton ships pre-configured as a single-publication reader with demo sections.

```
user/pages/
├── 00.sections/              # Reader home page
│   └── section-list.md       # Reader title, subtitle, authors, edition, license, cover image
├── 01.section-1/             # Section 1
│   ├── section-page.md       # Section settings (section_number, description, icon, learning_objectives)
│   ├── 01.page-one/          # Sub-page (also uses section-page.md)
│   └── 02.page-two/
├── 02.section-2/
├── 03.section-3/
└── readme/
```

Rename section folders to match your content, either in the Admin Panel or via FTP. The number prefix on each folder (e.g. `01.section-1/`) controls the order in the sidebar navigation.

> [!TIP]
> After adding, renaming, or removing a section folder, update `versioning.labels` in `user/config/themes/helios.yaml` (or via **Admin → Themes → Helios → Versioning → Version Labels**) to add the new folder name as a key – this sets the section name shown in the sidebar and browser tab title.

## Switching to Multi-Publication Mode

To host multiple publications within a single Helios Open Reader site, switch to multi-publication mode. A `publication-list.md` page serves as the publications home; each publication has its own `publication-home.md` and its section folders nested inside it:

```
user/pages/
├── 00.publications/                # Publications home page
│   └── publication-list.md        # Publications list title, description, layout settings
├── 01.getting-started/             # Publication 1
│   ├── publication-home.md        # Publication title, subtitle, authors, edition, license, cover image
│   ├── 01.section-1/              # Section 1
│   │   ├── section-page.md
│   │   ├── 01.page-one/
│   │   └── 02.page-two/
│   └── 02.section-2/
├── 02.working-with-reader/         # Publication 2
│   ├── publication-home.md
│   └── ...
└── readme/
```

To switch to multi-publication mode:

1. Create a `00.publications/` folder with a `publication-list.md` file inside
2. Create numbered publication folders at root level (e.g. `01.my-publication/`), each with a `publication-home.md`
3. Nest your section folders inside each publication folder
4. Update `home.alias` in `user/config/system.yaml` to `/publications`

In multi-publication mode, section names in the sidebar are drawn from each section page's `title` field — no `versioning.labels` configuration is needed.

## Grouping Sections into Parts

To group sections into parts on the reader home page (or within a publication in multi-publication mode), use the `part-N-section-M` folder naming pattern instead of `section-N`:

```
user/pages/
├── 00.sections/
├── 01.part-1-section-1/    # Part 1, Section 1
├── 02.part-1-section-2/    # Part 1, Section 2
├── 03.part-2-section-1/    # Part 2, Section 1
├── 04.part-2-section-2/    # Part 2, Section 2
└── readme/
```

Parts are detected automatically — no additional configuration required. Part headings ("Part 1", "Part 2") appear above each group of section cards on the reader home page, Prev/Next navigation stops at part boundaries, and the reading progress indicator counts pages within the current part only.

Update `versioning.labels` in `user/config/themes/helios.yaml` to use the new folder names as keys:

```yaml
versioning:
  labels:
    part-1-section-1: 'Introduction'
    part-1-section-2: 'Core Concepts'
    part-2-section-1: 'Advanced Topics'
    part-2-section-2: 'Publishing & Sharing'
```

> [!TIP]
> The `version_pattern` in `user/config/themes/helios.yaml` detects both `section-N` and `part-N-section-M` folder names automatically — no change to the pattern is needed when switching to parts.

To use custom titles for individual parts instead of the auto-generated "Part 1", "Part 2" labels, add a `parts` block to `section-list.md` (or `publication-home.md` in multi-publication mode):

```yaml
parts:
  - id: part-1
    label: 'Foundations of Open Education'
  - id: part-2
    label: 'Applying Open Practices'
```

## Showing and Hiding Sections

In the Admin panel, open the section folder and set **Published** to **Yes** to show or **No** to hide it. Unpublished sections are also excluded from search results and the sidebar.

Once you have set up your own content, you can safely delete any unused demo sections from `user/pages/` via the Admin panel or FTP.

> [!TIP]
> If changes don't appear immediately after publishing pages or updating settings, clear the Grav cache via the **Clear Cache** button in the Admin panel.

## Adding a New Section

To add a section, copy an existing section folder (e.g. `01.section-1/`) via FTP or the Admin panel (when using the Admin panel, open the section page, click the copy icon, then update the **Page Title** field to a valid new section ID such as `section-4`). Ensure the folder name follows the `section-N` convention, then add the new folder name as a key in `versioning.labels` in `user/config/themes/helios.yaml` (or via **Admin → Themes → Helios → Versioning → Version Labels**). Finally, set **Published** to **Yes** in the Admin panel to make it visible.

> [!TIP]
> After duplicating and renaming a section folder, clear the Grav cache via the **Clear Cache** button in the Admin panel if the new section does not appear immediately.
