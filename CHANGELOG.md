# v0.9.0
## 04/28/2026

1. [](#new)
    * ChangeLog started...
    * Reader home page template (`reader`) with chapter card grid, cover image, authors, edition, and license badge
    * Chapter page template (`chapter-page`) with learning objectives block from frontmatter
    * Prev/Next navigation with configurable position — top, bottom, or both (default: both)
    * Save My Place — persists last chapter page to localStorage; "Continue reading" strip on reader home page
    * `[objectives]` shortcode for chapter-opening learning objectives
    * `[key-takeaways]` shortcode for chapter-closing summaries
    * OER attribution block in footer — CC license and attribution text from reader home frontmatter
    * LMS embedding via `?chromeless=true` / `?embedded=true` URL parameter
    * TOC position override via `?toc_position` / `?toc` URL parameter
    * Git edit link suppression via `?edit_link=false` / `?hidegitlink=true` URL parameter
    * Shortcodes: H5P, PDF, Google Slides, iFrame, Embedly, Markdown File, Announcement
    * Helios-inspired Admin Panel styling enhancements
    * Admin Panel font size options (Large, Larger)
    * GitHub and Codeberg integration support
    * Grav 1.7 and Grav 2.0 compatible
    * Helios theme fallback to Quark or Quark2 if Helios is not installed
