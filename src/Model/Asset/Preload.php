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

use Iods\Performance\Helper\Data;
use Iods\Performance\Model\Html\Context;
use Iods\Performance\Service\Asset\PreloadInterface;
use Laminas\Http\Header\GenericMultiHeader;
use Laminas\Http\HeaderLoader;
use Laminas\Http\Headers;

class Preload implements PreloadInterface
{
    protected Data $_helper;

    protected array $_preloaded;

    private static array $_preloadFiles = [];

    public function __construct(
        Context $context
    ) {
        $this->_helper = $context->getHelper();
    }

    protected function getCssPreloads(): array
    {
        $files = [];

        if ($this->_helper->isServerPushCss()) {
            $files = $this->_helper->getServerPushedCss();
        }

        return $files;
    }

    protected function getJsPreloads(): array
    {
        $files = [];

        if ($this->_helper->isServerPushJs()) {
            $files = $this->_helper->getServerPushedJs();
        }

        return $files;
    }

    public function getPreloads(): array
    {
        if (null === $this->_preloaded) {
            $this->_preloaded = [];
            $this->_preloaded = array_merge($this->_preloaded, $this->getCssPreloads());
            $this->_preloaded = array_merge($this->_preloaded, $this->getJsPreloads());
        }
        return $this->_preloaded;
    }


    public function getHeader($url, $type): GenericMultiHeader
    {
        return GenericMultiHeader::fromString(sprintf("link: <%s>; rel=preload; as=%s", $url, $type));
    }

    public function isPreload($file): bool
    {
        foreach ($this->getPreloads() as $key => $_file) {
            if ($_file == $file) {
                return true;
            }
        }

        return false;
    }

    public function registerFile($url, $type): array
    {
        if ($type == 'js') {
            $type = PreloadInterface::TYPE_JS;
        } else {
            $type = PreloadInterface::TYPE_CSS;
        }

        if (!isset(self::$_preloadFiles[$url])) {
            self::$_preloadFiles[$url] = $type;
        }

        return self::$_preloadFiles;
    }

    public function getPreloadFiles(): array
    {
        return self::$_preloadFiles;
    }


    public function registerPlugin($headers): \Iterator
    {
        HeaderLoader::addStaticMap([
            'link' => GenericMultiHeader::class
        ]);
        $headers->getPluginClassLoader()->registerPlugin('link', GenericMultiHeader::class);
        return $headers;
    }


    /**
     * @param Headers $headers
     * @return \Iterator
     */
    public function appendPreload($headers): \Iterator
    {
        $files = $this->getPreloadFiles();
        foreach ($files as $file => $type) {
            $headers->addHeader($this->getHeader($file, $type));
        }
        return $headers;
    }


    public function getSupportedTypes(): array
    {
        return [
            'css',
            'js'
        ];
    }
}