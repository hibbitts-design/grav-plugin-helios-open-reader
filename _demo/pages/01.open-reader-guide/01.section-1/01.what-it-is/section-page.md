---
title: 'What It Is'
description: 'An overview of Helios Open Reader features: sections structure, callout blocks, and navigation.'
---

Helios Open Reader provides a ready-built open textbook or reader site using portable Markdown files you fully control. Highlights include a configurable sections structure, a full set of callout blocks, Save My Place navigation, and optional Git Sync for open collaborative authoring.

## Reader Structure

- **Sections structure** – top-level folders named `section-N` are auto-detected as sections and render as section cards on the reader home
- **Section N header** – section pages automatically display their section number in the page header; inherits correctly for all sub-pages within a section. The label is configurable (e.g. Chapter, Project, Unit, Module) via **Admin → Pages → Reader Home → Section Label**
- **Section sub-pages** – sections can contain any number of sub-pages, all shown in the sidebar and navigable with Prev/Next controls
- Reader home page with cover image, title, subtitle, authors, edition, and CC license badge

## Callout Blocks

- **Learning Objectives** – [raw]`[objectives]...[/objectives]`[/raw] (green); also available as frontmatter (`learning_objectives:`) for automatic rendering at the top of a section page
- **Key Takeaways** – [raw]`[key-takeaways]...[/key-takeaways]`[/raw] (blue)
- **Example** – [raw]`[example]...[/example]`[/raw] (purple)
- **Exercise** – [raw]`[exercise]...[/exercise]`[/raw] (amber)
- **Definition** – [raw]`[definition]...[/definition]`[/raw] (blue)
- **Reflection** – [raw]`[reflection]...[/reflection]`[/raw] (green)
- **Case Study** – [raw]`[case-study]...[/case-study]`[/raw] (red)
- **Announcement** – [raw]`[announcement]...[/announcement]`[/raw] (purple by default; configurable type)
- All callouts accept an optional `title="..."` parameter and support Markdown content
- Five built-in GitHub-style callouts: `> [!NOTE]`, `> [!TIP]`, `> [!IMPORTANT]`, `> [!WARNING]`, `> [!CAUTION]`

## Navigation & Reading Experience

- **Save My Place** – records the last section page visited in localStorage; a dismissable "Continue reading" strip appears on the reader home page on return
- **Reading progress indicator** – shows current page position (e.g. Page 4 of 22) with an accessible progress bar above the Prev/Next navigation on section pages
- **Prev/Next navigation** – configurable position: top, bottom, or both
- **TOC scroll spy** – active heading highlighted in the Table of Contents as the reader scrolls
- **Start Reading button** – on the reader home; links directly to the first section
- Search across the full reader via the simplesearch plugin

[key-takeaways]
- Content lives in portable Markdown files you own and control
- Sections structure is auto-detected from folder naming – no configuration needed
- Callout blocks, Save My Place, and reading progress are all built in
- The reader works standalone or embedded in any LMS
[/key-takeaways]
