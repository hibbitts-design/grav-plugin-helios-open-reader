---
title: ReadMe
published: true
simplesearch:
  process: false
---

Publish open textbooks, course readers, student projects, and other OER publications on the web, without building from scratch. This package, combined with the [Grav Premium Helios theme](https://getgrav.org/premium/helios), provides a web-first reading platform built on [Grav CMS](https://getgrav.org) (an open-source, flat-file CMS with no database required and a built-in Admin panel), with content you fully control.

## Who This Is For

Helios Open Reader is a **web-first publishing platform**: a place to openly share open textbooks, course readers, student projects, and other OER that you keep and control. It is not a learning management system and does not include enrollment, grade tracking, or student progress features.

It is well suited for educators and authors who want full control over their content, structure, and hosting, including:
- Individual educators wanting a clean, open web reader for course materials or an open textbook
- Authors publishing OER with CC licensing and open-authoring workflows
- Teams hosting shared readings, lab manuals, or module collections
- Instructors hosting student projects or a collaboratively maintained living publication updated each term

Content pages are written in Markdown, with optional shortcodes for embedding and callout blocks. For zero-setup publishing directly from GitHub or Codeberg without a Web server, [Docsify-This](https://docsify-this.net) is a natural companion.

## Why Helios Open Reader

Helios Open Reader gives you a modern, open, and fully controlled web reading experience that works alone or embedded in any LMS – a dedicated home for open textbooks, course readers, and student projects that you control completely.

- Ready in minutes – a complete, pre-configured package with demo content included
- Structured – supports sections with sub-pages, auto-detected from folder naming, with a configurable section label (Chapter, Project, Unit, Module, or any custom term)
- Callout blocks – Learning Objectives, Key Takeaways, Examples, Exercises, Definitions, Reflections, and Case Studies, all without coding
- Save My Place – readers can return to where they left off across sessions
- Reading progress – accessible progress indicator shows current page position (e.g. Page 4 of 22)
- LMS-ready – embed any page cleanly in Canvas, Moodle, or Brightspace with `?embedded=true`
- Open by design – optionally enable the built-in Git Sync and "View Page Markdown" link support
- Flat-file simplicity – your content is just Markdown files you own and control
- Support open source – your Grav Premium Helios theme purchase directly supports ongoing development of the open-source Grav CMS

## Features

Helios Open Reader provides a ready-built site for open educational content – open textbooks, readers, and student projects – using portable Markdown files you fully control. Highlights include a configurable sections structure, a full set of callout blocks, Save My Place navigation, and optional Git Sync for open collaborative authoring.

### Reader Structure
- **Sections structure** – top-level folders named `section-N` are auto-detected as sections and render as cards on the reader home
- **Optional parts grouping** – rename section folders to `part-N-section-M` (e.g. `part-1-section-1`, `part-2-section-1`) to group sections into parts; part headings appear automatically on the reader home, and Prev/Next navigation and reading progress are scoped per part
- **Section N header** – section pages automatically display their section number and label in the page header; inherits correctly for all sub-pages within a section. The label is configurable (e.g. Chapter, Project, Unit, Module) via **Admin → Pages → Reader Home → Section Label**
- **Section sub-pages** – sections can contain any number of sub-pages, all shown in the sidebar and navigable with Prev/Next controls
- Reader home page with cover image, title, subtitle, authors, edition, and CC license badge

### Callout Blocks
- **Learning Objectives** – `[objectives]...[/objectives]` (green); also available as frontmatter (`learning_objectives:`) for automatic rendering at the top of a section page
- **Key Takeaways** – `[key-takeaways]...[/key-takeaways]` (blue)
- **Example** – `[example]...[/example]` (purple)
- **Exercise** – `[exercise]...[/exercise]` (amber)
- **Definition** – `[definition]...[/definition]` (blue)
- **Reflection** – `[reflection]...[/reflection]` (green)
- **Case Study** – `[case-study]...[/case-study]` (red)
- **Announcement** – `[announcement]...[/announcement]` (purple by default; configurable type)
- **Project Brief** – `[project-brief]...[/project-brief]` (amber); frames the assignment or challenge prompt
- **Feedback Requested** – `[feedback-requested]...[/feedback-requested]` (purple); flags content awaiting review — useful in student projects and draft OER alike
- **Process Note** – `[process-note]...[/process-note]` (blue); documents iterations, decisions, or pivots during a project
- All callouts accept an optional `title="..."` parameter and support Markdown content
- Five built-in GitHub-style callouts: `> [!NOTE]`, `> [!TIP]`, `> [!IMPORTANT]`, `> [!WARNING]`, `> [!CAUTION]`

### Navigation & Reading Experience
- **Save My Place** – records the last section page visited in localStorage; a dismissable "Continue reading" strip appears on the reader home page on return
- **Reading progress indicator** – shows current page position (e.g. Page 4 of 22) with an accessible progress bar above the Prev/Next navigation on section pages
- **Prev/Next navigation** – configurable position: top, bottom, or both
- **TOC scroll spy** – active heading highlighted in the Table of Contents as the reader scrolls
- **Start button** – on the reader home; links directly to the first section. Button text is configurable (e.g. `Start Reading`, `Browse Projects`, `View Guides`)
- Search across the full reader via the simplesearch plugin

### Embedding & Shortcodes
- Embed rich content with built-in shortcodes: iFrames, Google Slides, PDFs, H5P, and Embedly cards, with responsive 16:9 layout and automatic dark/light theme detection
- TOC position override: append `?toc_position=hidden|left|right` to any page URL to override the Table of Contents position per request, without changing page frontmatter
- Git link visibility: append `?edit_link=false` (or `?hidegitlink=true`) to any page URL to hide the "Edit this Page" link per request

### LMS Embedding

Append `?embedded=true` to any page URL to display only the page content – no sidebar, header, or pagination. Designed for embedding Helios Open Reader pages in an LMS iframe (Canvas, Moodle, Brightspace, etc.).

- All internal links automatically carry the `?embedded=true` parameter forward, so navigating between pages stays in embedded mode
- `?chromeless=true` is also supported as an alternative parameter name
- Works with HTMX navigation – links in dynamically loaded content are also rewritten
- Combine with `?toc_position=hidden` to also hide the Table of Contents, useful for narrow iframe embeds
- Combine with `?toc_position=left` or `?toc_position=right` to reposition the Table of Contents, useful for working with surrounding LMS navigation elements
- Combine with `?edit_link=false` (or `?hidegitlink=true`) to hide the "Edit this Page" link on a per-page basis

**Example iframe:**

```html
<iframe src="https://yoursite.com/section-1/introduction?embedded=true" width="100%" height="600" style="border:none;"></iframe>
```

### Authoring & Customization
- Git Sync plugin included for syncing reader content with GitHub, Codeberg, or similar Git hosting
- Automatic "Edit this Page" link via the Helios theme, defaulting to **View Page Markdown** for open access to reader content; optionally configurable to direct editing for contributors with repository access
- OER attribution block – display a CC license statement in the footer, drawn from reader home page frontmatter
- Customize CSS and JavaScript via the bundled plugin assets
- Print stylesheet with page break control, absolute link URLs displayed inline, and consistent page margins across browsers

## Quick Start

The skeleton is a **complete package** – Grav CMS, the Helios Open Reader plugin, and demo content are all included; the [Grav Premium Helios theme](https://getgrav.org/premium/helios) requires a separate license. The home page is a reader landing page showing demo sections.

### Pre-flight Checklist
1. Confirm your web server meets [Grav's requirements](https://learn.getgrav.org/17/basics/requirements) (PHP 8.0 or higher)
2. Have your web server login credentials ready (username and password)

### Installation Steps
1. **Download** the [Grav Helios Open Reader Skeleton](https://github.com/hibbitts-design/grav-skeleton-helios-open-reader/releases/latest) package
2. **Unzip** the package onto your desktop
3. **Copy** the entire Grav Helios Open Reader folder to your web server (e.g. into `public_html/` or a subfolder within it)
4. **Open your browser** and go to your site's URL (e.g. `https://yoursite.com/grav-open-reader`)
5. **Create your site administrator account** when prompted
6. **Enter your Helios and SVG Icons license keys** (or import an existing license file), then install and activate the theme
7. **You're done!** – press the preview icon in the Admin Panel to view your site

> [!TIP]
> When copying the Grav Helios Open Reader folder to your web server, copy the **entire folder** – it contains hidden files (such as `.htaccess`) that are not selected by default. Omitting these hidden files can cause problems when running Grav.

## Reader Setup

All reader content lives within `user/pages/`. The skeleton ships with a reader home page and three pre-configured demo sections.

```
user/pages/
├── 00.reader/              # Reader home page
│   └── reader.md           # Reader title, subtitle, authors, edition, license, cover image
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

### Showing and Hiding Sections

In the Admin panel, open the section folder and set **Published** to **Yes** to show or **No** to hide it. Unpublished sections are also excluded from search results and the sidebar.

Once you have set up your own content, you can safely delete any unused demo sections from `user/pages/` via the Admin panel or FTP.

> [!TIP]
> If changes don't appear immediately after publishing pages or updating settings, clear the Grav cache via the **Clear Cache** button in the Admin panel.

### Adding a New Section

To add a section, copy an existing section folder (e.g. `01.section-1/`) via FTP or the Admin panel (when using the Admin panel, open the section page, click the copy icon, then update the **Page Title** field to a valid new section ID such as `section-4`). Ensure the folder name follows the `section-N` convention, then add the new folder name as a key in `versioning.labels` in `user/config/themes/helios.yaml` (or via **Admin → Themes → Helios → Versioning → Version Labels**). Finally, set **Published** to **Yes** in the Admin panel to make it visible.

> [!TIP]
> After duplicating and renaming a section folder, clear the Grav cache via the **Clear Cache** button in the Admin panel if the new section does not appear immediately.

## Reader Home Settings

The `reader.md` frontmatter controls the reader identity and card layout on the home page. These fields can be set in the Admin Panel by opening the reader home page.

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
| `section_label` | Label used for sections throughout the reader (e.g. `Chapter`, `Unit`). Leave empty to use the language default (`Section`). |
| `part_label` | Label used for part headings on the reader home page when using the `part-N-section-M` folder naming pattern (e.g. `Theme`, `Project`). Leave empty to use the default (`Part`). |
| `parts` | Optional list of custom part titles (see Part Label below) |
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

## Section Page Settings

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

## Label Customization

### Reader Title

The title displayed in the browser tab and header comes from the `title` field in `reader.md`. Edit it via **Admin → Pages → Reader Home**, or directly in `user/pages/00.reader/reader.md`.

### Section Label

The label used for sections throughout the reader (in page headers, cards, and the sidebar) can be set via **Admin → Pages → Reader Home → Section Label**. Leave it empty to use the language default (`Section` in English, `Chapitre` in French). Examples: `Chapter`, `Project`, `Unit`, `Module`.

For multi-language sites, the per-language default is set via `SECTION_LABEL` in `user/plugins/helios-open-reader/languages.yaml`. English and French are included:

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

### Part Label

When sections are grouped into parts using the `part-N-section-M` folder naming pattern, the part heading label (default: `Part`) can be customized via **Admin → Pages → Reader Home → Part Label**. Leave it empty to use the default. Examples: `Theme`, `Project`.

To use custom titles for individual parts instead of the auto-generated "Part 1", "Part 2" labels, add a `parts` block to `reader.md` frontmatter:

```yaml
parts:
  - id: part-1
    label: 'Foundations of Open Education'
  - id: part-2
    label: 'Applying Open Practices'
```

### Section Names

The individual section name shown in the sidebar, the section dropdown (when enabled), and as the middle segment of the browser tab title (`Page Title | Reader Title | Site Title`) comes from the `versioning.labels` setting in the Helios Theme config. These can be edited via **Admin → Themes → Helios → Versioning tab → Version Labels**, or directly in `user/config/themes/helios.yaml`:

```yaml
versioning:
  labels:
    section-1: 'What is Open Education?'
    section-2: 'Tools for Open Course Design'
    section-3: 'Getting Started with Open Authoring'
```

## Browser Tab Title

The browser tab title is automatically formatted as:

`Page Title | Reader Title | Site Title`

The Reader Title is drawn from the reader home page title. The Site Title comes from `site.title` in `user/config/site.yaml`. Set the Site Title to your institution or author name – it serves as the top-level identifier in the browser tab.

## Git Sync & Open Editing

The skeleton includes the [Git Sync plugin](https://github.com/trilbymedia/grav-plugin-git-sync), which keeps your site content automatically in sync with a GitHub or Codeberg repository. This enables a full open-authoring workflow:

- Content editors can work directly in the Grav Admin or commit changes via Git
- The Helios Theme's **"Edit this Page"** option defaults to a 'View Page Markdown' link on each page, taking readers directly to the Markdown source file in your repository (configurable to link directly to file editing via the Helios Open Reader plugin settings)

If you prefer not to write Markdown directly, the optional [Grav Premium Editor Pro](https://getgrav.org/premium/editor-pro) provides a visual block editor for editing pages.

## Included Plugin: Helios Open Reader

Custom CSS, JavaScript, shortcodes, callout blocks, and Helios-inspired Admin Panel styling for the Helios Open Reader skeleton. If the Helios theme is not installed, the plugin automatically falls back to the Quark or Quark2 theme so the frontend site remains viewable, redirecting to the License Manager page in the Admin panel.

### Templates
- **reader** – Reader home template displaying the reader header, resume reading strip, and section card grid
- **section-page** – Section reading page with configurable section N header, optional Learning Objectives block from frontmatter, and main content
- **default-toc** – Content page template with a right-column Table of Contents; set `template: default-toc` in any page's frontmatter to enable (requires the page-toc plugin, included)

> [!TIP]
> The `default-toc` template is ideal for standalone content-heavy pages such as a preface, bibliography, or about page that benefit from in-page navigation but don't need the section structure.

### Assets
- **helios.css** – Theme styling (announcement blockquotes, heading typography, Font Awesome spacing, responsive containers)
- **reader.css** – Reader-specific styles (callout block spacing, resume reading strip, reading progress indicator, top Prev/Next navigation styling)
- **helios.js** – Embedly dark/light theme support, Save My Place localStorage logic, HTMX content-loaded integration
- **print.css** – Print stylesheet (hides navigation chrome, resets colors for light and dark themes, controls page breaks, displays absolute link URLs, sets consistent page margins)
- **admin.css** – Helios-inspired Admin Panel styling (conditionally loaded based on the Helios-inspired Admin Styling setting)
- **admin.js** – Admin panel JavaScript customizations

### Shortcodes

All callouts accept an optional `title="..."` parameter and support Markdown content.

- [raw]`[objectives]...[/objectives]`[/raw] – Learning Objectives block (green)
- [raw]`[key-takeaways]...[/key-takeaways]`[/raw] – Key Takeaways block (blue)
- [raw]`[example]...[/example]`[/raw] – Example block (purple)
- [raw]`[exercise]...[/exercise]`[/raw] – Exercise block (amber)
- [raw]`[definition]...[/definition]`[/raw] – Definition block (blue)
- [raw]`[reflection]...[/reflection]`[/raw] – Reflection block (green)
- [raw]`[case-study]...[/case-study]`[/raw] – Case Study block (red)
- [raw]`[announcement]...[/announcement]`[/raw] – Announcement notice (purple by default), supports Markdown
- [raw]`[announcement title="..." type="..."]...[/announcement]`[/raw] – With optional custom title and type (`note`, `tip`, `important`, `warning`, `caution`)
- [raw]`[project-brief]...[/project-brief]`[/raw] – Project Brief block (amber); frames the assignment or challenge prompt
- [raw]`[feedback-requested]...[/feedback-requested]`[/raw] – Feedback Requested block (purple); flags content awaiting review — useful in student projects and draft OER alike
- [raw]`[process-note]...[/process-note]`[/raw] – Process Note block (blue); documents iterations, decisions, or pivots during a project
- [raw]`[iframe url="..."]`[/raw] – Responsive iframe embed, 16:9 by default
- [raw]`[googleslides url="..."]`[/raw] – Responsive Google Slides embed, 16:9 by default
- [raw]`[pdf url="..."]`[/raw] – PDF viewer via Google Docs, 16:9 by default
- [raw]`[pdf url="..." ratio="portrait"]`[/raw] – PDF viewer at portrait ratio (letter/A4)
- [raw]`[h5p url="..."]`[/raw] – H5P interactive content via full embed URL
- [raw]`[h5p id="..."]`[/raw] – H5P interactive content via Content ID (requires H5P Content Embed Source URL to be set in plugin settings)
- [raw]`[embedly url="..."]`[/raw] – Embedly card with dark mode support

> [!TIP]
> For simple notices, the standard Markdown callout `> [!IMPORTANT]` is a zero-friction alternative to the [raw]`[announcement]`[/raw] shortcode.

### Plugin Settings

The following settings are available in the Admin panel under **Plugins → Helios Open Reader**:

| Setting | Default | Description |
|---------|---------|-------------|
| Helios-inspired Admin Styling | Enabled | Apply Helios-inspired styling enhancements to the Admin Panel (rounded corners, transitions, improved typography) |
| Admin Font Size | Large | Sets the Admin Panel font size: Default, Large, or Larger |
| Show Site Logo Icon | Enabled | Show or hide the icon square next to the Logo Text in the header when no logo image is set |
| Site Logo Icon | [raw]`tabler/notebook.svg`[/raw] | Tabler icon path for the site logo icon square. Only applies when Show Site Logo Icon is enabled |
| Show Plugin Credits | Enabled | Show or hide the "Built with Grav · Helios · Helios Open Reader" attribution line in the footer |
| Show Repository Host Icon Link in Header | Enabled | Show a GitHub or Codeberg icon link to the reader repository in the site header (requires GitHub Integration enabled in the Helios theme) |
| Git Link Icon | [raw]`tabler/file-text.svg`[/raw] | Tabler icon path for the Git link icon shown in the page footer |
| Git Link Mode | View file | Whether the Git link opens the file for **viewing** (default, for open access) or **editing** (for contributors with repository access) |
| Repository Host | [raw]`github.com`[/raw] | Repository hosting service for the Helios GitHub Integration ([raw]`github.com`[/raw] or [raw]`codeberg.org`[/raw]) |
| H5P Content Embed Source URL | `https://h5p.org/h5p/embed/` | Base URL for H5P embeds via Content ID (used with [raw]`[h5p id="..."]`[/raw]) |

> **Note:** The Helios-inspired Admin Panel colour scheme (zinc nav, accessible blue links, muted purple accents) is pre-configured in this skeleton.

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

> [!TIP]
> Want to no longer display this page on your site? Go to **Helios Theme Settings > Appearance**, scroll down to the bottom of the page and delete the **Header Menu** item **ReadMe**.
