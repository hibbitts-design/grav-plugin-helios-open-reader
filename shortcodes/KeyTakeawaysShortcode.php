<?php
namespace Grav\Plugin\Shortcodes;

use Grav\Common\Twig\Extension\GravExtension;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class KeyTakeawaysShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('key-takeaways', function(ShortcodeInterface $sc) {
            $content = $sc->getContent();

            if (!$content) {
                return '';
            }

            $title   = htmlspecialchars($sc->getParameter('title', 'Key Takeaways'), ENT_QUOTES, 'UTF-8');
            $iconUri = 'plugin://github-markdown-alerts/assets/icons/octicon-note.svg';
            $icon    = GravExtension::svgImageFunction($iconUri);

            $output  = '<div class="md-alert md-alert--note hor-key-takeaways">';
            $output .= '<p class="md-alert-title">' . ($icon ? $icon . ' ' : '') . $title . '</p>';
            $output .= '<div class="md-alert-body">' . $content . '</div>';
            $output .= '</div>';

            return $output;
        });
    }
}
