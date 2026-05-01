<?php

// Developed with the assistance of Claude Code (claude.ai)

namespace Grav\Plugin;

use Grav\Common\Plugin;

class HeliosOpenReaderPlugin extends Plugin
{
    /** @var bool Whether the Helios theme is missing or inactive */
    protected $themeMissing = false;

    /** @var string|null Computed "Reader Title | Page Title | Site Title" browser title */
    protected $browserTitle = null;

    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    public function onPluginsInitialized()
    {
        // Check theme folder and active status directly, as admin may have switched to Quark/Quark2
        $themeName   = 'helios';
        $themePath   = GRAV_ROOT . '/user/themes/' . $themeName;
        $themeActive = $this->config->get('system.pages.theme') === $themeName;

        if (!is_dir($themePath) || !$themeActive) {
            $fallback = is_dir(GRAV_ROOT . '/user/themes/quark2') ? 'quark2' : 'quark';
            $this->config->set('system.pages.theme', $fallback);
            $this->themeMissing = true;
        }

        // Register page blueprints in every context so they are discoverable
        // from admin, frontend, CLI, and API requests alike.
        $this->enable([
            'onGetPageBlueprints' => ['onGetPageBlueprints', 0],
        ]);

        if ($this->isAdmin2Route()) {
            $this->enable([
                'onPagesInitialized' => ['onPagesInitializedAdmin2', 1001],
            ]);
            return;
        }

        if ($this->isAdmin()) {
            $this->enable([
                'onPageInitialized' => ['onPageInitialized', 0],
                'onOutputGenerated' => ['onOutputGenerated', 0],
            ]);
            return;
        }

        $this->enable([
            'onThemeInitialized'  => ['onThemeInitialized', -1000],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', -100],
            'onOutputGenerated'   => ['onOutputGenerated', 0],
            'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
        ]);
    }

    protected function getSectionLabel(): string
    {
        return $this->grav['language']->translate('PLUGIN_HELIOS_OPEN_READER.SECTION_LABEL');
    }

    public function onThemeInitialized(): void
    {
        $lang          = $this->grav['language'];
        $activeLang    = $lang->getLanguage() ?: 'en';
        $sectionLabel  = $this->getSectionLabel();
        $latestLabel   = $lang->translate('PLUGIN_HELIOS_OPEN_READER.SECTION_LATEST_LABEL');

        $this->grav['languages']->mergeRecursive([
            $activeLang => [
                'THEME_HELIOS' => [
                    'VERSION'        => $sectionLabel,
                    'VERSION_LATEST' => $latestLabel,
                ],
            ],
        ]);
    }

    private function isAdmin2Route(): bool
    {
        if (!$this->config->get('plugins.admin2.enabled', false)) {
            return false;
        }
        $route = $this->config->get('plugins.admin2.route', '');
        if (!$route) {
            return false;
        }
        $base    = '/' . trim($route, '/');
        $current = $this->grav['uri']->route();
        return $current === $base || str_starts_with($current, $base . '/');
    }

    public function onPagesInitializedAdmin2(): void
    {
        $fontSize = $this->config->get('plugins.helios-open-reader.admin_font_size', 'large');
        if ($fontSize === 'default') {
            return;
        }
        $cssFile = __DIR__ . "/assets/admin-fonts-{$fontSize}.css";
        if (!file_exists($cssFile)) {
            return;
        }
        $css = file_get_contents($cssFile);
        ob_start(function (string $html) use ($css): string {
            if (strpos($html, 'data-sveltekit-preload-data') === false) {
                return $html;
            }
            return str_replace('</head>', '<style>' . $css . '</style></head>', $html);
        });
    }

    public function onPageInitialized()
    {
        $assets = $this->grav['assets'];
        $path   = 'plugin://helios-open-reader/assets';

        if ($this->config->get('plugins.helios-open-reader.admin_styling_enhancements', true)) {
            $assets->addCss("$path/admin.css");
        }

        $assets->addJs("$path/admin.js");

        $this->injectHeliosPreset();
        $this->injectLoginCss();

        if ($this->themeMissing) {
            $heliosLicense = \Grav\Common\GPM\Licenses::get('helios');
            $targetRoute   = $heliosLicense ? '/admin/themes' : '/admin/license-manager';
            $currentRoute  = $this->grav['uri']->path();
            $isLoggedIn    = $this->grav['user']->authenticated ?? false;

            $this->grav['messages']->add(
                "Helios Grav Premium theme required. Enter your Helios and SVG Icons license keys, then install and activate the theme. (Helios Open Reader Plugin)",
                'warning'
            );

            if ($isLoggedIn && $currentRoute === '/admin') {
                $this->grav->redirect($targetRoute);
                return;
            }
        }
    }

    protected function injectHeliosPreset()
    {
        $existing = $this->config->get('plugins.admin.whitelabel.custom_presets');
        if (!empty($existing)) {
            return;
        }
        $preset = file_get_contents(__DIR__ . '/helios-preset.yaml');
        $this->config->set('plugins.admin.whitelabel.custom_presets', $preset);
    }

    protected function injectLoginCss()
    {
        $existing = $this->config->get('plugins.admin.whitelabel.custom_css');
        if (!empty($existing)) {
            return;
        }
        $this->config->set(
            'plugins.admin.whitelabel.custom_css',
            '#admin-login h1 svg path:first-child { fill: rgba(255, 255, 255, 0.10); }'
        );
    }

    public function onGetPageBlueprints($event)
    {
        $types = $event->types;
        $types->scanBlueprints('plugin://helios-open-reader/blueprints');
    }

    public function onTwigTemplatePaths()
    {
        if ($this->themeMissing) {
            return;
        }
        $twig = $this->grav['twig'];
        array_unshift($twig->twig_paths, __DIR__ . '/templates');
    }

    public function onShortcodeHandlers()
    {
        $shortcodes = $this->grav['shortcode'];
        $dir        = __DIR__ . '/shortcodes';

        // Register only .php files to avoid processing .DS_Store and similar
        foreach (new \DirectoryIterator($dir) as $file) {
            if ($file->isDot() || $file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }
            $shortcodes->registerShortcode($file->getFilename(), $dir);
        }
    }

    public function onTwigSiteVariables()
    {
        if ($this->themeMissing) {
            return;
        }

        $assets = $this->grav['assets'];
        $path   = 'plugin://helios-open-reader/assets';

        $assets->addCss("$path/helios.css");
        $assets->addCss("$path/reader.css");
        $assets->addCss("$path/print.css", ['media' => 'print']);
        $assets->addJs("$path/helios.js", ['group' => 'bottom', 'loading' => 'defer']);

        $twig = $this->grav['twig'];

        // Integration settings
        $twig->twig_vars['github_server']           = $this->config->get('plugins.helios-open-reader.github_server', 'github.com');
        $twig->twig_vars['github_link_icon']        = $this->config->get('plugins.helios-open-reader.github_link_icon', 'tabler/file-text.svg');
        $twig->twig_vars['github_link_mode']        = $this->config->get('plugins.helios-open-reader.github_link_mode', 'view');
        $twig->twig_vars['show_github_header_icon'] = $this->config->get('plugins.helios-open-reader.show_github_header_icon', true);
        $twig->twig_vars['show_site_icon']          = $this->config->get('plugins.helios-open-reader.show_site_icon', true);
        $twig->twig_vars['site_icon']               = $this->config->get('plugins.helios-open-reader.site_icon', '');
        $twig->twig_vars['show_plugin_credits']     = $this->config->get('plugins.helios-open-reader.show_plugin_credits', true);
        $twig->twig_vars['helios_base_simple']      = 'partials/base-simple.html.twig';

        // Default logo URL to site root
        $twig->twig_vars['logo_url'] = $this->grav['base_url'] ?: '/';

        // URL parameter handling
        $uri = $this->grav['uri'];
        $twig->twig_vars['chromeless']    = (bool) $uri->query('embedded') || (bool) $uri->query('chromeless');
        $tocParam                          = $uri->query('toc_position') ?: $uri->query('toc') ?: null;
        $twig->twig_vars['toc_url_param'] = ($tocParam !== null && $tocParam !== false) ? $tocParam : null;
        $twig->twig_vars['hide_git_link'] = $uri->query('edit_link') === 'false' || $uri->query('hidegitlink') === 'true';

        // OER attribution toggle
        $twig->twig_vars['show_oer_attribution'] = $this->config->get('plugins.helios-open-reader.show_oer_attribution', false);

        // Section label (configurable; falls back to language default)
        $twig->twig_vars['section_label'] = $this->getSectionLabel();

        // Prev/Next position: top | bottom | both
        $twig->twig_vars['hor_prev_next_position'] = $this->config->get('plugins.helios-open-reader.prev_next_position', 'both');

        // Find the reader home page to pull attribution fields, logo URL, and favicon.
        // Sections are top-level siblings of the reader home (same structure as
        // Course Hub courses), so ancestor walking alone won't reach it from section pages.
        // Strategy: walk up first (handles the reader home page itself), then fall back to
        // scanning root-level children for the first page using the 'reader' template.
        $page       = $this->grav['page'];
        $readerHome = null;

        // Pass 1 — ancestor walk (catches the reader home page viewing itself)
        $candidate = $page;
        while ($candidate) {
            if ($candidate->template() === 'reader') {
                $readerHome = $candidate;
                break;
            }
            $candidate = $candidate->parent();
        }

        // Pass 2 — root-level scan (catches section pages)
        if (!$readerHome) {
            $root = $this->grav['pages']->root();
            foreach ($root->children() as $child) {
                if ($child->template() === 'reader') {
                    $readerHome = $child;
                    break;
                }
            }
        }

        if ($readerHome) {
            $twig->twig_vars['reader_title']       = $readerHome->title();
            $twig->twig_vars['reader_authors']     = $readerHome->header()->authors ?? '';
            $twig->twig_vars['reader_edition']     = $readerHome->header()->edition ?? '';
            $twig->twig_vars['reader_license']     = $readerHome->header()->license ?? '';
            $twig->twig_vars['reader_license_url'] = $readerHome->header()->license_url ?? '';
            $twig->twig_vars['reader_attribution'] = $readerHome->header()->attribution_text ?? '';

            // Section label: reader home page frontmatter overrides the language default
            $pageLabel = trim((string) ($readerHome->header()->section_label ?? ''));
            if ($pageLabel !== '') {
                $twig->twig_vars['section_label'] = $pageLabel;
                $lang       = $this->grav['language'];
                $activeLang = $lang->getLanguage() ?: 'en';
                $this->grav['languages']->mergeRecursive([
                    $activeLang => ['THEME_HELIOS' => ['VERSION' => $pageLabel]],
                ]);
            }

            // Point logo to the reader home page
            $twig->twig_vars['logo_url'] = $readerHome->url();

            // Build "Reader Title | Page Title | Site Title" for non-home pages
            if ($page->template() !== 'reader') {
                $readerTitle = $readerHome->title();
                $pageTitle = $page->title();
                $siteTitle = $this->grav['config']->get('site.title', '');
                if ($readerTitle && $pageTitle && $siteTitle) {
                    $this->browserTitle = $pageTitle . ' | ' . $readerTitle . ' | ' . $siteTitle;
                }
            }
        } else {
            $twig->twig_vars['reader_title']       = '';
            $twig->twig_vars['reader_authors']     = '';
            $twig->twig_vars['reader_edition']     = '';
            $twig->twig_vars['reader_license']     = '';
            $twig->twig_vars['reader_license_url'] = '';
            $twig->twig_vars['reader_attribution'] = '';
        }

        // Filter helios_version_info to remove unpublished parts from the dropdown.
        // Runs at priority -100 so the theme has already populated this variable.
        if (isset($twig->twig_vars['helios_version_info'])) {
            $pages       = $this->grav['pages'];
            $versionInfo = $twig->twig_vars['helios_version_info'];

            $filteredVersions = array_values(array_filter(
                $versionInfo['versions'],
                function ($version) use ($pages) {
                    $versionId = is_array($version) ? ($version['id'] ?? null) : ($version->id ?? null);
                    if (!$versionId) {
                        return true;
                    }
                    $versionPage = $pages->find('/' . $versionId);
                    if (!$versionPage) {
                        return true;
                    }
                    return $versionPage->published();
                }
            ));

            $versionInfo['versions'] = $filteredVersions;
            $versionInfo['count']    = count($filteredVersions);
            $twig->twig_vars['helios_version_info'] = $versionInfo;
        }

        // Section sidebar image — shown as a banner above the nav when show_sidebar_image is set.
        // section_home_url — always the section root URL, used for the sidebar label link.
        // (helios_version_info version.url is the version-switcher URL, not the root URL.)
        $twig->twig_vars['section_sidebar_image']    = null;
        $twig->twig_vars['section_sidebar_image_url'] = null;
        $twig->twig_vars['section_home_url']          = null;
        if (isset($twig->twig_vars['helios_version_info'])) {
            foreach ($twig->twig_vars['helios_version_info']['versions'] as $version) {
                $isCurrent = is_array($version) ? ($version['is_current'] ?? false) : ($version->is_current ?? false);
                if ($isCurrent) {
                    $versionId = is_array($version) ? ($version['id'] ?? null) : ($version->id ?? null);
                    if ($versionId) {
                        $sectionPage = $this->grav['pages']->find('/' . $versionId);
                        if ($sectionPage) {
                            $twig->twig_vars['section_home_url'] = $sectionPage->url();
                            if ($sectionPage->header()->show_sidebar_image ?? false) {
                                $imageFile = $sectionPage->header()->image ?? null;
                                if ($imageFile) {
                                    $medium = $sectionPage->media()->get($imageFile);
                                    if ($medium) {
                                        $twig->twig_vars['section_sidebar_image']     = $medium->url();
                                        $twig->twig_vars['section_sidebar_image_url'] = $sectionPage->url();
                                    }
                                }
                            }
                        }
                    }
                    break;
                }
            }
        }

        // Cross-section Prev/Next: bridge navigation across section boundaries.
        // Runs after Helios has set helios_prev/helios_next (priority 0 vs -100).
        $this->injectCrossSectionNavigation($twig, $page);

        // Section reading progress (X of Y).
        $this->injectSectionProgress($twig, $page);
    }

    /**
     * Fill in cross-section Prev/Next links for section-page templates.
     *
     * Three cases:
     *   Next — helios_next is null (last sub-page of a section): set to the next section root page.
     *   Prev A — helios_prev is null (section root page): set to last content page of previous section.
     *   Prev B — helios_prev points to the parent section root (first sub-page): replace with last
     *            content page of the previous section.
     */
    protected function injectCrossSectionNavigation($twig, $page): void
    {
        if (!$page || $page->template() !== 'section-page') {
            return;
        }

        $versionInfo = $twig->twig_vars['helios_version_info'] ?? null;
        if (!$versionInfo) {
            return;
        }

        $versions = $versionInfo['versions'] ?? [];
        if (empty($versions)) {
            return;
        }

        $versionIds = array_values(array_map(
            fn($v) => is_array($v) ? ($v['id'] ?? '') : ($v->id ?? ''),
            $versions
        ));

        // Current section = first path segment of the page route (e.g. "section-1")
        $routeSegments    = explode('/', trim($page->route(), '/'));
        $currentSectionId = $routeSegments[0] ?? '';
        $currentIndex     = array_search($currentSectionId, $versionIds, true);

        if ($currentIndex === false) {
            return;
        }

        $pages = $this->grav['pages'];

        // --- Next: last sub-page of a section → next section root page ---
        if ($twig->twig_vars['helios_next'] === null && $currentIndex < count($versionIds) - 1) {
            $nextSection = $pages->find('/' . $versionIds[$currentIndex + 1]);
            if ($nextSection) {
                $twig->twig_vars['helios_next'] = [
                    'title' => $nextSection->title(),
                    'url'   => $nextSection->url(),
                ];
            }
        }

        // --- Prev: section root page → last content page of previous section ---
        // Helios scopes prev/next within the current version, so helios_prev is null
        // on every section root page. A single-segment route (e.g. /section-2) means
        // this IS the section root. ($currentIndex !== false already confirms it's a version.)
        $parentPage = $page->parent();
        $pageIsSectionRoot = count($routeSegments) === 1;

        if ($twig->twig_vars['helios_prev'] === null && $pageIsSectionRoot && $currentIndex > 0) {
            $prevSection = $pages->find('/' . $versionIds[$currentIndex - 1]);
            if ($prevSection) {
                $flatList = [];
                $this->collectPagesDepthFirst($prevSection, $flatList);
                if (!empty($flatList)) {
                    $lastPage = end($flatList);
                    $twig->twig_vars['helios_prev'] = [
                        'title' => $lastPage->title(),
                        'url'   => $lastPage->url(),
                    ];
                }
            }
        }

        // --- Prev: first sub-page of a section → last content page of previous section ---
        // Triggered when helios_prev points to the parent section root page, which happens
        // for the first direct child of a top-level section.
        $prevData = $twig->twig_vars['helios_prev'];
        $prevUrl  = is_array($prevData) ? ($prevData['url'] ?? null) : null;

        // Parent is a top-level section page when its own route is one of the version IDs.
        // Avoids relying on the Grav root page's route() value which varies by environment.
        $parentIsTopLevel = $parentPage
            && in_array(trim($parentPage->route(), '/'), $versionIds, true);

        if ($prevUrl
            && $parentPage
            && $parentIsTopLevel
            && $parentPage->url() === $prevUrl
            && $currentIndex > 0
        ) {
            $prevSection = $pages->find('/' . $versionIds[$currentIndex - 1]);
            if ($prevSection) {
                $flatList = [];
                $this->collectPagesDepthFirst($prevSection, $flatList);
                if (!empty($flatList)) {
                    $lastPage = end($flatList);
                    $twig->twig_vars['helios_prev'] = [
                        'title' => $lastPage->title(),
                        'url'   => $lastPage->url(),
                    ];
                }
            }
        }
    }

    /**
     * Inject section_progress_current and section_progress_total Twig vars.
     * Counts all visible, routable section-page templates across all parts.
     */
    protected function injectSectionProgress($twig, $page): void
    {
        if (!$page || $page->template() !== 'section-page') {
            return;
        }

        $allPages = [];
        $root = $this->grav['pages']->root();
        foreach ($root->children()->visible() as $topLevel) {
            $this->collectPagesDepthFirst($topLevel, $allPages);
        }

        $sections = array_values(array_filter(
            $allPages,
            fn($p) => $p->template() === 'section-page'
        ));

        if (empty($sections)) {
            return;
        }

        $currentUrl = $page->url();
        $currentIndex = null;
        foreach ($sections as $i => $s) {
            if ($s->url() === $currentUrl) {
                $currentIndex = $i;
                break;
            }
        }

        if ($currentIndex === null) {
            return;
        }

        $twig->twig_vars['section_progress_current'] = $currentIndex + 1;
        $twig->twig_vars['section_progress_total']   = count($sections);
    }

    /**
     * Recursively collect visible, routable pages in depth-first order.
     */
    protected function collectPagesDepthFirst($page, array &$list): void
    {
        if ($page->visible() && $page->routable()) {
            $list[] = $page;
        }
        foreach ($page->children()->visible() as $child) {
            $this->collectPagesDepthFirst($child, $list);
        }
    }

    public function onOutputGenerated($event)
    {
        if ($this->isAdmin()) {
            $fontSize = $this->config->get('plugins.helios-open-reader.admin_font_size', 'large');
            if ($fontSize !== 'default') {
                $cssFile = __DIR__ . "/assets/admin-fonts-{$fontSize}.css";
                if (file_exists($cssFile)) {
                    $css             = file_get_contents($cssFile);
                    $event['output'] = str_replace('</head>', '<style>' . $css . '</style></head>', $event['output']);
                }
            }
        }

        if ($this->browserTitle !== null) {
            $event['output'] = preg_replace(
                '~<title>[^<]*</title>~',
                '<title>' . htmlspecialchars($this->browserTitle, ENT_QUOTES, 'UTF-8') . '</title>',
                $event['output'],
                1
            );
        }

    }
}
