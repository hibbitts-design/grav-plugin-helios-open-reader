<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class ReferencesShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('references', function(ShortcodeInterface $sc) {
            $content = $sc->getContent();

            if (!$content) {
                return '';
            }

            $title = htmlspecialchars($sc->getParameter('title', 'References'), ENT_QUOTES, 'UTF-8');

            $output  = '<div class="hor-references">';
            $output .= '<p class="hor-references-label">' . $title . '</p>';
            $output .= '<div class="hor-references-body">' . $content . '</div>';
            $output .= '</div>';

            return $output;
        });
    }
}
