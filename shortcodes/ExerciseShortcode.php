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

            // Keep the exercise's content, and show its first paragraph that's just a link
            // (such as "View H5P activity online") as a button. A link inside a sentence stays a normal link.
            $body = $content;

            // a paragraph holding only a link: <p><a href="...">...</a></p> (the link's address is kept in $match[1])
            $linkParagraph = '/<p>\s*<a\s[^>]*href=["\']([^"\']+)["\'][^>]*>.*?<\/a>\s*<\/p>/is';

            if (preg_match($linkParagraph, $content, $match)) {
                $href   = htmlspecialchars(html_entity_decode($match[1], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
                $button = '<p><a class="hor-h5p-btn" href="' . $href . '" target="_blank" rel="noopener">Open Interactive Activity ↗</a></p>';

                // replace just that paragraph with the button: find where it starts, then swap it
                $start = strpos($content, $match[0]);
                $body  = substr_replace($content, $button, $start, strlen($match[0]));
            }

            $output  = '<div class="md-alert md-alert--tip hor-h5p-exercise">';
            $output .= '<p class="md-alert-title">' . ($icon ? '<span aria-hidden="true">' . $icon . '</span> ' : '') . $title . '</p>';
            $output .= '<div class="md-alert-body">' . $body . '</div>';
            $output .= '</div>';

            return $output;
        });
    }
}
