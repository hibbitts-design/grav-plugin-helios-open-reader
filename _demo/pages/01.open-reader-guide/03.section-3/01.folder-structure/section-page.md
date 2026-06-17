---
title: 'Folder Structure'
description: 'How reader content is organized, and how to show, hide, and add sections.'
---

All reader content lives within `user/pages/`. The skeleton ships with a reader home page and pre-configured demo sections.

```
user/pages/
├── 00.sections/              # Reader home page
│   └── section-list.md           # Reader title, subtitle, authors, edition, license, cover image
├── 01.section-1/           # Section 1 (published by default)
│   ├── section-page.md     # Section settings (section_number, description, icon, learning_objectives)
│   ├── 01.section-one/     # Sub-page (also uses section-page.md)
│   └── 02.section-two/     # Sub-page (also uses section-page.md)
├── 02.section-2/
├── 03.section-3/
└── readme/
```

Rename section folders to match your content, either in the Admin Panel or via FTP. The number prefix on each folder (e.g. `01.section-1/`) controls the order in the sidebar navigation.

> [!TIP]
> After adding, renaming, or removing a section folder, update `versioning.labels` in `user/config/themes/helios.yaml` (or via **Admin → Themes → Helios → Versioning → Version Labels**) to add the new folder name as a key – this sets the section name shown in the sidebar and browser tab title.

## Grouping Sections into Parts

To group sections into parts on the reader home page, use the `part-N-section-M` folder naming pattern instead of `section-N`:

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

To use custom titles for individual parts instead of the auto-generated "Part 1", "Part 2" labels, add a `parts` block to the `section-list.md` frontmatter:

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
