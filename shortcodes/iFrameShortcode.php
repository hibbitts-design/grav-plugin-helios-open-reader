<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class iFrameShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('iframe', function(ShortcodeInterface $sc) {

            // Get shortcode content and parameters
            $iframeurl = $sc->getParameter('url', $sc->getBbCode()) ?: $sc->getContent();
            $ratio     = $sc->getParameter('ratio', '16:9');
            $title     = htmlspecialchars($sc->getParameter('title', 'Embedded content'), ENT_QUOTES, 'UTF-8');

            // Map ratio parameter to CSS modifier class; default is 16:9
            $ratioClass = ($ratio === '4:3') ? ' responsive-container--4x3' : '';

            if ($iframeurl) {
                $output = '<div class="responsive-container' . $ratioClass . '"><iframe src="' . htmlspecialchars($iframeurl, ENT_QUOTES, 'UTF-8') . '" title="' . $title . '" allowfullscreen></iframe></div>';

                return $output;
            }

        });
    }
}
