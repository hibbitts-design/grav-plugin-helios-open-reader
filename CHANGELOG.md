# v0.9.9
## 06/16/2026

1. [](#improved)
   * Add has() guard to shared shortcodes to prevent duplicate handler error when both plugins are active simultaneously

# v0.9.8
## 06/15/2026

1. [](#bugfix)
   * Add ignore missing to yetisearch-pro include
   * Update plugin templates and PHP to use Helios v2.1.6 renamed Twig variables (helios_* → doc_*, nav_tree)
   * Fix duplicate shortcode handler error on Grav v2.0 by guarding onShortcodeHandlers() against multiple firings

# v0.9.7
## 05/21/2026

1. [](#improved)
   * Updated demo pages and ReadMe
   * Updates for Helios theme v2.1.3: migrate base.html.twig to swap-body architecture and add missing TOC layout variables

# v0.9.6
## 05/18/2026

1. [](#improved)
   * Removed no longer needed Admin2 font sizing

# v0.9.5
## 05/15/2026

1. [](#improved)
   * Trim trailing slash from repo value in footer git link URL
   * Add absolute URL resolution for images in llms-full.txt output
   * Add configurable image URL handling (absolute, suppress, relative) for plain text version

# v0.9.4
## 05/14/2026

1. [](#improved)
   * Fix llms.txt output to use colon separator per spec
   * Add configurable icon to plain text version footer link
   * Remove border between GitHub link and OER attribution in footer

# v0.9.3
## 05/13/2026

1. [](#new)
   * Add opt-in plain text version endpoints (llms.txt / llms-full.txt) with documentation

1. [](#improved)
   * Reduce Admin2 zoom and prevent button text wrapping in large font size modes

# v0.9.2
## 05/07/2026

1. [](#improved)
   * Add admin label alignment adjustment for Admin 2 with optional config key

1. [](#bugfix)
   * Fix continue reading cross-site bleed by namespacing localStorage key with window.location.origin

# v0.9.1
## 05/06/2026

1. [](#bugfix)
    * Updated blueprints.yaml icon and description

# v0.9.0
## 04/28/2026

1. [](#new)
    * ChangeLog started...
