<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

/**
 * [badge] – the badge shortcode from Grav Open Publishing Space, Open Course Hub and Open MultiCourse Hub,
 * shown with the Helios badge style, so content moved from them works unchanged - hibbittsdesign.org
 *
 * Examples: [badge label="Due Friday"]  [badge label="New" type="success"]  [badge label="Read more" url="https://..."]
 * A Helios colour can also be given directly: [badge label="New" color="green"]
 */
class BadgeShortcode extends Shortcode
{
    /** The Bootstrap badge types used in the free projects, and the Helios colour each one becomes */
    protected $typeColors = [
        'primary'   => 'default',
        'secondary' => 'plain',
        'success'   => 'green',
        'info'      => 'blue',
        'warning'   => 'yellow',
        'danger'    => 'red',
        'light'     => 'plain',
        'dark'      => 'plain',
    ];

    public function init()
    {
        // Shortcode Core can include a starter [badge] of its own; remove it first so this one is used
        // (a shortcode name can only be added once)
        $handlers = $this->shortcode->getHandlers();
        if ($handlers->has('badge')) {
            $handlers->remove('badge');
        }
        $handlers->add('badge', function (ShortcodeInterface $sc) {
            // the badge text, from label="..." or the text between [badge] and [/badge]
            $label = (string) $sc->getParameter('label', '');
            if ($label === '') {
                $label = trim(strip_tags((string) $sc->getContent()));
            }
            if ($label === '') {
                return '';
            }

            // a Helios colour given with color="...", or else the free projects' type="..." translated to one
            $color = (string) $sc->getParameter('color', '');
            if ($color === '') {
                $type = (string) $sc->getParameter('type', 'secondary');
                $color = 'plain';
                if (isset($this->typeColors[$type])) {
                    $color = $this->typeColors[$type];
                }
            }

            // drawn with the Helios theme's own badge template, so it matches [doc-badge]
            $badge = trim($this->twig->processTemplate('shortcodes/doc-badge.html.twig', [
                'label'   => $label,
                'color'   => $color,
                'style'   => 'filled',
                'size'    => 'default',
                'icon'    => '',
                'classes' => '',
            ]));

            // with url="...", the badge is a link, as in the free projects
            $url = (string) $sc->getParameter('url', '');
            if ($url !== '') {
                $target = (string) $sc->getParameter('target', '_self');
                return '<a href="' . htmlspecialchars($url, ENT_QUOTES) . '" target="' . htmlspecialchars($target, ENT_QUOTES) . '">' . $badge . '</a>';
            }

            return $badge;
        });
    }
}
