<div align="center">

# 📖 Grav Helios Open Reader Plugin

### Designed to accompany the Helios Open Reader Skeleton

<p><em>Publish open textbooks, course readers, student projects, and other OER on the web – with content in portable Markdown files you fully control.</em></p>

[![Grav Discord Chat](https://img.shields.io/discord/501836936584101899.svg?logo=discord&colorB=728ADA&label=Grav%20Discord%20Chat)](https://chat.getgrav.org) [![Latest Release](https://img.shields.io/github/v/release/hibbitts-design/grav-plugin-helios-open-reader?style=flat-square&label=Release)](https://github.com/hibbitts-design/grav-plugin-helios-open-reader/releases/latest) [![License](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](https://github.com/hibbitts-design/grav-plugin-helios-open-reader/blob/master/LICENSE) [![PHP](https://img.shields.io/badge/PHP-%3E%3D8.0-8892BF?style=flat-square&logo=php&logoColor=white)](https://learn.getgrav.org/17/basics/requirements)

</div>

A free, open-source plugin that transforms the [Grav Premium Helios theme](https://getgrav.org/premium/helios) into a structured site for open educational content — open textbooks, readers, and student projects — built on [Grav CMS](https://getgrav.org) with Markdown file-based content, a built-in Admin panel, and no database required. Purchasing the Helios theme also directly supports Grav's open-source development.

## When is Grav Helios Open Reader a Good Candidate?

Grav Helios Open Reader is a strong fit when you:

- Want a web-first open textbook, course reader, student project collection, or collaboratively maintained living publication with a clean reading experience and proper CC licensing
- Need a structured layout with sections and sub-pages, auto-detected from your folder naming, with a configurable section label (Chapter, Project, Unit, Module, or any custom term)
- Want built-in callout blocks (Learning Objectives, Key Takeaways, Examples, Exercises, Definitions, Reflections, Case Studies) without coding
- Need to embed reader pages directly into an LMS (Canvas, Moodle, Brightspace) as clean iframes, with flexible Table of Contents positioning
- Want a Save My Place feature so readers can return to where they left off
- Want collaborative open authoring — invite readers to view source and suggest improvements via GitHub or Codeberg
- Need rich content embedding (H5P, iFrames, Google Slides, PDFs, Embedly) without coding
- Value keeping content in portable Markdown files with full ownership and hosting control
- Want dark mode, mobile-friendly design, and keyboard-accessible navigation out of the box

Other publishing tools might be better candidates when you:

- Need packaged export formats (PDF, ePub, DOCX) for offline distribution — browser print-to-PDF works, but no dedicated export pipeline is included
- Need built-in math/LaTeX rendering for STEM content (requires a separate Grav MathJax plugin)
- Need social annotation or inline commenting features (e.g. Hypothesis integration)
- Need zero-server, instant publishing directly from GitHub without any hosting setup
- Prefer a large ecosystem of themes and plugins beyond what Grav currently offers

Still unsure? Install the skeleton package on almost any Web server, replace the demo content with your own, and your reader is ready. Your content stays in portable Markdown files you own completely, and those same files work with other tools if your needs change. For zero-setup publishing directly from GitHub or Codeberg without a Web server, [Docsify-This](https://docsify-this.net) is a natural companion.

## Quick Start

The recommended starting point is the pre-configured [Grav Helios Open Reader Skeleton](https://github.com/hibbitts-design/grav-skeleton-helios-open-reader/releases/latest), which includes this plugin, demo content, and all required configuration.

1. **Download and install** the [Grav Helios Open Reader Skeleton](https://github.com/hibbitts-design/grav-skeleton-helios-open-reader/releases/latest) package
2. **Enter your licenses** – your Helios and complimentary SVG Icons license keys (or import an existing license file), then install and activate the theme

The skeleton comes pre-configured with demo sections and is ready to use after licensing the Helios theme.

To install the plugin manually on an existing Grav + Helios site, see the [Installation](#installation) and [Demo Content](#demo-content) sections below.

## Installation

Typically a plugin should be installed via [GPM](http://learn.getgrav.org/advanced/grav-gpm) (Grav Package Manager):

```
$ bin/gpm install helios-open-reader
```

Alternatively it can be installed via the [Admin Plugin](http://learn.getgrav.org/admin-panel/plugins).

## Demo Content

The `_demo` folder in this plugin contains a default Helios Open Reader site that can be used as a starting point:

- `00.reader/` – Reader home page (`reader.md` sets the book title, subtitle, authors, edition, license, and cover image)
- `01.section-1/` – Section 1: What is Open Education? (with sub-pages)
- `02.section-2/` – Section 2: Tools for Open Course Design (with sub-pages)
- `03.section-3/` – Section 3: Getting Started with Open Authoring (with sub-pages)
- `readme/` – In-site README page

To use the demo content, copy the contents of `_demo/pages/` into your Grav `user/pages/` folder.

## Helios Theme Configuration

> [!NOTE]
> This section is only needed when installing the plugin manually. The [Grav Helios Open Reader Skeleton](https://github.com/hibbitts-design/grav-skeleton-helios-open-reader) includes all of the following configuration automatically.

Add the following to `user/config/themes/helios.yaml` to configure the reader structure and search. The `version_pattern` detects `section-N` folder names automatically.

```yaml
versioning:
  version_pattern: '/^section-?\d+/i'
  labels:
    section-1: 'Introduction to the Subject'
    section-2: 'Core Concepts'
    section-3: 'Advanced Topics'
search:
  placeholder: 'Search reader...'
```

> **Note:** Labels are required — without them, Helios falls back to a raw slug display. Add one `key: 'Title'` entry per section folder. Labels can also be edited via **Admin → Themes → Helios → Versioning tab → Version Labels**.

> [!TIP]
> After adding or renaming a section folder, clear the Grav cache via the **Clear Cache** button in the Admin panel if the change does not appear immediately.

If disabling the plugin, manually restore the following Helios theme defaults in `user/config/themes/helios.yaml`:

```yaml
versioning:
  version_pattern: '/^v?\d+(\.\d+)*$/'
  labels:
    v1: "v1 (Legacy)"
    v2: "v2 (Stable)"
    v3: "v3 (Latest)"
search:
  placeholder: 'Search documentation...'
```

## Features

Helios Open Reader provides a ready-built site for open educational content — open textbooks, readers, and student projects — using portable Markdown files you fully control. Highlights include a configurable sections structure, a full set of callout blocks, Save My Place navigation, and optional Git Sync for open collaborative authoring.

### Reader Structure
- **Sections structure** — top-level folders named `section-N` are auto-detected as sections and render as section cards on the reader home
- **Section N header** — section pages automatically display their section number and configurable label in the page header (e.g. `Chapter 1`, `Project 2`, `Unit 3`); inherits correctly for all sub-pages within a section. Set via **Admin → Pages → Reader Home → Section Label**
- **Section sub-pages** — sections can contain any number of sub-pages, all shown in the sidebar and navigable with Prev/Next controls
- Reader home page with cover image, title, subtitle, authors, edition, and CC license badge

### Callout Blocks
- **Learning Objectives** — `[objectives]...[/objectives]` (green); also available as frontmatter (`learning_objectives:`) for automatic rendering at the top of a section page
- **Key Takeaways** — `[key-takeaways]...[/key-takeaways]` (blue)
- **Example** — `[example]...[/example]` (purple)
- **Exercise** — `[exercise]...[/exercise]` (amber)
- **Definition** — `[definition]...[/definition]` (blue)
- **Reflection** — `[reflection]...[/reflection]` (green)
- **Case Study** — `[case-study]...[/case-study]` (red)
- **Announcement** — `[announcement]...[/announcement]` (purple by default; configurable type)
- All callouts accept an optional `title="..."` parameter and support Markdown content
- Five built-in GitHub-style callouts via the github-markdown-alerts plugin: `> [!NOTE]`, `> [!TIP]`, `> [!IMPORTANT]`, `> [!WARNING]`, `> [!CAUTION]`

### Navigation & Reading Experience
- **Save My Place** — records the last section page visited in localStorage; a dismissable "Continue reading" strip appears on the reader home page on return
- **Reading progress indicator** — shows current page position (e.g. Page 4 of 22) with an accessible progress bar above the Prev/Next navigation on section pages
- **Prev/Next navigation** — configurable position: top, bottom, or both
- **TOC scroll spy** — active heading highlighted in the Table of Contents as the reader scrolls
- **Start button** — on the reader home; links directly to the first section. Button text is configurable (e.g. `Start Reading`, `Browse Projects`, `View Guides`)
- Search across the full reader via the simplesearch plugin

### LMS Embedding

Append `?embedded=true` (or `?chromeless=true`) to any page URL to display only the page content — no sidebar, header, or pagination. Designed for embedding Helios Open Reader pages in an LMS iframe (Canvas, Moodle, Brightspace, etc.).

- All internal links automatically carry the `?embedded=true` parameter forward, so navigating between pages stays in embedded mode
- Combine with `?toc_position=hidden` to also hide the Table of Contents
- Combine with `?toc_position=left` or `?toc_position=right` to reposition the Table of Contents to suit surrounding LMS navigation
- Combine with `?edit_link=false` (or `?hidegitlink=true`) to hide the "Edit this Page" link

**Example iframe:**

```html
<iframe src="https://yoursite.com/section-1?embedded=true" width="100%" height="600" style="border:none;"></iframe>
```

### Authoring & Customization
- Git Sync plugin for syncing reader content with GitHub, Codeberg, or similar Git hosting
- Automatic "Edit this Page" link via the Helios theme, defaulting to **View Page Markdown** for open access to reader content; optionally configurable to direct editing for contributors with repository access
- OER attribution block — display a CC license statement in the footer, drawn from reader home page frontmatter
- Customize CSS and JavaScript via the bundled plugin assets
- Print stylesheet with page break control, absolute link URLs displayed inline, and consistent page margins across browsers

If you prefer not to write Markdown directly, the optional [Grav Premium Editor Pro](https://getgrav.org/premium/editor-pro) provides a visual block editor for editing pages.

## Reader Setup

Place section folders directly under the site root alongside the reader home, named using the `section-N` convention:

```
00.reader/          ← reader home (reader.md)
01.section-1/       ← section 1 (section-page.md)
  01.section-one/   ← sub-page (section-page.md)
  02.section-two/   ← sub-page (section-page.md)
02.section-2/       ← section 2 (section-page.md)
03.section-3/       ← section 3 (section-page.md)
```

Add a `labels` entry in `user/config/themes/helios.yaml` for each section folder.

> [!TIP]
> After adding, renaming, or removing a section folder, clear the Grav cache via the **Clear Cache** button in the Admin panel.

### Reader Home Settings

The `reader.md` frontmatter controls the reader identity and card layout on the home page.

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
| `cards_per_row` | Number of section cards per row (1–3) |
| `card_icon` | Default icon for all cards (Tabler icon path) |
| `card_image_layout` | Card image position: `side` or `top` |
| `card_description_lines` | Maximum description lines per card (2, 3, or 0 for no limit) |

Page content written in `reader.md` appears above the cards by default. To also display content **below** the cards, add `===` on its own line as a delimiter:

```markdown
This text appears above the section cards.

===

This text appears below the section cards.
```

### Section Page Settings

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

## Templates

- **reader** – Reader home template displaying the book header, resume reading strip, and section card grid
- **section-page** – Section reading page with configurable section N header, optional Learning Objectives block from frontmatter, and main content; extends the full Helios base with sidebar and Prev/Next navigation
- **default-toc** – Content page with a right-column Table of Contents; set `template: default-toc` in any page's frontmatter to enable (requires the page-toc plugin, included)

> [!TIP]
> The `default-toc` template is ideal for standalone content-heavy pages such as a preface, bibliography, or about page that benefit from in-page navigation but don't need the section structure.

## Assets

- **helios.css** – Theme styling (announcement blockquotes, heading typography, Font Awesome spacing, responsive containers)
- **reader.css** – Reader-specific styles (callout block spacing, resume reading strip, reading progress indicator, top Prev/Next navigation styling)
- **helios.js** – Embedly dark/light theme support, Save My Place localStorage logic, HTMX content-loaded integration
- **print.css** – Print stylesheet (hides navigation chrome, resets colors for light and dark themes, controls page breaks, displays absolute link URLs, sets consistent page margins)
- **admin.css** – Helios-inspired Admin Panel styling (conditionally loaded based on the Helios-inspired Admin Styling setting)
- **admin.js** – Admin panel JavaScript customizations

## Shortcodes

### Callout Blocks

All callouts accept an optional `title="..."` parameter and support Markdown content.

- `[objectives]...[/objectives]` – Learning Objectives block (green)
- `[objectives title="By the end of this section..."]...[/objectives]` – With custom title
- `[key-takeaways]...[/key-takeaways]` – Key Takeaways block (blue)
- `[example]...[/example]` – Example block (purple)
- `[exercise]...[/exercise]` – Exercise block (amber)
- `[definition]...[/definition]` – Definition block (blue)
- `[reflection]...[/reflection]` – Reflection block (green)
- `[case-study]...[/case-study]` – Case Study block (red)
- `[announcement]...[/announcement]` – Announcement notice (purple by default), supports Markdown
- `[announcement title="..." type="..."]...[/announcement]` – With optional custom title and type (`note`, `tip`, `important`, `warning`, `caution`)

> [!TIP]
> For simple notices, the standard Markdown callout `> [!IMPORTANT]` is a zero-friction alternative to the `[announcement]` shortcode.

### Embedding

- `[iframe url="..."]` – Responsive iframe embed, 16:9 by default
- `[iframe url="..." ratio="4:3"]` – Responsive iframe embed at 4:3 ratio
- `[googleslides url="..."]` – Responsive Google Slides embed, 16:9 by default
- `[googleslides url="..." ratio="4:3"]` – Responsive Google Slides embed at 4:3 ratio
- `[pdf url="..."]` – PDF viewer via Google Docs, 16:9 by default
- `[pdf url="..." ratio="4:3"]` – PDF viewer at 4:3 ratio
- `[pdf url="..." ratio="portrait"]` – PDF viewer at portrait ratio (letter/A4)
- `[h5p url="..."]` – H5P interactive content via full embed URL
- `[h5p id="..."]` – H5P interactive content via Content ID (requires H5P Content Embed Source URL to be set in plugin settings)
- `[embedly url="..."]` – Embedly card with dark mode support

## Plugin Settings

The following settings are available in the Admin panel under **Plugins → Helios Open Reader**:

| Setting | Default | Description |
|---------|---------|-------------|
| Helios-inspired Admin Styling | Enabled | Apply Helios-inspired styling enhancements to the Admin Panel (rounded corners, transitions, improved typography) |
| Admin Font Size | Large | Sets the Admin Panel font size: Default, Large, or Larger |
| Show Site Logo Icon | Enabled | Show or hide the icon square next to the Logo Text in the header when no logo image is set |
| Site Logo Icon | `tabler/notebook.svg` | Tabler icon path for the site logo icon square. Only applies when Show Site Logo Icon is enabled |
| Show Plugin Credits | Enabled | Show or hide the "Built with Grav · Helios · Helios Open Reader" attribution line in the footer |
| Show Repository Host Icon Link in Header | Enabled | Show a GitHub or Codeberg icon link to the reader repository in the site header (requires GitHub Integration enabled in the Helios theme) |
| Git Link Icon | `tabler/file-text.svg` | Tabler icon path for the Git link icon shown in the page footer |
| Git Link Mode | View file | Whether the Git link opens the file for **viewing** (default, for open access) or **editing** (for contributors with repository access) |
| Repository Host | `github.com` | Repository hosting service for the Helios GitHub Integration (`github.com` or `codeberg.org`) |
| H5P Content Embed Source URL | `https://h5p.org/h5p/embed/` | Base URL for H5P embeds via Content ID (used with `[h5p id="..."]`) |

> **Note:** To apply the Helios-inspired Admin Panel colour scheme (zinc nav, accessible blue links, muted purple accents), go to **Admin → Customization → Presets**, select **Helios**, and click **Save**. When using the skeleton, this preset is pre-configured automatically.

## Requirements

- PHP >= 8.0
- Grav CMS >= 1.7.0
- [Grav Premium Helios Theme](https://getgrav.org/premium/helios) – one license per site ([Standard or Team](https://getgrav.org/premium/license))

## Support

### Contact and Support
- Follow [@hibbittsdesign@mastodon.social](https://mastodon.social/@hibbittsdesign) on Mastodon for updates
- 👩🏻‍💻🧑🏻‍💻 Join the [Grav Discord](https://chat.getgrav.org) and often find me there
- Add a ⭐️ [star on GitHub](https://github.com/hibbitts-design/grav-skeleton-helios-open-reader) to the Helios Open Reader project repository
- For bugs or feature requests, [open an issue](https://github.com/hibbitts-design/grav-plugin-helios-open-reader/issues) on GitHub

### Professional Services

By leveraging his extensive UX design expertise and systems-oriented approach, Paul helps teams and individuals utilize open content in education and publication settings. Professional services include user experience and workflow consulting, premium support subscriptions, workshops, and custom development. Interested? Send a note to [paul@hibbittsdesign.org](mailto:paul@hibbittsdesign.org).

## License

MIT – Hibbitts Design
