<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class ExcerptShortcode extends Shortcode
{
    public function init()
    {
        if ($this->shortcode->getHandlers()->has('excerpt')) {
            return;
        }
        $this->shortcode->getHandlers()->add('excerpt', function(ShortcodeInterface $sc) {
            $content = $sc->getContent();
            if (!$content) {
                return '';
            }
            return '<blockquote class="excerpt">' . $content . '</blockquote>';
        });
    }
}
