# v1.1.3
## XX/XX/2026

1. [](#improved)
   * * Update ReadMe and example pages

# v1.1.2
## 08/02/2026

1. [](#bugfix)
   * Handle unreachable Embedly URLs with a clear "no longer available" link instead of vague fallback text
   * Fix extra spacing around link preview cards caused by Tailwind prose overriding unset image margins

# v1.1.1
## 08/01/2026

1. [](#improved)
   * Update ReadMe

# v1.1.0
## 07/11/2026

1. [](#new)
   * Add self-hosted [linkpreviewcard] shortcode as a working replacement for legacy Embedly card

1. [](#improved)
   * Fix Grav 2 tagfilter escaping in H5PShortcode by moving the resizer script to the Assets API

# v1.0.1
## 07/08/2026

1. [](#improved)
   * Update example pages

# v1.0.0
## 07/07/2026

1. [](#new)
   * Add Content Text Scaling setting to toggle comfortable (18px) reading size for section/module pages

1. [](#improved)
   * Update ReadMe
   * Update example pages

# v0.9.48
## 07/05/2026

1. [](#improved)
   * Suppress sidebar section label when nested_section_nav is enabled, as the collapsible nav parent already provides that context

# v0.9.47
## 07/05/2026

1. [](#new)
   * Add nested_section_nav option and position it with other section fields in the Content tab blueprint

# v0.9.46
## 07/05/2026

1. [](#improved)
   * Restored PDF shortcode default ratio to 16:9

1. [](#bugfix)
   * Fix GoogleSlides shortcode: switch to getHandlers() to prevent raw HTML output

# v0.9.45
## 07/05/2026

1. [](#improved)
   * Changed "Save My Place" to "Keep My Place"

1. [](#bugfix)
   * Fix H5P iframe clipping and default PDF embed to portrait aspect ratio in both plugins

# v0.9.44
## 07/03/2026

1. [](#improved)
   * Remove mobile breadcrumb title div from header

1. [](#bugfix)
   * Fix URL double-encoding and H5P mobile layout in embed shortcodes

# v0.9.43
## 07/02/2026

1. [](#improved)
   * Add [references] shortcode, improve H5P exercise blocks, and add figcaption styling
   * Render full body content in exercise shortcode when block elements are present

# v0.9.42
## 07/01/2026

1. [](#improved)
   * Add [references] shortcode, improve H5P exercise blocks, and add figcaption styling

# v0.9.41
## 07/01/2026

1. [](#improved)
   * Add WCAG 2.1 listbox keyboard navigation to version dropdown

# v0.9.40
## 07/01/2026

1. [](#improved)
   * Use system sans-serif for section-list cards, resume strip, Start Reading button, and page footer

# v0.9.39
## 07/01/2026

1. [](#bugfix)
   * Use page.template check to prevent section-list's section_label from hiding chapter numbers on section landing pages

# v0.9.38
## 07/01/2026

1. [](#new)
   * Add show_section_label toggle to hide section label on cards and page headers

# v0.9.37
## 07/01/2026

1. [](#new)
   * Add per-section section_label override for custom labels or hiding individual section labels

# v0.9.36
## 06/30/2026

1. [](#bugfix)
   * Fix version dropdown label alignment for long wrapping text

# v0.9.35
## 06/30/2026

1. [](#improved)
   * Port HTMX history-miss fix from Helios theme v2.1.9 to plugin base templates

1. [](#bugfix)
   * Fix WCAG 2.1 critical and serious accessibility issues in templates and shortcodes

# v0.9.34
## 06/29/2026

1. [](#bugfix)
   * Fix sticky nav button alignment — NEXT always right-aligned, remove empty placeholder div

# v0.9.33
## 06/29/2026

1. [](#improved)
   * Add sticky prev/next bar to section pages with mobile-always-on and scroll-triggered behaviour on larger screens

# v0.9.32
## 06/29/2026

1. [](#improved)
   * Add sticky prev/next bar on mobile and address nav button height, alignment, and title truncation

# v0.9.31
## 06/29/2026

1. [](#improved)
   * Adjust mobile prev/next nav button height, alignment, and title truncation

# v0.9.30
## 06/28/2026

1. [](#improved)
   * Add heading font toggle to Typography section, defaulting to Helios theme font for headings

# v0.9.29
## 06/28/2026

1. [](#bugfix)
   * Exclude prev/next navigation from custom font by resetting to Helios theme font
   
# v0.9.28
## 06/28/2026

1. [](#new)
   * Add custom Google Font support with font family, URL, and size options to Typography section

# v0.9.27
## 06/28/2026

1. [](#improved)
   * Add [excerpt] shortcode for subtle grey border styling on multi-paragraph blockquotes
   * Update ReadMe

# v0.9.26
## 06/27/2026

1. [](#improved)
   * Update example pages

# v0.9.25
## 06/26/2026

1. [](#bugfix)
   * Fix redirect pages appearing as prev/next nav targets by filtering them from the depth-first page list in the plugin

# v0.9.24
## 06/25/2026

1. [](#bugfix)
   * Ensure og:image URL is absolute for social media preview cards

# v0.9.23
## 06/25/2026

1. [](#new)
   * Set cover image to full width on mobile in sidebar layout

# v0.9.22
## 06/24/2026

1. [](#new)
   * Add sidebar cover image layout option to section-list pages

# v0.9.21
## 06/24/2026

1. [](#improved)
   * Update example pages

# v0.9.20
## 06/22/2026

1. [](#improved)
   * Add custom URL option for header git icon link, independent of Helios GitHub Integration

# v0.9.19
## 06/22/2026

1. [](#bugfix)
   * Fix Save My Place resetting when switching between publications — use per-publication localStorage keys instead of a single shared key
   * Fix ?embedded=true not suppressing header/footer on default-toc, section-list, and reader-list pages — add chromeless support to base-simple-wide.html.twig and override base-simple.html.twig
   * Fix missing Open Graph meta tags on section-list and reader-list pages
   * Fix missing SKIP_TO_CONTENT i18n key causing raw key string to render on screen

1. [](#improved)
   * Align section-list blueprint title to template name (Reader Section List)

# v0.9.18
## 06/19/2026

1. [](#improved)
   * Split section-page template into section (landing) and section-page (content), updating blueprints, PHP, Twig, demo pages, and docs to match. Upgrade note: rename each section folder's root file from section-page.md to section.md - the site renders correctly without this, but card fields will no longer be editable in the Admin panel until the rename is done.

# v0.9.17
## 06/19/2026

1. [](#improved)
   *  Further revise blueprint tabs to improve field grouping

# v0.9.16
## 06/19/2026

1. [](#improved)
   * Reorganise blueprint tabs to bring markdown editor closer to top of Content tab

# v0.9.15
## 06/19/2026

1. [](#improved)
   * Update example pages
   * Remove readers list resume strip, simplify Save My Place to publication home only, and add i18n for user-visible strings

# v0.9.14
## 06/18/2026

1. [](#improved)
   * Update example pages
   * Add Save My Place to readers list with "Last read" strip linking to publication home, plus Last Updated field on publication cards

# v0.9.13
## 06/18/2026

1. [](#improved)
   * Update example pages
   * Add publication card authors, edition, group grouping, and simplified readers list header

# v0.9.12
## 06/17/2026

1. [](#improved)
   * Improve Admin2 theme warning, refactor Admin1 notice to language strings, and rename publication templates to readers

# v0.9.11
## 06/17/2026

1. [](#improved)
   * Add per-publication plain text export with scoped footer link and hide link on publications list page
   
# v0.9.10
## 06/17/2026

1. [](#new)
   * Add multi-publication support using section-list template, with per-publication Save My Place, search scoping, and single-publication logo link bypass

# v0.9.9
## 06/17/2026

1. [](#bugfix)
    * Fix incorrect home alias

# v0.9.8
## 06/17/2026

1. [](#improved)
    * Updated screenshot
    * Update example pages (default multi-publication setup)
    * Updated ReadMe

# v0.9.7
## 06/15/2026

1. [](#improved)
    * Updated with latest Helios Open Reader plugin
    
# v0.9.6
## 05/21/2026

1. [](#bugfix)
    * Set default theme to Helios

# v0.9.5
## 05/21/2026

1. [](#improved)
    * Updated ReadMe
    * Update example pages
    * Updated with latest Helios Open Reader plugin

# v0.9.4
## 05/18/2026

1. [](#improved)
    * Updated screenshot

# v0.9.3
## 05/18/2026

1. [](#new)
    * Add notebook favicon matching site icon
    * Add footnote examples to OER attribution sample page

1. [](#improved)
    * Updated with latest Helios Open Reader plugin

# v0.9.2
## 05/13/2026

1. [](#improved)
    * Updated ReadMes
    * Updated with latest Helios Open Reader plugin

# v0.9.1
## 05/06/2026

1. [](#bugfix)
    * Updated blueprints.yaml

# v0.9.0
## 04/28/2026

1. [](#new)
    * ChangeLog started...