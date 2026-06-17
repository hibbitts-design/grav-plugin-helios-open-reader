---
title: 'Templates & Assets'
description: 'Available page templates and CSS/JavaScript assets for customizing your reader.'
---

## Templates

- **reader** – Reader home template displaying the reader header, resume reading strip, and section card grid
- **section-page** – Section reading page with configurable section N header, optional Learning Objectives block from frontmatter, and main content
- **default-toc** – Content page with a right-column Table of Contents; set `template: default-toc` in any page's frontmatter to enable (requires the page-toc plugin, included)

> [!TIP]
> The `default-toc` template is ideal for standalone content-heavy pages such as a preface, bibliography, or about page that benefit from in-page navigation but don't need the section structure.

## CSS & JavaScript Assets

- **helios.css** – Theme styling (announcement blockquotes, heading typography, Font Awesome spacing, responsive containers)
- **reader.css** – Reader-specific styles (callout block spacing, resume reading strip, reading progress indicator, top Prev/Next navigation styling)
- **helios.js** – Embedly dark/light theme support, Save My Place localStorage logic, HTMX content-loaded integration
- **print.css** – Print stylesheet (hides navigation chrome, resets colors for light and dark themes, controls page breaks, displays absolute link URLs, sets consistent page margins)
- **admin.css** – Helios-inspired Admin Panel styling (conditionally loaded based on the Helios-inspired Admin Styling setting)
- **admin.js** – Admin panel JavaScript customizations

To customize the reader's appearance, edit or override these files in the plugin's `assets/` directory. Changes persist across Grav cache clears.
