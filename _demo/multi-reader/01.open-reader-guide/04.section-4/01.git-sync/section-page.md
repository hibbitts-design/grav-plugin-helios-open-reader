---
title: 'Git Sync & Open Editing'
---

The skeleton includes the [Git Sync plugin](https://github.com/trilbymedia/grav-plugin-git-sync), which keeps your site content automatically in sync with a GitHub or Codeberg repository. This enables a full open-authoring workflow:

- Content editors can work directly in the Grav Admin or commit changes via Git
- The Helios theme's **Edit this page** link on each page takes readers directly to the page's Markdown source file in your repository (it opens the file for viewing by default; set Git Link Mode in the Helios Open Reader plugin settings to link straight to editing)

If you prefer not to write Markdown directly, the optional [Grav Premium Editor Pro](https://getgrav.org/premium/editor-pro) provides a visual block editor for editing pages.

> [!TIP]
> The **Git Link Mode** setting in the Helios Open Reader plugin controls whether the footer link opens the file for **viewing** (default, for open access) or **editing** (for contributors with repository access). View mode is the safer default for public readers – GitHub's edit mode also handles unauthenticated visitors gracefully via a fork-and-propose flow.
