<?php
namespace Grav\Plugin\Shortcodes;

use Grav\Common\Twig\Extension\GravExtension;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class CaseStudyShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('case-study', function(ShortcodeInterface $sc) {
            $content = $sc->getContent();

            if (!$content) {
                return '';
            }

            $title   = htmlspecialchars($sc->getParameter('title', 'Case Study'), ENT_QUOTES, 'UTF-8');
            $iconUri = 'plugin://github-markdown-alerts/assets/icons/octicon-caution.svg';
            $icon    = GravExtension::svgImageFunction($iconUri);

            $output  = '<div class="md-alert md-alert--caution hor-case-study">';
            $output .= '<p class="md-alert-title">' . ($icon ? $icon . ' ' : '') . $title . '</p>';
            $output .= '<div class="md-alert-body">' . $content . '</div>';
            $output .= '</div>';

            return $output;
        });
    }
}
