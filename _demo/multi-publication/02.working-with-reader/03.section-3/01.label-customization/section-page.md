---
title: 'Labels & Browser Tab Title'
description: 'Customizing the reader title, section names, section label, and browser tab title format.'
---

## Reader Title

The reader title displayed in the browser tab and header comes from the `title` field in `section-list.md`. Edit it via **Admin → Pages → Reader Home**, or directly in `user/pages/00.sections/section-list.md`.

## Section Names

The individual section name shown in the sidebar, in the section dropdown (when enabled), and as the middle segment of the browser tab title (`Page Title | Reader Title | Site Title`) comes from the `versioning.labels` setting in the Helios Theme config. Edit via **Admin → Themes → Helios → Versioning tab → Version Labels**, or directly in `user/config/themes/helios.yaml`:

```yaml
versioning:
  labels:
    section-1: 'Introduction'
    section-2: 'Installation'
    section-3: 'Creating Content'
    section-4: 'Publishing & Sharing'
    section-5: 'Customization'
```

## Section Label

The section label used throughout the reader (in page headers, cards, and the sidebar) can be set via **Admin → Pages → Reader Home → Section Label**. Leave it empty to use the language default. For multi-language sites, the per-language default is set via `SECTION_LABEL` in `user/plugins/helios-open-reader/languages.yaml`. English and French are included:

```yaml
en:
  PLUGIN_HELIOS_OPEN_READER:
    SECTION_LABEL: Section
    SECTION_LATEST_LABEL: latest

fr:
  PLUGIN_HELIOS_OPEN_READER:
    SECTION_LABEL: Chapitre
    SECTION_LATEST_LABEL: dernière
```

To customize the label or add a language, update the relevant block in `user/plugins/helios-open-reader/languages.yaml`.

## Browser Tab Title

The browser tab title is automatically formatted as:

`Page Title | Reader Title | Site Title`

The Reader Title is drawn from the reader home page title. The Site Title comes from `site.title` in `user/config/site.yaml`. Set the Site Title to your institution or author name – it serves as the top-level identifier in the browser tab.
