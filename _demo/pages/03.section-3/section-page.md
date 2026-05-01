---
title: 'Getting Started with Open Authoring'
section_number: '3'
icon: tabler/git-branch.svg
description: 'How to invite readers to suggest edits, view source, and contribute to your open course content via GitHub or Codeberg.'
learning_objectives: "- Set up Git Page Link to invite reader contributions\n- Distinguish between view mode and edit mode for different Git platforms\n- Describe the fork-and-propose workflow for unauthenticated GitHub visitors"
image: natalia-y-YqeS71-42c4-unsplash.jpg
sitemap:
    lastmod: '29-04-2026 14:32'
---

Open authoring closes the loop between publishing content openly and actively inviting others to improve it. The goal isn't just making content readable — it's making it editable.

## The Git Page Link Plugin

The [Git Page Link plugin](https://github.com/hibbitts-design/grav-plugin-git-page-link) adds a link to each Grav page connecting visitors directly to the source Markdown file in your Git repository.

A single link serves two audiences:

- **Contributors with repository access** land on the file editor
- **Everyone else** sees the file in the repository viewer, where they can fork and propose changes

## View Mode vs. Edit Mode

| Platform | View mode | Edit mode |
| --- | --- | --- |
| GitHub | Shows file to everyone | Redirects unauthenticated users to fork-and-propose flow |
| Codeberg | Shows file to everyone | Redirects unauthenticated users to login |
| GitLab | Shows file to everyone | Redirects unauthenticated users to login |

For publicly accessible readers, **view mode** is the safer default on all platforms. GitHub's edit mode is the exception — it handles unauthenticated visitors gracefully, making it well-suited for open authoring workflows.

## Inviting Contributions

The simplest invitation is a single sentence at the top of your reader home page:

> Found an error or want to suggest an improvement? Use the link at the bottom of any page to view the source and propose a change.

Readers who are comfortable with GitHub can open a pull request. Those who aren't can email you the suggested change. Either way, the source file is visible and accessible.