---
title: 'Plain Text Version'
description: 'Exporting all reader content as a structured plain text file for portable, open access.'
---

## Overview

Helios Open Reader can publish your content as a structured plain-text file — a single URL that always reflects current content, readable by people and tools alike. This is useful for:

- **Open access** — share one stable link to all reader content in a portable, format-neutral form
- **Ebook generation** — use `/llms-full.txt` as input for tools like [Pandoc](https://pandoc.org/) to produce EPUB, DOCX, or PDF output (note: shortcode syntax will appear as literal text)
- **Search and indexing** — machine-readable content that any tool can consume without parsing HTML
- **Compatible with AI tools** — the plain text format can be read by AI tools if instructors or students choose to use them

When enabled, two endpoints are served from your site root:

| Endpoint | Contents |
|----------|----------|
| `/llms.txt` | Site title, description, and a linked index of all included pages with their descriptions |
| `/llms-full.txt` | Everything in `/llms.txt` plus the raw Markdown content of each page |


## Enabling the Feature

Go to **Admin → Plugins → Helios Open Reader → Plain Text Version** and toggle **Enable Plain Text Version** on.

Once enabled, both endpoints are immediately available at your site's root URL.

> [!NOTE]
> The feature is disabled by default. No endpoints are served until it is turned on.

## Footer Link

When the plain text version is enabled, a **Plain text version** link can optionally appear in the page footer linking visitors to `/llms-full.txt`. Use **Show Plain Text Version Link in Footer** to show or hide it, and **Plain Text Version Link Label** to customise the link text.

## Controlling Which Pages Appear

The **Include Page Templates** setting controls which page types are included in the output. The default is `section-page`, which matches the standard Open Reader content pages.

To include other page types, add their template names (e.g. `default`, `default-toc`) to the list in the Admin panel.

> [!TIP]
> The raw Markdown in `/llms-full.txt` includes shortcode syntax (e.g. `[definition]…[/definition]`), which will appear as literal text in Pandoc output and most other tools.
