<?php

namespace App\Twig;

use Psr\Cache\CacheItemPoolInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Referer class
 *
 * Class ViteAssetExtension
 * @package App\Twig
 */
class ViteAssetExtension extends AbstractExtension
{
    CONST CACHE_KEY = 'vite_manifest';

    /** @var bool */
    private $isDev;

    /** @var string */
    private $manifest;

    private $manifestData = [];

    /** @var CacheItemPoolInterface */
    private $cache;

    public function __construct(
        bool $isDev,
        CacheItemPoolInterface $cache
    )
    {
        $this->isDev = $isDev;
        $this->manifest = \dirname(__DIR__). '/../public/assets/manifest.json';
        $this->cache = $cache;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'vite_asset',
                [$this, 'asset'],
                ['is_safe' => ['html']])
        ];
    }

    public function asset(string $entry, array $deps): string
    {
        if  ($this->isDev) {
            return $this->assetDev($entry, $deps);
        }

        return $this->assetProd($entry);
    }

    /**
     * Inject structure JS project
     *
     * @param string $entry // exemple main.js
     * @param array $deps // exemple angular, react, vue , vanille
     * @return string
     */
    private function assetDev(string $entry, array $deps): string
    {
        $html = <<<HTML
<script type="module" src="http://localhost:3000/assets/@vite/client"></script>
HTML;
        if (in_array('react', $deps)) {
            $html .= '<script type="module">
                    import RefreshRuntime from "http://localhost:3000/assets/@react-refresh"
                    RefreshRuntime.injectIntoGlobalHook(window)
                    window.$RefreshReg$ = () => {}
                    window.$RefreshSig$ = () => (type) => type
                    window.__vite_plugin_react_preamble_installed__ = true
                </script>';
        }
        $html .= <<<HTML
<script type="module" src="http://localhost:3000/assets/{$entry}" defer></script>
HTML;
        return $html;
    }

    private function assetProd(string $entry): string
    {
        if (empty($this->manifestData)) {
            // look for manifest data into cache
            $item = $this->cache->getItem(self::CACHE_KEY);

            if ($item->isHit()) {
                // return manifest data from cache
                $this->manifestData = $item->get();
            } else {
                $this->manifestData = json_decode($this->getManifestContent(), true);
                $item->set($this->manifestData);
                dump('voir mon cache vite');
                $this->cache->save($item);
            }
        }

        if (key_exists('file', $this->manifestData[$entry])) {
            $file = $this->manifestData[$entry]['file'];
            $html = <<<HTML
<script type="module" src="/assets/{$file}" defer></script>
HTML;
        }

        if (key_exists('css', $this->manifestData[$entry])) {
            $cssFiles = $this->manifestData[$entry]['css'];
            foreach ($cssFiles as $key => $css) {
                $html .= <<<HTML
<link rel="stylesheet" media="screen" href="/assets/{$css}" />
HTML;
            }
        }

        if (key_exists('inport', $this->manifestData[$entry])) {
            $imports = $this->manifestData[$entry]['inport'];

            foreach ($imports as $key => $import) {
                $html .= <<<HTML
<link rel="modulepreload"  href="/assets/{$import}" />
HTML;
            }
        }

        return $html;
    }

    private function getManifestContent()
    {
        return file_get_contents($this->manifest, true);
    }
}
