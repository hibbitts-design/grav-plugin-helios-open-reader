<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

/**
 * [showifembedded]...[/showifembedded] – content shown only when the page is embedded (?embedded=true or ?chromeless=true),
 * as in the free Grav Open Publishing Space, Open Course Hub and Open MultiCourse Hub - hibbittsdesign.org
 * The content is only wrapped here; CSS shows it (see helios.css and onTwigSiteVariables), because a page's content
 * is cached once and shown for both the embedded and normal addresses.
 */
class ShowIfEmbeddedShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('showifembedded', function (ShortcodeInterface $sc) {
            return '<div class="hor-show-if-embedded">' . $sc->getContent() . '</div>';
        });
    }
}
