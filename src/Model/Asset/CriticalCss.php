<?php
/**
 * Core UI and SEO enhancements to improve Magento performance.
 *
 * @package   Iods_Performance
 * @author    Rye Miller <rye@drkstr.dev>
 * @copyright Copyright © 2021, Rye Miller (https://ryemiller.io)
 * @license   MIT (https://en.wikipedia.org/wiki/MIT_License)
 */
declare(strict_types=1);

namespace Iods\Performance\Model\Asset;

use Iods\Performance\Service\Asset\CriticalCssInterface;
use Magento\Framework\App\Cache\Type\Reflection;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\View\Asset\Repository as AssetRepository;

class CriticalCss implements CriticalCssInterface
{
    const CSS_CACHE_LIFETIME = 2592000;

    protected AssetRepository $_assetRepository;

    protected CacheInterface $_cache;

    public function __construct(
        AssetRepository $assetRepository,
        CacheInterface $cache
    ) {
        $this->_assetRepository = $assetRepository;
        $this->_cache = $cache;
    }

    protected function loadContent($fileName): array
    {
        $content = $this->_cache->load($fileName);
        if (!$content) {

            $asset = $this->_assetRepository->createAsset(
                $fileName,
                [
                    'secure' => true
                ]
            );

            try {
                $content = $asset->getContent();
                $content = str_replace(
                    'domain_static_version',
                    $this->_assetRepository->getStaticViewFileContext()->getBaseUrl(),
                    $content
                );
                $this->_cache->save(
                    $content,
                    $fileName,
                    [Reflection::CACHE_TAG],
                    static::CSS_CACHE_LIFETIME
                );
            } catch (\Exception $exception) {
                // bypass exception
                $content = '';
            }
        }
        return $content;
    }

    // compile the default css files defined "core and critical"
    public function getDefaultCriticalCss(): string
    {
        $default = $this->loadContent(CriticalCssInterface::DEFAULT_CRITICAL_CSS_FILE);
        $core = $this->loadContent(CriticalCssInterface::IODS_CRITICAL_CSS_FILE);

        return $default . PHP_EOL . $core;
    }

    // compile the core font styles
    public function getFontsCriticalCss()
    {
        $fonts = CriticalCssInterface::FONTS_CRITICAL_CSS_FILE;
        return $this->loadContent($fonts);
    }

    // get the critical content to display
    public function getCriticalContent($bodyClass): string
    {
        $content = [
            'cms-index-index' => 'Iods_Performance::css/google_fonts.css',
        ];
        $fileId = self::DEFAULT_CRITICAL_CSS_FILE;

        foreach ($content as $class => $_fileId) {
            if (strpos($bodyClass, $class) !== false) {
                $fileId = $_fileId;
                break;
            }
        }
        return '';
    }
}