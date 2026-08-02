<?php
namespace Grav\Plugin\Shortcodes;

use Grav\Common\Grav;
use Grav\Common\HTTP\Client;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class EmbedlyShortcode extends Shortcode
{
    const REACHABLE_CACHE_SECONDS = 604800;   // 7 days
    const UNREACHABLE_CACHE_SECONDS = 3600;   // 1 hour

    public function init()
    {
        if ($this->shortcode->getHandlers()->has('embedly')) {
            return;
        }
        $this->shortcode->getHandlers()->add('embedly', function(ShortcodeInterface $sc) {

            // Get shortcode content and parameters
            $embedlycardurl = $sc->getParameter('url', $sc->getBbCode()) ?: $sc->getContent();

            if (!$embedlycardurl) {
                return '';
            }

            $embedlycardurl = html_entity_decode($embedlycardurl, ENT_QUOTES, 'UTF-8');
            $safeUrl = htmlspecialchars($embedlycardurl, ENT_QUOTES, 'UTF-8');
            $host = parse_url($embedlycardurl, PHP_URL_HOST) ?: $embedlycardurl;

            if (!$this->isUrlReachable($embedlycardurl)) {
                return '<a class="embedly-card embedly-card-unavailable" href="' . $safeUrl . '" target="_blank" rel="nofollow noopener noreferrer">This linked content is no longer available</a>';
            }

            return '<a class="embedly-card" data-card-controls="0" data-card-align="left" href="' . $safeUrl . '" aria-label="' . htmlspecialchars('Embedded content from ' . $host, ENT_QUOTES, 'UTF-8') . '">View embedded content</a>';

        });
    }

    private function isUrlReachable(string $url): bool
    {
        if (!preg_match('#^https?://#i', $url)) {
            return false;
        }

        $cache = Grav::instance()['cache'];
        $cacheKey = 'embedly-reachable-' . md5($url);
        $cached = $cache->fetch($cacheKey);

        if ($cached !== false) {
            return (bool) $cached['reachable'];
        }

        $isReachable = false;

        try {
            $response = Client::getClient()->request('GET', $url, ['timeout' => 5]);
            $isReachable = $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            $isReachable = false;
        }

        $cache->save(
            $cacheKey,
            ['reachable' => $isReachable],
            $isReachable ? self::REACHABLE_CACHE_SECONDS : self::UNREACHABLE_CACHE_SECONDS
        );

        return $isReachable;
    }
}
