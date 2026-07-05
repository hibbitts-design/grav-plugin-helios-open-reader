<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class PDFShortcode extends Shortcode
{
    public function init()
    {
        if ($this->shortcode->getHandlers()->has('pdf')) {
            return;
        }
        $this->shortcode->getHandlers()->add('pdf', function(ShortcodeInterface $sc) {

            // Get shortcode content and parameters
            $pdfurl = $sc->getParameter('url', $sc->getBbCode()) ?: $sc->getContent();
            $ratio  = $sc->getParameter('ratio', 'portrait');
            $title  = htmlspecialchars($sc->getParameter('title', 'PDF document'), ENT_QUOTES, 'UTF-8');

            // Map ratio parameter to CSS modifier class; default is portrait
            if ($ratio === '4:3') {
                $ratioClass = ' responsive-container--4x3';
            } elseif ($ratio === 'portrait') {
                $ratioClass = ' responsive-container--portrait';
            } else {
                $ratioClass = '';
            }

            if ($pdfurl) {
                $pdfurl = htmlspecialchars(html_entity_decode($pdfurl, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
                $output = '<div class="responsive-container' . $ratioClass . '"><iframe src="https://docs.google.com/gview?url=' . $pdfurl . '&amp;embedded=true" title="' . $title . '"></iframe></div>';

                return $output;
            }

        });
    }
}
