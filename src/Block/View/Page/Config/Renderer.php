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

namespace Iods\Performance\Block\View\Page\Config;

use Iods\Performance\Model\AssetService;
use Iods\Performance\Service\Asset\FooterCssInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\ConfigInterface;
use Magento\Framework\View\Asset\GroupedCollection;
use Magento\Framework\View\Asset\MergeService;
use Magento\Framework\View\Asset\PropertyGroup;
use Magento\Framework\View\Page\Config;
use Psr\Log\LoggerInterface;

class Renderer extends Config\Renderer
{
    protected AssetService $_assetService;

    protected ConfigInterface $_config;

    protected FooterCssInterface $_footerCss;


    public function __construct(
        AssetService $assetService,
        Config $pageConfig,
        ConfigInterface $config,
        Escaper $escaper,
        FooterCssInterface $footerCss,
        LoggerInterface $logger,
        MergeService $mergeService,
        StringUtils $string,
        UrlInterface $urlBuilder,
    ) {
        $this->_assetService = $assetService;
        $this->_config = $config;
        $this->_footerCss = $footerCss;
        parent::__construct($pageConfig, $mergeService, $urlBuilder, $escaper, $string, $logger);
    }


    public function renderAssets($resultGroups = []): string
    {
        $sortedResultGroups = $this->_assetService->modifyResultGroups($resultGroups, $this->pageConfig);
        return parent::renderAssets($sortedResultGroups);
    }


    protected function renderAssetHtml(PropertyGroup $group): string
    {
        // disable merge js/css
        $content = $group->getProperty(GroupedCollection::PROPERTY_CONTENT_TYPE);
        $css = $content == 'css';
        $js = $content == 'js';

        $isMergeCssEnabled = $this->_config->isMergeCssFiles();
        $isMergeJsEnabled = $this->_config->isMergeJsFiles();

        if (($css && !$isMergeCssEnabled) || ($js && !$isMergeJsEnabled)) {
            $attributes = $this->getGroupAttributes($group);
            $assets = $group->getAll();

            foreach ($assets as $key => $asset) {
                $type = $this->getAssetContentType($asset);
                $this->_assetService->pushPreloadAsset($key, $asset->getUrl(), $type);

                if ($this->_assetService->pushDeferByScript($key, $asset->getUrl(), $attributes)) {
                    $group->remove($key);
                } elseif ($this->_footerCss->isMovable($key)) {
                    $this->_footerCss->registerCssFile($key, $asset);
                    $group->remove($key);
                }

            }

        }

        $result = parent::renderAssetHtml($group);
        return $result . $this->_assetService->renderDeferByScript();
    }
}