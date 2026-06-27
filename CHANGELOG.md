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