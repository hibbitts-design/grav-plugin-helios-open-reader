<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

/**
 * [hideifembedded]...[/hideifembedded] – content hidden when the page is embedded (?embedded=true or ?chromeless=true),
 * as in Grav Helios Course Hub and Grav Open Publishing Space - hibbittsdesign.org
 * The content is only wrapped here; CSS hides it (see helios.css and onTwigSiteVariables), because a page's content
 * is cached once and shown for both the embedded and normal addresses.
 */
class HideIfEmbeddedShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('hideifembedded', function (ShortcodeInterface $sc) {
            return '<div class="hor-hide-if-embedded">' . $sc->getContent() . '</div>';
        });
    }
}
