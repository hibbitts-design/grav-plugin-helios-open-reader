<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class EmbedlyShortcode extends Shortcode
{
    public function init()
    {
        if ($this->shortcode->getHandlers()->has('embedly')) {
            return;
        }
        $this->shortcode->getHandlers()->add('embedly', function(ShortcodeInterface $sc) {

            // Get shortcode content and parameters
            $embedlycardurl = $sc->getParameter('url', $sc->getBbCode()) ?: $sc->getContent();

            if ($embedlycardurl) {
                $embedlycardurl = htmlspecialchars(html_entity_decode($embedlycardurl, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
                $host = parse_url(html_entity_decode($embedlycardurl, ENT_QUOTES, 'UTF-8'), PHP_URL_HOST) ?: $embedlycardurl;
                return '<a class="embedly-card" data-card-controls="0" data-card-align="left" href="' . $embedlycardurl . '" aria-label="' . htmlspecialchars('Embedded content from ' . $host, ENT_QUOTES, 'UTF-8') . '">View embedded content</a>';
            }

        });
    }
}
