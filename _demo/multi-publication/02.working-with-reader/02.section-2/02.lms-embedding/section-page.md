---
title: 'LMS Embedding'
description: 'Embedding reader pages in Canvas, Moodle, or Brightspace using URL parameters.'
---

Append `?embedded=true` to any page URL to display only the page content – no sidebar, header, or pagination. Designed for embedding individual Helios Open Reader pages in an LMS iframe (Canvas, Moodle, Brightspace, etc.).

- All internal links automatically carry the `?embedded=true` parameter forward, so navigating between pages stays in embedded mode
- `?chromeless=true` is also supported as an alternative parameter name
- Combine with `?toc_position=hidden` to also hide the Table of Contents
- Combine with `?toc_position=left` or `?toc_position=right` to reposition the Table of Contents to suit surrounding LMS navigation
- Combine with `?edit_link=false` (or `?hidegitlink=true`) to hide the "Edit this Page" link

**Example iframe:**

```html
<iframe src="https://yoursite.com/section-1/introduction?embedded=true" width="100%" height="600" style="border:none;"></iframe>
```

[example title="Canvas: Use External Tool for Best Accessibility"]
In Canvas, use the **External Tool** option rather than a raw iFrame embed for the best accessibility and mobile experience. If your institution hasn't enabled external tool embedding, a standard iFrame in a Page editor still works for most use cases.
[/example]

> [!TIP]
> Combine `?embedded=true` with `?toc_position=left` or `?toc_position=right` to integrate the Table of Contents with the surrounding LMS navigation layout.
