---
title: 'Plugin Settings'
---

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
| Enable Plain Text Version | Disabled | Generate `/llms.txt` (structured index) and `/llms-full.txt` (full content) endpoints containing all reader content in plain text |
| Show Plain Text Version Link in Footer | Enabled | Show a link to `/llms-full.txt` in the page footer. Only applies when Enable Plain Text Version is enabled |
| Plain Text Version Link Label | `Plain text version` | Label for the plain text version footer link |
| Include Page Templates | `section-page` | Only pages using these templates appear in the plain text version |

> **Note:** To apply the Helios-inspired Admin Panel colour scheme (zinc nav, accessible blue links, muted purple accents), go to **Admin → Customization → Presets**, select **Helios**, and click **Save**.
