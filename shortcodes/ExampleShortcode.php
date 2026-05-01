<?php
namespace Grav\Plugin\Shortcodes;

use Grav\Common\Twig\Extension\GravExtension;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class ExampleShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('example', function(ShortcodeInterface $sc) {
            $content = $sc->getContent();

            if (!$content) {
                return '';
            }

            $title   = htmlspecialchars($sc->getParameter('title', 'Example'), ENT_QUOTES, 'UTF-8');
            $iconUri = 'plugin://github-markdown-alerts/assets/icons/octicon-important.svg';
            $icon    = GravExtension::svgImageFunction($iconUri);

            $output  = '<div class="md-alert md-alert--important hor-example">';
            $output .= '<p class="md-alert-title">' . ($icon ? $icon . ' ' : '') . $title . '</p>';
            $output .= '<div class="md-alert-body">' . $content . '</div>';
            $output .= '</div>';

            return $output;
        });
    }
}
