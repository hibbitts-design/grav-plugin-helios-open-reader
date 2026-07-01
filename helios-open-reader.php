<?php

// Developed with the assistance of Claude Code (claude.ai)

namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

class HeliosOpenReaderPlugin extends Plugin
{
    /** @var bool Whether the Helios theme is missing or inactive */
    protected $themeMissing       = false;
    protected $themeInstalledOnly = false;

    protected $shortcodesRegistered = false;

    /** @var string|null "Page Title | Reader Title | Site Title" for browser tab */
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

        $themeInstalled = is_dir($themePath);

        if (!$themeInstalled || !$themeActive) {
            $fallback = is_dir(GRAV_ROOT . '/user/themes/quark2') ? 'quark2' : 'quark';
            $this->config->set('system.pages.theme', $fallback);
            $this->themeMissing        = true;
            $this->themeInstalledOnly  = $themeInstalled && !$themeActive;
        }

        // Register blueprints globally so they're discoverable from admin, frontend, and API contexts.
        $this->enable([
            'onGetPageBlueprints'         => ['onGetPageBlueprints', 0],
            'onApiDashboardNotifications' => ['onApiDashboardNotifications', 0],
        ]);

        if ($this->isAdmin2Route()) {
            $this->enable([
                'onPagesInitialized' => ['onPagesInitializedAdmin2', 1001],
            ]);
            return;
        }

        if ($this->isAdmin()) {
            $this->enable([
                'onAdminTwigTemplatePaths' => ['onAdminHeliosNotice', 0],
                'onPageInitialized'        => ['onPageInitialized', 0],
                'onOutputGenerated'        => ['onOutputGenerated', 0],
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

        if ($this->config->get('plugins.helios-open-reader.plain_text_export_enabled', false)) {
            $this->enable([
                'onPagesInitialized' => ['onLlmsRoute', 0],
            ]);
        }
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
        $css = '';

        if ($this->config->get('plugins.helios-open-reader.admin_label_alignment', true)) {
            $labelCssFile = __DIR__ . '/assets/admin-label-alignment.css';
            if (file_exists($labelCssFile)) {
                $css .= file_get_contents($labelCssFile);
            }
        }

        if ($css === '') {
            return;
        }

        ob_start(function (string $html) use ($css): string {
            if (strpos($html, 'data-sveltekit') === false && strpos($html, '</body>') === false) {
                return $html;
            }
            return str_replace('</head>', '<style>' . $css . '</style></head>', $html);
        });
    }

    protected function themeNoticeKey(): string
    {
        return $this->themeInstalledOnly
            ? 'PLUGIN_HELIOS_OPEN_READER.THEME_INACTIVE_NOTICE'
            : 'PLUGIN_HELIOS_OPEN_READER.THEME_REQUIRED_NOTICE';
    }

    public function onAdminHeliosNotice(): void
    {
        if (!$this->themeMissing) {
            return;
        }

        $this->grav['messages']->add(
            $this->grav['language']->translate($this->themeNoticeKey()),
            'warning'
        );
    }

    public function onApiDashboardNotifications(Event $event): void
    {
        if (!$this->themeMissing) {
            return;
        }

        $notifications = $event['notifications'] ?? [];
        $notifications['top'][] = [
            'id'             => 'helios-open-reader-theme-required',
            'date'           => date('c'),
            'level'          => 'warning',
            'icon'           => 'shield-alert',
            'location'       => ['top'],
            'message'        => $this->grav['language']->translate($this->themeNoticeKey()),
            'reappear_after' => '+1 days',
        ];
        $event['notifications'] = $notifications;
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
        if ($this->shortcodesRegistered) {
            return;
        }
        $this->shortcodesRegistered = true;

        $shortcodes = $this->grav['shortcode'];
        $dir        = __DIR__ . '/shortcodes';

        // Register only .php files to avoid processing .DS_Store and similar
        foreach (new \DirectoryIterator($dir) as $file) {
            if ($file->isDot() || $file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }
            try {
                $shortcodes->registerShortcode($file->getFilename(), $dir);
            } catch (\RuntimeException $e) {
                // Handler already registered by another plugin (e.g. helios-course-hub)
            }
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
        $assets->addCss("$path/section-list.css");
        $assets->addCss("$path/print.css", ['media' => 'print']);
        $assets->addJs("$path/helios.js", ['group' => 'bottom', 'loading' => 'defer']);


        $twig = $this->grav['twig'];

        // Integration settings
        $twig->twig_vars['github_server']             = $this->config->get('plugins.helios-open-reader.github_server', 'github.com');
        $twig->twig_vars['github_link_icon']          = $this->config->get('plugins.helios-open-reader.github_link_icon', 'tabler/file-text.svg');
        $twig->twig_vars['github_link_mode']          = $this->config->get('plugins.helios-open-reader.github_link_mode', 'view');
        $twig->twig_vars['show_github_header_icon']    = $this->config->get('plugins.helios-open-reader.show_github_header_icon', true);
        $rawCustomUrl = trim($this->config->get('plugins.helios-open-reader.github_header_custom_url', ''));
        $twig->twig_vars['github_header_custom_url']  = preg_match('#^https?://#i', $rawCustomUrl) ? $rawCustomUrl : '';
        $twig->twig_vars['show_site_icon']            = $this->config->get('plugins.helios-open-reader.show_site_icon', true);
        $twig->twig_vars['plain_text_export_enabled'] = $this->config->get('plugins.helios-open-reader.plain_text_export_enabled', false);
        $twig->twig_vars['show_plain_text_link']      = $this->config->get('plugins.helios-open-reader.show_plain_text_link', true);
        $twig->twig_vars['plain_text_link_label']     = $this->config->get('plugins.helios-open-reader.plain_text_link_label', 'Plain text version (llms-full.txt)');
        $twig->twig_vars['plain_text_link_icon']      = $this->config->get('plugins.helios-open-reader.plain_text_link_icon', 'tabler/book.svg');
        $twig->twig_vars['site_icon']                 = $this->config->get('plugins.helios-open-reader.site_icon', '');
        $twig->twig_vars['show_plugin_credits']       = $this->config->get('plugins.helios-open-reader.show_plugin_credits', true);
        $twig->twig_vars['helios_base_simple']        = 'partials/base-simple.html.twig';

        $twig->twig_vars['logo_url'] = $this->grav['base_url'] ?: '/';

        // URL parameter handling
        $uri = $this->grav['uri'];
        $twig->twig_vars['chromeless']    = (bool) $uri->query('embedded') || (bool) $uri->query('chromeless');
        $tocParam                          = $uri->query('toc_position') ?: $uri->query('toc') ?: null;
        $twig->twig_vars['toc_url_param'] = ($tocParam !== null && $tocParam !== false) ? $tocParam : null;
        $twig->twig_vars['hide_git_link'] = $uri->query('edit_link') === 'false' || $uri->query('hidegitlink') === 'true';

        $twig->twig_vars['section_label'] = $this->getSectionLabel();

        // OER attribution and Prev/Next defaults — overridden below from reader home frontmatter.
        $twig->twig_vars['show_oer_attribution']   = false;
        $twig->twig_vars['hor_prev_next_position'] = 'both';

        // Find the reader home page to pull attribution fields, logo URL, and favicon.
        // Strategy: pre-scan root for reader-list (sets multi-publication mode before
        // the ancestor walk), then walk ancestors to find the reader/publication home.
        $page                = $this->grav['page'];
        $readerHome          = null;
        $isMultiPublication  = false;
        $isNestedPublication = false;
        $publicationListPage = null;

        // Pre-scan root for reader-list first — must happen before the ancestor walk so
        // section-list pages in the walk are correctly identified as nested publication
        // homes (multi-pub) vs. single-publication reader homes.
        $root = $this->grav['pages']->root();
        foreach ($root->children() as $child) {
            if ($child->template() === 'reader-list') {
                $isMultiPublication  = true;
                $publicationListPage = $child;
                break;
            }
        }

        // Ancestor walk: finds the reader home and detects nested publication context.
        $candidate = $page;
        while ($candidate) {
            $tmpl = $candidate->template();
            if ($tmpl === 'section-list') {
                $readerHome = $candidate;
                if ($isMultiPublication) {
                    $isNestedPublication = true;
                }
                break;
            }
            if ($tmpl === 'reader-list') {
                $readerHome = $candidate;
                break;
            }
            $candidate = $candidate->parent();
        }

        // Fallback: ancestor walk found nothing — use the reader-list page,
        // or scan root children for a section-list (single-publication mode).
        if (!$readerHome) {
            if ($publicationListPage) {
                $readerHome = $publicationListPage;
            } else {
                foreach ($root->children() as $child) {
                    if ($child->template() === 'section-list') {
                        $readerHome = $child;
                        break;
                    }
                }
            }
        }

        // Base path for page lookups: empty for flat/root-level sections, publication route for nested.
        $nestedTmpl          = $readerHome ? $readerHome->template() : '';
        $publicationBasePath = ($isNestedPublication && $nestedTmpl === 'section-list')
            ? $readerHome->route()
            : '';

        $twig->twig_vars['is_multi_publication']   = $isMultiPublication;
        $twig->twig_vars['is_nested_publication']  = $isNestedPublication;
        $twig->twig_vars['publication_base_path']  = $publicationBasePath;
        $twig->twig_vars['reader_list_url']        = $isMultiPublication
            ? ($publicationListPage ? $publicationListPage->url() : ($readerHome ? $readerHome->url() : null))
            : null;

        // Collect publication home pages for the reader-list template and sidebar links.
        $publicationPages = [];
        if ($isMultiPublication) {
            foreach ($root->children() as $child) {
                if ($child->template() === 'section-list' && $child->visible()) {
                    $publicationPages[] = $child;
                }
            }

            if ($isNestedPublication && $readerHome && $readerHome->template() === 'section-list') {
                // Nested mode: set Back to Book Home link only when not already on the book home.
                if ($page->url() !== $readerHome->url()) {
                    $twig->twig_vars['publication_home_url']   = $readerHome->url();
                    $twig->twig_vars['publication_home_title'] = $readerHome->title();
                }
                $partKey = trim((string) ($readerHome->header()->part_key ?? ''));
                if ($partKey !== '') {
                    $twig->twig_vars['current_publication_part'] = $partKey;
                }
            } elseif (in_array($page->template(), ['section', 'section-page'], true)) {
                // Flat mode: detect part from the section page route prefix.
                $routeSegs   = explode('/', trim($page->route(), '/'));
                $currentPart = $this->extractPartPrefix($routeSegs[0] ?? '');
                if ($currentPart) {
                    $twig->twig_vars['current_publication_part'] = $currentPart;
                    foreach ($publicationPages as $publicationPage) {
                        if (($publicationPage->header()->part_key ?? null) === $currentPart) {
                            $twig->twig_vars['publication_home_url']   = $publicationPage->url();
                            $twig->twig_vars['publication_home_title'] = $publicationPage->title();
                            break;
                        }
                    }
                }
            }
        }
        $twig->twig_vars['publication_pages'] = $publicationPages;

        if ($readerHome) {
            // In nested mode readerHome is the book home, which may not carry global settings
            // like section_label or OER attribution. Fall back to the book-list page for those.
            $settingsFallback = ($isNestedPublication && $publicationListPage) ? $publicationListPage : null;

            $twig->twig_vars['reader_title']       = $readerHome->title();
            $twig->twig_vars['reader_authors']     = $this->headerFallback($readerHome, $settingsFallback, 'authors');
            $twig->twig_vars['reader_edition']     = $this->headerFallback($readerHome, $settingsFallback, 'edition');
            $twig->twig_vars['reader_license']     = $this->headerFallback($readerHome, $settingsFallback, 'license');
            $twig->twig_vars['reader_license_url'] = $this->headerFallback($readerHome, $settingsFallback, 'license_url');
            $twig->twig_vars['reader_attribution'] = $this->headerFallback($readerHome, $settingsFallback, 'attribution_text');

            $twig->twig_vars['show_oer_attribution']   = (bool) $this->headerFallback($readerHome, $settingsFallback, 'show_oer_attribution', false);
            $twig->twig_vars['hor_prev_next_position'] = (string) $this->headerFallback($readerHome, $settingsFallback, 'prev_next_position', 'both');
            $twig->twig_vars['hor_show_sticky_nav']    = (bool) $this->headerFallback($readerHome, $settingsFallback, 'show_sticky_nav', true);
            $twig->twig_vars['show_section_label']     = (bool) $this->headerFallback($readerHome, $settingsFallback, 'show_section_label', true);

            // Section label: reader home frontmatter overrides the language default,
            // falling back to book-list in nested mode.
            $pageLabel = trim((string) ($readerHome->header()->section_label ?? ''));
            if ($pageLabel === '' && $settingsFallback) {
                $pageLabel = trim((string) ($settingsFallback->header()->section_label ?? ''));
            }
            if ($pageLabel !== '') {
                $twig->twig_vars['section_label'] = $pageLabel;
                $lang       = $this->grav['language'];
                $activeLang = $lang->getLanguage() ?: 'en';
                $this->grav['languages']->mergeRecursive([
                    $activeLang => ['THEME_HELIOS' => ['VERSION' => $pageLabel]],
                ]);
            }

            // Logo target: readers list in nested multi-publication mode; otherwise the reader home.
            $logoTarget = ($isNestedPublication && $publicationListPage) ? $publicationListPage : $readerHome;

            // When logo_link_target is 'single_publication' and only one publication is active,
            // point the logo directly to that publication's home page, bypassing the readers list.
            $logoLinkTarget = $this->config->get('plugins.helios-open-reader.logo_link_target', 'single_publication');
            if ($logoLinkTarget === 'single_publication' && $isMultiPublication && count($publicationPages) === 1) {
                $logoTarget = $publicationPages[0];
            }

            $twig->twig_vars['logo_url'] = $logoTarget->url();

            // Build browser title for non-home section pages
            if ($page->template() !== 'section-list' && $page->template() !== 'reader-list') {
                $readerTitle = $readerHome->title();
                $pageTitle   = $page->title();
                $siteTitle   = $this->grav['config']->get('site.title', '');
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

        // Helios's versioning only scans root-level pages for version pattern matches.
        // Because section pages live inside publication folders (not at root), Helios finds
        // nothing and leaves doc_version_info unset — causing sidebar nav, cross-section
        // Prev/Next, and progress counter to bail out early. Build both doc_version_info
        // and nav_tree from the publication's section hierarchy instead.
        if ($isNestedPublication
            && $readerHome
            && $readerHome->template() === 'section-list'
            && in_array($page->template(), ['section', 'section-page'], true)
        ) {
            $nSegs        = explode('/', trim($page->route(), '/'));
            $nSectionSlug = $nSegs[1] ?? '';

            if ($nSectionSlug) {
                $nVersions = [];
                foreach ($readerHome->children()->visible() as $sp) {
                    $slug        = $sp->slug();
                    $nVersions[] = [
                        'id'         => $slug,
                        'label'      => $sp->title(),
                        'url'        => $sp->url(),
                        'is_current' => ($slug === $nSectionSlug),
                        'is_default' => false,
                    ];
                }
                if (!empty($nVersions)) {
                    $twig->twig_vars['doc_version_info'] = [
                        'versions'        => $nVersions,
                        'count'           => count($nVersions),
                        'current_version' => $nSectionSlug,
                    ];
                }
            }

            if ($nSectionSlug) {
                $nSectionRoot = $this->grav['pages']->find($publicationBasePath . '/' . $nSectionSlug);
                if ($nSectionRoot) {
                    $nCurrentUrl  = $page->url();
                    $nSubItems    = $this->buildNavTree($nSectionRoot->children()->visible(), $nCurrentUrl);
                    $nRootActive  = ($nSectionRoot->url() === $nCurrentUrl);
                    $twig->twig_vars['nav_tree'] = [[
                        'url'           => $nSectionRoot->url(),
                        'title'         => $nSectionRoot->title(),
                        'route'         => $nSectionRoot->route(),
                        'active'        => $nRootActive,
                        'parent_active' => !$nRootActive && $this->hasActiveDescendant($nSubItems),
                        'children'      => $nSubItems,
                        'icon'          => $nSectionRoot->header()->icon ?? null,
                        'api'           => [],
                    ]];

                    // Helios overrides doc_prev/doc_next with cross-version links when it sees
                    // doc_version_info. Set them from section page order instead;
                    // injectCrossSectionNavigation bridges the nulls at part boundaries.
                    $nAllPages = [];
                    $this->collectPagesDepthFirst($nSectionRoot, $nAllPages);
                    $nPos = null;
                    foreach ($nAllPages as $nIdx => $nP) {
                        if ($nP->url() === $nCurrentUrl) {
                            $nPos = $nIdx;
                            break;
                        }
                    }
                    if ($nPos !== null) {
                        $twig->twig_vars['doc_prev'] = ($nPos > 0) ? [
                            'title' => $nAllPages[$nPos - 1]->title(),
                            'url'   => $nAllPages[$nPos - 1]->url(),
                        ] : null;
                        $twig->twig_vars['doc_next'] = ($nPos < count($nAllPages) - 1) ? [
                            'title' => $nAllPages[$nPos + 1]->title(),
                            'url'   => $nAllPages[$nPos + 1]->url(),
                        ] : null;
                    }
                }
            }
        }

        // Filter doc_version_info to remove unpublished sections from the dropdown.
        if (isset($twig->twig_vars['doc_version_info'])) {
            $pages       = $this->grav['pages'];
            $versionInfo = $twig->twig_vars['doc_version_info'];

            $filteredVersions = array_values(array_filter(
                $versionInfo['versions'],
                function ($version) use ($pages, $publicationBasePath) {
                    $versionId = is_array($version) ? ($version['id'] ?? null) : ($version->id ?? null);
                    if (!$versionId) {
                        return true;
                    }
                    $versionPage = $pages->find($publicationBasePath . '/' . $versionId);
                    if (!$versionPage) {
                        return true;
                    }
                    return $versionPage->published();
                }
            ));

            $versionInfo['versions'] = $filteredVersions;
            $versionInfo['count']    = count($filteredVersions);
            $twig->twig_vars['doc_version_info'] = $versionInfo;
        }

        // Parts detection — optional feature using part-N-section-M folder naming.
        // If any version IDs match the pattern, group them by part and expose has_parts,
        // part_groups, and part_labels to Twig.
        $hasParts   = false;
        $partGroups = [];
        $partLabels = [];

        if (isset($twig->twig_vars['doc_version_info'])) {
            $versionsForParts = $twig->twig_vars['doc_version_info']['versions'];

            foreach ($versionsForParts as $v) {
                $vid = is_array($v) ? ($v['id'] ?? '') : ($v->id ?? '');
                if ($this->extractPartPrefix($vid) !== null) {
                    $hasParts = true;
                    break;
                }
            }

            if ($hasParts) {
                $rawPartLabel  = trim((string) ($readerHome ? ($readerHome->header()->part_label ?? '') : ''));
                $partLabelWord = ($rawPartLabel !== '') ? ucfirst($rawPartLabel) : 'Part';

                // Custom per-part titles from frontmatter:
                // parts:
                //   - id: part-1
                //     label: 'Foundations of Open Education'
                $customPartLabels = [];
                if ($readerHome) {
                    $partsHeader = $readerHome->header()->parts ?? [];
                    if (is_array($partsHeader)) {
                        foreach ($partsHeader as $item) {
                            $pid  = isset($item['id'])    ? strtolower(trim($item['id']))    : null;
                            $plbl = isset($item['label']) ? trim($item['label'])              : null;
                            if ($pid && $plbl) {
                                $customPartLabels[$pid] = $plbl;
                            }
                        }
                    }
                }

                foreach ($versionsForParts as $v) {
                    $vid        = is_array($v) ? ($v['id'] ?? '') : ($v->id ?? '');
                    $partPrefix = $this->extractPartPrefix($vid);
                    $key        = $partPrefix ?? '__ungrouped__';

                    if (!isset($partGroups[$key])) {
                        $partGroups[$key] = [];
                        if ($partPrefix) {
                            if (isset($customPartLabels[$partPrefix])) {
                                $partLabels[$partPrefix] = $customPartLabels[$partPrefix];
                            } else {
                                // Auto-label: "part-1" → "<PartLabel> 1" (e.g. "Part 1", "Theme 1")
                                preg_match('/^part-(\d+)$/i', $partPrefix, $nm);
                                $partNumber = $nm[1] ?? '';
                                $partLabels[$partPrefix] = $partLabelWord . ($partNumber !== '' ? ' ' . $partNumber : '');
                            }
                        }
                    }
                    $partGroups[$key][] = $v;
                }
            }
        }

        $twig->twig_vars['has_parts']   = $hasParts;
        $twig->twig_vars['part_groups'] = $partGroups;
        $twig->twig_vars['part_labels'] = $partLabels;

        // Section sidebar image — shown as a banner above the nav when show_sidebar_image is set.
        // section_home_url — always the section root URL, used for the sidebar label link.
        // (doc_version_info version.url is the version-switcher URL, not the root URL.)
        $twig->twig_vars['section_sidebar_image']     = null;
        $twig->twig_vars['section_sidebar_image_url'] = null;
        $twig->twig_vars['section_home_url']          = null;
        $twig->twig_vars['section_home_title']        = null;
        if (isset($twig->twig_vars['doc_version_info'])) {
            foreach ($twig->twig_vars['doc_version_info']['versions'] as $version) {
                $isCurrent = is_array($version) ? ($version['is_current'] ?? false) : ($version->is_current ?? false);
                if ($isCurrent) {
                    $versionId = is_array($version) ? ($version['id'] ?? null) : ($version->id ?? null);
                    if ($versionId) {
                        $sectionPage = $this->grav['pages']->find($publicationBasePath . '/' . $versionId);
                        if ($sectionPage) {
                            $twig->twig_vars['section_home_url']   = $sectionPage->url();
                            $twig->twig_vars['section_home_title'] = $sectionPage->title();
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

        $this->injectCrossSectionNavigation($twig, $page, $isNestedPublication, $publicationBasePath);
        $this->injectSectionProgress($twig, $page, $isNestedPublication, $isNestedPublication ? $readerHome : null);
    }

    /**
     * Read a header field from $primary, falling back to $fallback when the field is absent.
     * Used for reader home fields that may inherit from the book-list page in nested mode.
     */
    private function headerFallback($primary, $fallback, string $key, $default = '')
    {
        return $primary->header()->{$key} ?? ($fallback ? ($fallback->header()->{$key} ?? $default) : $default);
    }

    /**
     * Bridge Prev/Next links across section boundaries for section-page templates.
     *
     * Three cases:
     *   Next  — last page of a section: link to the next section root.
     *   Prev A — section root page: link to the last page of the previous section.
     *   Prev B — first sub-page of a section: replace the Helios-generated parent link
     *            with the last page of the previous section.
     */
    protected function injectCrossSectionNavigation($twig, $page, bool $isNestedPublication = false, string $publicationBasePath = ''): void
    {
        if (!$page || !in_array($page->template(), ['section', 'section-page'], true)) {
            return;
        }

        $versionInfo = $twig->twig_vars['doc_version_info'] ?? null;
        if (!$versionInfo || empty($versionInfo['versions'])) {
            return;
        }

        $versionIds = array_values(array_map(
            fn($v) => is_array($v) ? ($v['id'] ?? '') : ($v->id ?? ''),
            $versionInfo['versions']
        ));

        // In flat mode the section slug is the first route segment;
        // in nested mode the first segment is the book folder, so use the second.
        $routeSegments    = explode('/', trim($page->route(), '/'));
        $currentSectionId = $isNestedPublication ? ($routeSegments[1] ?? '') : ($routeSegments[0] ?? '');
        $currentIndex     = array_search($currentSectionId, $versionIds, true);

        if ($currentIndex === false) {
            return;
        }

        $currentPartPrefix = $this->extractPartPrefix($currentSectionId);

        // --- Next: last page of a section → next section root ---
        // Only cross into the next section when it belongs to the same part.
        if ($twig->twig_vars['doc_next'] === null && $currentIndex < count($versionIds) - 1) {
            $nextSectionId = $versionIds[$currentIndex + 1];
            if ($this->extractPartPrefix($nextSectionId) === $currentPartPrefix) {
                $nextSection = $this->grav['pages']->find($publicationBasePath . '/' . $nextSectionId);
                if ($nextSection) {
                    $twig->twig_vars['doc_next'] = [
                        'title' => $nextSection->title(),
                        'url'   => $nextSection->url(),
                    ];
                }
            }
        }

        // --- Prev A: section root page → last page of previous section ---
        $pageIsSectionRoot = count($routeSegments) === ($isNestedPublication ? 2 : 1);

        if ($twig->twig_vars['doc_prev'] === null && $pageIsSectionRoot && $currentIndex > 0) {
            $prevSectionId = $versionIds[$currentIndex - 1];
            if ($this->extractPartPrefix($prevSectionId) === $currentPartPrefix) {
                $lastPage = $this->lastPageOfSection($prevSectionId, $publicationBasePath);
                if ($lastPage) {
                    $twig->twig_vars['doc_prev'] = $lastPage;
                }
            }
        }

        // --- Prev B: first sub-page of a section → last page of previous section ---
        $prevData   = $twig->twig_vars['doc_prev'];
        $prevUrl    = is_array($prevData) ? ($prevData['url'] ?? null) : null;
        $parentPage = $page->parent();

        // In flat mode a section root's route matches a version ID (single segment).
        // In nested mode, check the basename of the parent's route.
        $parentIsTopLevel = $parentPage && (
            $isNestedPublication
                ? in_array(basename(trim($parentPage->route(), '/')), $versionIds, true)
                : in_array(trim($parentPage->route(), '/'), $versionIds, true)
        );

        if ($prevUrl && $parentPage && $parentIsTopLevel && $parentPage->url() === $prevUrl && $currentIndex > 0) {
            $prevSectionId = $versionIds[$currentIndex - 1];
            if ($this->extractPartPrefix($prevSectionId) === $currentPartPrefix) {
                $lastPage = $this->lastPageOfSection($prevSectionId, $publicationBasePath);
                if ($lastPage) {
                    $twig->twig_vars['doc_prev'] = $lastPage;
                }
            }
        }
    }

    /**
     * Returns [title, url] for the last visible page of the given section, or null.
     */
    private function lastPageOfSection(string $sectionId, string $publicationBasePath): ?array
    {
        $section = $this->grav['pages']->find($publicationBasePath . '/' . $sectionId);
        if (!$section) {
            return null;
        }
        $pages = [];
        $this->collectPagesDepthFirst($section, $pages);
        if (empty($pages)) {
            return null;
        }
        $last = end($pages);
        return ['title' => $last->title(), 'url' => $last->url()];
    }

    /**
     * Inject section_progress_current and section_progress_total Twig vars.
     * When parts are active, counts only section-page templates within the current part.
     */
    protected function injectSectionProgress($twig, $page, bool $isNestedPublication = false, ?object $bookBasePage = null): void
    {
        if (!$page || !in_array($page->template(), ['section', 'section-page'], true)) {
            return;
        }

        $hasParts          = $twig->twig_vars['has_parts'] ?? false;
        $routeSegments     = explode('/', trim($page->route(), '/'));
        $currentSectionId  = $isNestedPublication ? ($routeSegments[1] ?? '') : ($routeSegments[0] ?? '');
        $currentPartPrefix = $this->extractPartPrefix($currentSectionId);

        $scopedSectionIds = null;
        if ($hasParts && $currentPartPrefix !== null) {
            $versionInfo = $twig->twig_vars['doc_version_info'] ?? null;
            if ($versionInfo) {
                $scopedSectionIds = [];
                foreach ($versionInfo['versions'] as $v) {
                    $vid = is_array($v) ? ($v['id'] ?? '') : ($v->id ?? '');
                    if ($this->extractPartPrefix($vid) === $currentPartPrefix) {
                        $scopedSectionIds[] = $vid;
                    }
                }
            }
        }

        $allPages = [];
        if ($isNestedPublication && $bookBasePage) {
            foreach ($bookBasePage->children()->visible() as $section) {
                $this->collectPagesDepthFirst($section, $allPages);
            }
        } else {
            $root = $this->grav['pages']->root();
            foreach ($root->children()->visible() as $topLevel) {
                if ($scopedSectionIds !== null
                    && !in_array(trim($topLevel->route(), '/'), $scopedSectionIds, true)
                ) {
                    continue;
                }
                $this->collectPagesDepthFirst($topLevel, $allPages);
            }
        }

        $sections = array_values(array_filter(
            $allPages,
            fn($p) => in_array($p->template(), ['section', 'section-page'], true)
        ));

        if (empty($sections)) {
            return;
        }

        $currentUrl   = $page->url();
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

        // Expose the current part label so the progress indicator can show
        // "Part 1 · Page 2 of 3" instead of just "Page 2 of 3" when parts are active.
        if ($hasParts && $currentPartPrefix !== null) {
            $partLabels = $twig->twig_vars['part_labels'] ?? [];
            $twig->twig_vars['section_part_label'] = $partLabels[$currentPartPrefix] ?? null;
        }
    }

    /**
     * Build a nav_tree array from a Grav page children collection for nested-book mode.
     * Each item: url, title, route, active, parent_active, children, icon, api.
     */
    protected function buildNavTree($pages, string $currentUrl): array
    {
        $tree = [];
        foreach ($pages as $p) {
            if (!$p->routable() || !$p->published()) {
                continue;
            }
            $children = $this->buildNavTree($p->children()->visible(), $currentUrl);
            $isActive = ($p->url() === $currentUrl);
            $tree[] = [
                'url'           => $p->url(),
                'title'         => $p->title(),
                'route'         => $p->route(),
                'active'        => $isActive,
                'parent_active' => !$isActive && $this->hasActiveDescendant($children),
                'children'      => $children,
                'icon'          => $p->header()->icon ?? null,
                'api'           => [],
            ];
        }
        return $tree;
    }

    /**
     * Return true if any item in the array (or its descendants) is active.
     */
    protected function hasActiveDescendant(array $items): bool
    {
        foreach ($items as $item) {
            if ($item['active'] || $item['parent_active']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Extract the "part-N" prefix from a version ID, or return null if not part-scoped.
     * e.g. "part-1-section-2" → "part-1"; "section-3" → null
     */
    protected function extractPartPrefix(string $versionId): ?string
    {
        if (preg_match('/^(part-\d+)-section-\d+$/i', $versionId, $m)) {
            return strtolower($m[1]);
        }
        return null;
    }

    /**
     * Recursively collect visible, routable pages in depth-first order.
     */
    protected function collectPagesDepthFirst($page, array &$list): void
    {
        if ($page->visible() && $page->routable() && !($page->header()->redirect ?? false)) {
            $list[] = $page;
        }
        foreach ($page->children()->visible() as $child) {
            $this->collectPagesDepthFirst($child, $list);
        }
    }

    public function onLlmsRoute(): void
    {
        $path = $this->grav['uri']->route();
        if ($path === '/llms') {
            $this->outputLlms(false);
        } elseif ($path === '/llms-full') {
            $pub = $this->grav['uri']->query('pub');
            $this->outputLlms(true, $pub ? '/' . ltrim((string) $pub, '/') : null);
        }
    }

    private function outputLlms(bool $full, ?string $pubPath = null): void
    {
        $config    = $this->grav['config'];
        $title     = $config->get('site.title', 'Open Reader');
        $desc      = $config->get('site.metadata.description', '');
        $templates = (array) $this->config->get('plugins.helios-open-reader.plain_text_templates', ['section', 'section-page']);
        $imageMode = $this->config->get('plugins.helios-open-reader.plain_text_images', 'absolute');

        $lines = [];

        if ($pubPath) {
            $pubPage = $this->grav['pages']->find($pubPath);
            if ($pubPage && $pubPage->published() && $pubPage->visible()) {
                $lines[] = '# ' . $pubPage->title();
                if ($desc) {
                    $lines[] = '> ' . $desc;
                }
                $lines[] = '';
                $this->walkPages($pubPage, [], $lines, $full, $templates, $imageMode);
            }
        } else {
            $lines[] = '# ' . $title;
            if ($desc) {
                $lines[] = '> ' . $desc;
            }
            $lines[] = '';
            foreach ($this->grav['pages']->root()->children()->published()->visible() as $child) {
                $this->walkPages($child, [], $lines, $full, $templates, $imageMode);
            }
        }

        header('Content-Type: text/plain; charset=utf-8');
        echo implode("\n", $lines);
        exit();
    }

    /**
     * Process image references in markdown according to the configured mode:
     *   absolute  — rewrite relative paths to absolute URLs (default; best for LLM access)
     *   suppress  — remove all image markdown (text-only output)
     *   relative  — leave image paths unchanged
     */
    private function resolveImageUrls(string $markdown, $page, string $mode): string
    {
        if ($mode === 'suppress') {
            return preg_replace('/!\[[^\]]*\]\([^)]+\)\n?/', '', $markdown);
        }

        if ($mode !== 'absolute') {
            return $markdown;
        }

        $pageDir  = rtrim($page->url(true), '/') . '/';
        $siteBase = rtrim($this->grav['base_url_absolute'], '/');

        return preg_replace_callback(
            '/!\[([^\]]*)\]\(([^)]+)\)/',
            function ($m) use ($pageDir, $siteBase) {
                $url = $m[2];
                // Skip already-absolute URLs and data URIs
                if (preg_match('/^(https?:\/\/|\/\/|data:)/', $url)) {
                    return $m[0];
                }
                // Root-relative paths — prepend scheme+host only
                if ($url[0] === '/') {
                    return '![' . $m[1] . '](' . $siteBase . $url . ')';
                }
                // Relative paths — prepend the page's directory URL
                return '![' . $m[1] . '](' . $pageDir . $url . ')';
            },
            $markdown
        );
    }

    private function walkPages($page, array $crumbs, array &$lines, bool $full, array $templates, string $imageMode = 'absolute'): void
    {
        if (!$page->published() || !$page->visible()) {
            return;
        }

        $template = $page->template();

        if (in_array($template, $templates, true)) {
            $crumbs[]    = $page->title();
            $prefix      = implode(' > ', $crumbs);
            $description = $page->header()->description ?? '';

            $lines[] = '## ' . $prefix;
            $lines[] = '- [' . $page->title() . '](' . $page->url(true) . ')'
                       . ($description ? ' — ' . $description : '');

            if ($full) {
                $lines[] = '';
                $lines[] = $this->resolveImageUrls(trim($page->rawMarkdown()), $page, $imageMode);
            }

            $lines[] = '';
        } else {
            $crumbs[] = $page->title();
        }

        foreach ($page->children()->published()->visible() as $child) {
            $this->walkPages($child, $crumbs, $lines, $full, $templates, $imageMode);
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
            return;
        }

        // Custom Google Font — inject preconnect hints, stylesheet link, and scoped font-family/size override
        $fontUrl    = trim($this->config->get('plugins.helios-open-reader.custom_font_url', ''));
        $fontFamily = trim($this->config->get('plugins.helios-open-reader.custom_font_family', ''));
        $fontSize   = $this->config->get('plugins.helios-open-reader.custom_font_size', 'medium');
        $fontHeadings = (bool) $this->config->get('plugins.helios-open-reader.custom_font_headings', false);
        if ($fontUrl && strpos($fontUrl, 'https://fonts.googleapis.com/css') === 0
            && $fontFamily && preg_match('/^[\w\s,"\'.-]+$/', $fontFamily)) {
            $textScales = [
                'small'  => ['xs'=>'0.675rem','sm'=>'0.7875rem','base'=>'0.9rem','lg'=>'1.0125rem','xl'=>'1.125rem','2xl'=>'1.35rem','3xl'=>'1.6875rem','4xl'=>'2.025rem','5xl'=>'2.7rem'],
                'medium' => ['xs'=>'0.75rem','sm'=>'0.875rem','base'=>'1rem','lg'=>'1.125rem','xl'=>'1.25rem','2xl'=>'1.5rem','3xl'=>'1.875rem','4xl'=>'2.25rem','5xl'=>'3rem'],
                'large'  => ['xs'=>'0.825rem','sm'=>'0.9625rem','base'=>'1.1rem','lg'=>'1.2375rem','xl'=>'1.375rem','2xl'=>'1.65rem','3xl'=>'2.0625rem','4xl'=>'2.475rem','5xl'=>'3.3rem'],
            ];
            $themeFontMap = [
                'inter'                      => 'Inter, sans-serif',
                'open-sans'                  => 'OpenSans, sans-serif',
                'geom'                       => 'Geom, sans-serif',
                'nunito-sans'                => 'NunitoSans, sans-serif',
                'ubuntu-sans'                => 'UbuntuSans, sans-serif',
                'work-sans'                  => 'WorkSans, sans-serif',
                'public-sans'                => 'PublicSans, sans-serif',
                'quicksand'                  => 'Quicksand, sans-serif',
            ];
            $themeBodyFont = $this->config->get('themes.helios.fonts.body', 'inter');
            $themeFontFamily = $themeFontMap[$themeBodyFont] ?? 'Inter, sans-serif';
            $scale  = $textScales[$fontSize] ?? $textScales['medium'];
            $inject = '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n"
                    . '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n"
                    . '<link rel="stylesheet" href="' . htmlspecialchars($fontUrl, ENT_QUOTES, 'UTF-8') . '">' . "\n"
                    . '<style>#main-content {'
                    . ' --helios-font-body: ' . $fontFamily . ';'
                    . ' --font-sans: ' . $fontFamily . ';'
                    . ' font-family: ' . $fontFamily . ';'
                    . ' --text-xs: ' . $scale['xs'] . ';'
                    . ' --text-sm: ' . $scale['sm'] . ';'
                    . ' --text-base: ' . $scale['base'] . ';'
                    . ' --text-lg: ' . $scale['lg'] . ';'
                    . ' --text-xl: ' . $scale['xl'] . ';'
                    . ' --text-2xl: ' . $scale['2xl'] . ';'
                    . ' --text-3xl: ' . $scale['3xl'] . ';'
                    . ' --text-4xl: ' . $scale['4xl'] . ';'
                    . ' --text-5xl: ' . $scale['5xl'] . ';'
                    . ' }'
                    . ' #main-content #htmx-prev-next, #main-content .prev-next-nav {'
                    . ' --helios-font-body: ' . $themeFontFamily . ';'
                    . ' --font-sans: ' . $themeFontFamily . ';'
                    . ' font-family: ' . $themeFontFamily . ';'
                    . ' }'
                    . (!$fontHeadings
                        ? ' #main-content h1, #main-content h2, #main-content h3,'
                        . ' #main-content h4, #main-content h5, #main-content h6 {'
                        . ' --helios-font-body: ' . $themeFontFamily . ';'
                        . ' --font-sans: ' . $themeFontFamily . ';'
                        . ' font-family: ' . $themeFontFamily . ';'
                        . ' }'
                        : '')
                    . '</style>' . "\n";
            $event['output'] = str_replace('</head>', $inject . '</head>', $event['output']);
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
