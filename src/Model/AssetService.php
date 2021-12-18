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

namespace Iods\Performance\Model;

use Iods\Performance\Service\Asset\CriticalCssInterface;
use Iods\Performance\Service\Asset\DeferCssInterface;
use Iods\Performance\Service\Asset\PreloadInterface;

class AssetService
{
    protected CriticalCssInterface $_criticalCss;

    protected DeferCssInterface $_deferCss;

    protected PreloadInterface $_preloadAsset;

    public function __construct(
        CriticalCssInterface $criticalCss,
        DeferCssInterface    $deferCss,
        PreloadInterface     $preloadAsset
    ) {
        $this->_criticalCss  = $criticalCss;
        $this->_deferCss     = $deferCss;
        $this->_preloadAsset = $preloadAsset;
    }

    // return list w/ updated critical assets
    public function modifyResultGroups($resultGroups, $pageConfig): array
    {
        $sortedResultGroups['woff2'] = '';
        $sortedResultGroups['woff']  = '';
        $sortedResultGroups['ttf']   = '';
        $sortedResultGroups['eot']   = '';
        $sortedResultGroups['css']   = '';

        $criticalFont = '<style data-type="criticalCss">' . $this->_criticalCss->getFontsCriticalCss() . '</style>' . PHP_EOL;
        $criticalCss  = '<style data-tyle="criticalCss">' . $this->_criticalCss->getDefaultCriticalCss() . '</style>' . PHP_EOL;

        $sortedResultGroups['css'] .= $criticalFont;
        $sortedResultGroups['css'] .= $criticalCss;

        foreach ($resultGroups as $k => $v) {
            if (isset($sortedResultGroups[$k])) {
                $sortedResultGroups[$k] .= $v;
            } else {
                $sortedResultGroups[$k] = '';
            }
        }

        return $sortedResultGroups;
    }

    // register all of the preloads
    public function pushPreloadAsset($file, $url, $type)
    {
        if (in_array($type, (array)$this->_preloadAsset->getSupportedTypes()) !== false) {
            if ($this->_preloadAsset->isPreload($file)) {
                $this->_preloadAsset->registerFile($url, $type);
            }
        }
    }

    // register the headers
    public function pushPreloadHeader($headers)
    {
        if (!empty($this->_preloadAsset->getPreloads())) {
            $this->_preloadAsset->registerPlugin($headers);
            $this->_preloadAsset->appendPreload($headers);
        }
    }

    // check if file is deferred, then register it as static
    public function pushDeferByScript($file, $url, $attribute): bool
    {
        if ($this->_deferCss->isDeferred($file)) {
            $this->_deferCss->registerFile($url, $attribute);
            return true;
        }
        return false;
    }

    // render the files pushed by defer
    public function renderDeferByScript(): string
    {
        $result = $this->_deferCss->renderDeferred();

        return $result;
    }
}