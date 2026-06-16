---
title: 'Installation Steps'
description: 'Step-by-step instructions for getting Helios Open Reader running on your web server.'
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

> [!TIP]
> If changes don't appear immediately after publishing pages or updating settings, clear the Grav cache via the **Clear Cache** button in the Admin panel.

[announcement title="Next Steps After Installation"]
Once the reader is running, replace the demo content with your own:

1. Open **Admin → Pages → Reader Home** and update the title, subtitle, and authors
2. Rename or replace the demo sections under `user/pages/`
3. Update section names in **Admin → Themes → Helios → Versioning → Version Labels**
[/announcement]
