<?php
namespace Grav\Plugin\Shortcodes;

use Grav\Common\Twig\Extension\GravExtension;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class ExerciseShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('exercise', function(ShortcodeInterface $sc) {
            $content = $sc->getContent();

            if (!$content) {
                return '';
            }

            $title   = htmlspecialchars($sc->getParameter('title', 'Interactive Activity'), ENT_QUOTES, 'UTF-8');
            $iconUri = 'plugin://github-markdown-alerts/assets/icons/octicon-tip.svg';
            $icon    = GravExtension::svgImageFunction($iconUri);

            // Compact button mode: only when content is a bare link with no surrounding block elements
            $hasRichContent = (bool) preg_match('/<(?:p|ul|ol|blockquote|details|h[1-6])\b/i', $content);
            if (!$hasRichContent && preg_match('/<a\s[^>]*href=["\']([^"\']+)["\'][^>]*>/i', $content, $match)) {
                $href = htmlspecialchars(html_entity_decode($match[1], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
                $body = '<p><a class="hor-h5p-btn" href="' . $href . '" target="_blank" rel="noopener">Open Interactive Activity ↗</a></p>';
            } else {
                $body = $content;
            }

            $output  = '<div class="md-alert md-alert--tip hor-h5p-exercise">';
            $output .= '<p class="md-alert-title">' . ($icon ? '<span aria-hidden="true">' . $icon . '</span> ' : '') . $title . '</p>';
            $output .= '<div class="md-alert-body">' . $body . '</div>';
            $output .= '</div>';

            return $output;
        });
    }
}
