---
title: 'Git Sync & Open Editing'
description: 'Keeping reader content automatically in sync with a GitHub or Codeberg repository.'
---

The skeleton includes the [Git Sync plugin](https://github.com/trilbymedia/grav-plugin-git-sync), which keeps your site content automatically in sync with a GitHub or Codeberg repository. This enables a full open-authoring workflow:

- Content editors can work directly in the Grav Admin or commit changes via Git
- The Helios Theme's **"Edit this Page"** option defaults to a 'View Page Markdown' link on each page, taking readers directly to the Markdown source file in your repository (configurable to link directly to file editing via the Helios Open Reader plugin settings)

If you prefer not to write Markdown directly, the optional [Grav Premium Editor Pro](https://getgrav.org/premium/editor-pro) provides a visual block editor for editing pages.

> [!TIP]
> The **Git Link Mode** setting in the Helios Open Reader plugin controls whether the footer link opens the file for **viewing** (default, for open access) or **editing** (for contributors with repository access). View mode is the safer default for public readers – GitHub's edit mode also handles unauthenticated visitors gracefully via a fork-and-propose flow.
