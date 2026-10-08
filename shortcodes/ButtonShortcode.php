<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

/**
 * [button] – the button shortcode from Grav Open Publishing Space, Open Course Hub and Open MultiCourse Hub,
 * shown with the Helios button style, so content moved from them works unchanged - hibbittsdesign.org
 *
 * Examples: [button label="Start the Quiz" url="https://..."]  [button label="Slides" url="..." type="secondary" size="sm"]
 * The free course hubs use type="...", Open Publishing Space uses style="..."; both work, as does a Helios color="...".
 */
class ButtonShortcode extends Shortcode
{
    /** The Bootstrap button types used in the free projects, and the Helios colour each one becomes */
    protected $typeColors = [
        'primary'   => 'default',
        'secondary' => 'plain',
        'success'   => 'green',
        'info'      => 'blue',
        'warning'   => 'yellow',
        'danger'    => 'red',
        'light'     => 'plain',
        'dark'      => 'plain',
    ];

    public function init()
    {
        // another plugin, or Shortcode Core's Shortcode Builder, may also define [button]; remove it first so this one is used
        // (a shortcode name can only be added once)
        $handlers = $this->shortcode->getHandlers();
        if ($handlers->has('button')) {
            $handlers->remove('button');
        }
        $handlers->add('button', function (ShortcodeInterface $sc) {
            $label = (string) $sc->getParameter('label', '');
            if ($label === '') {
                $label = trim(strip_tags((string) $sc->getContent()));
            }
            $url = (string) $sc->getParameter('url', '');
            // as in the free projects, a button needs both a label and a link
            if ($label === '' || $url === '') {
                return '';
            }

            // the button's type: type="..." (course hubs) or style="..." (Open Publishing Space)
            $type = (string) $sc->getParameter('type', '');
            if ($type === '') {
                $type = (string) $sc->getParameter('style', 'primary');
            }

            // an outline-... type becomes the Helios bordered style, in the matching colour
            $style = 'default';
            if (strpos($type, 'outline-') === 0) {
                $style = 'bordered';
                $type = substr($type, strlen('outline-'));
            }

            // a Helios colour given with color="...", or else the type translated to one
            $color = (string) $sc->getParameter('color', '');
            if ($color === '') {
                $color = 'default';
                if (isset($this->typeColors[$type])) {
                    $color = $this->typeColors[$type];
                }
            }

            // Bootstrap's sm and lg sizes have Helios equivalents; anything else is the normal size
            $size = (string) $sc->getParameter('size', '');
            if ($size !== 'sm' && $size !== 'lg') {
                $size = 'default';
            }

            // drawn with the Helios theme's own button template, so it matches [doc-button]
            $button = trim($this->twig->processTemplate('shortcodes/doc-button.html.twig', [
                'label'      => $label,
                'link'       => $url,
                'style'      => $style,
                'color'      => $color,
                'size'       => $size,
                'icon_left'  => '',
                'icon_right' => '',
                'new_tab'    => $sc->getParameter('target', '') === '_blank',
                'data_attr'  => '',
                'data_val'   => '',
                'classes'    => (string) $sc->getParameter('classes', ''),
                'center'     => false,
            ]));

            // in its own paragraph, as in the free projects, so it sits on its own line
            return '<p>' . $button . '</p>';
        });
    }
}
