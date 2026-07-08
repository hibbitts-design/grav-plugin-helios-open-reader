---
title: 'Installation Steps'
---

1. **Download** the [Grav Helios Open Reader Skeleton](https://github.com/hibbitts-design/grav-skeleton-helios-open-reader/releases/latest) package
2. **Unzip** the package onto your desktop
3. **Copy** the entire Grav Helios Open Reader folder to your web server (e.g. into `public_html/` or a subfolder within it)
4. **Open your browser** and go to your site's URL (e.g. `https://yoursite.com/grav-open-reader`)
5. **Create your site administrator account** when prompted
6. **Enter your Helios and SVG Icons license keys** (or import an existing license file), then install and activate the theme
7. **You're done!** – press the preview icon in the Admin Panel to view your site

> [!TIP]
> When copying the Grav Helios Open Reader folder to your web server, copy the **entire folder** – it contains hidden files (such as `.htaccess`) that are not selected by default. Omitting these hidden files can cause problems when running Grav.

[announcement title="Next Steps After Installation"]
Once the reader is running, replace the demo content with your own:

1. Open **Admin → Pages → Reader Home** and update the title, subtitle, and authors for each publication
2. Rename or replace the demo sections under `user/pages/`
3. In single-publication mode, update section names in **Admin → Themes → Helios → Versioning → Version Labels** (not needed in multi-publication mode, where section names come from each page's `title` field)
[/announcement]
