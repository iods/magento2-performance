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

namespace Iods\Performance\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Data
 * @package Iods\Performance\Helper
 */
class Data extends AbstractHelper
{
    const IODS_CORE_GENERAL_CONFIG_XML = 'iods/';

    // return a config option
    public function getConfig($path, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(self::IODS_CORE_GENERAL_CONFIG_XML . $path, $scope);
    }

    // return if a config is enabled or not
    public function isEnabled(): bool
    {
        return !!$this->getConfig('general/enable');
    }

    // return if in debug mode
    public function isDebugMode(): bool
    {
        return !!$this->getConfig('general/debug');
    }

    // return if css was moved to the footer
    public function isInFooterCss(): bool
    {
        return !!$this->getConfig('css/in_footer');
    }

    // return if the JS was moved to the footer
    public function isInFooterJs(): bool
    {
        return !!$this->getConfig('js/in_footer');
    }

    // return if the CSS was minified inline or not
    public function isMinifyInlineCss(): bool
    {
        return !!$this->getConfig('css/minify_inline');
    }

    // return if the JS was minified inline or not
    public function isMinifyInlineJs(): bool
    {
        return !!$this->getConfig('js/minify_inline');
    }

    // return if the HTML is set to minify
    public function isMinifyHtml(): bool
    {
        return !!$this->getConfig('html/minify');
    }

    // return a list of all stylesheets moved to the footer
    public function listFooterCss(): array
    {
        return array_map(
            'trim', explode(',', $this->getConfig('css/in_footer_files'))
        );
    }

    // return whether lazy loading images is enabled
    public function isLazyLoadImage(): bool
    {
        return $this->getConfig('html/lazy_load_image');
    }

    // return whether lazy loading iframes is enabled or not
    public function isLazyLoadIframe(): bool
    {
        return $this->getConfig('html/lazy_load_iframe');
    }
}