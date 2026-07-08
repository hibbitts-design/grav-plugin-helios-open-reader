---
title: 'Folder Structure'
---

All reader content lives within `user/pages/`. This skeleton ships pre-configured in **multi-publication mode**, with two demo publications — this guide is Publication 1:

```
user/pages/
├── 00.readers/                    # Readers home
│   └── reader-list.md                 # Readers list title, subtitle, shared settings
├── 01.open-reader-guide/          # Publication 1 (this guide)
│   ├── section-list.md            # Publication home: title, authors, edition, license, cover image
│   ├── 01.section-1/              # Section 1 (published by default)
│   │   ├── section.md             # Section settings (section_number, description, icon, learning_objectives)
│   │   ├── 01.what-it-is/         # Sub-page (uses section-page.md)
│   │   └── 02.when-to-use/        # Sub-page (uses section-page.md)
│   ├── 02.section-2/
│   └── ...
├── 02.open-education-essentials/  # Publication 2
│   ├── section-list.md
│   └── ...
└── readme/
```

The readers home auto-detects all publication folders at root level and displays them as cards. Rename section folders to match your content, either in the Admin Panel or via FTP. The number prefix on each folder (e.g. `01.section-1/`) controls the order in the sidebar navigation.

Section names in the sidebar and browser tab title are drawn from each section's `title` field — no `versioning.labels` configuration is needed in multi-publication mode.

> [!TIP]
> If you only need a single publication, you can convert to a simpler single-publication mode: remove `00.readers/`, move the publication folder's contents to root level, and point `home.alias` in `user/config/system.yaml` to your reader home. In single-publication mode, add a `versioning.labels` entry in `user/config/themes/helios.yaml` for each section folder — this sets the section name shown in the sidebar and browser tab title.

## Grouping Sections into Parts

To group sections into parts, use the `part-N-section-M` folder naming pattern instead of `section-N` within a publication folder (or at root level in single-publication mode):

```
01.open-reader-guide/
├── section-list.md
├── 01.part-1-section-1/    # Part 1, Section 1
├── 02.part-1-section-2/    # Part 1, Section 2
├── 03.part-2-section-1/    # Part 2, Section 1
├── 04.part-2-section-2/    # Part 2, Section 2
└── ...
```

Parts are detected automatically — no additional configuration required. Part headings ("Part 1", "Part 2") appear above each group of section cards on the reader home page, Prev/Next navigation stops at part boundaries, and the reading progress indicator counts pages within the current part only.

In single-publication mode, update `versioning.labels` in `user/config/themes/helios.yaml` to use the new folder names as keys:

```yaml
versioning:
  labels:
    part-1-section-1: 'Introduction'
    part-1-section-2: 'Core Concepts'
    part-2-section-1: 'Advanced Topics'
    part-2-section-2: 'Publishing & Sharing'
```

> [!TIP]
> This isn't needed in multi-publication mode, where section names come from each page's `title` field. The `version_pattern` in `user/config/themes/helios.yaml` detects both `section-N` and `part-N-section-M` folder names automatically — no change to the pattern is needed when switching to parts.

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

To add a section, copy an existing section folder (e.g. `01.section-1/`) into the same publication's folder via FTP or the Admin panel (when using the Admin panel, open the section page, click the copy icon, then update the **Page Title** field to a valid new section ID such as `section-4`). Ensure the folder name follows the `section-N` convention, then set **Published** to **Yes** in the Admin panel to make it visible.

> [!TIP]
> In single-publication mode, also add the new folder name as a key in `versioning.labels` in `user/config/themes/helios.yaml` (or via **Admin → Themes → Helios → Versioning → Version Labels**) — this isn't needed in multi-publication mode, where section names come from each page's `title` field. After duplicating and renaming a section folder, clear the Grav cache via the **Clear Cache** button in the Admin panel if the new section does not appear immediately.
