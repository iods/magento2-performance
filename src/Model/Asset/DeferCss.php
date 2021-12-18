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
use Iods\Performance\Service\Asset\DeferCssInterface;
use Iods\Performance\Service\Asset\PreloadInterface;

class DeferCss implements DeferCssInterface
{
    protected Data $_helper;

    protected array $_registeredFiles = [];

    private static array $_deferFiles = [];

    public function __construct(
        Context $context
    ) {
        $this->_helper = $context->getHelper();
        $this->_registeredFiles = $this->_helper->getDeferredFiles();
    }

    public function isDeferred($fileId): bool
    {
        return (!!$this->_helper->getDeferCssMode()) && in_array($fileId, $this->_registeredFiles);
    }

    public function resetFiles(): DeferCss
    {
        self::$_deferFiles = [];
        return $this;
    }


    /**
     * @param $url
     * @param $attribute
     * @return array|PreloadInterface
     */
    public function registerFile($url, $attribute): PreloadInterface
    {
        if (!isset(self::$_deferFiles[$url])) {
            self::$_deferFiles[$url] = $attribute;
        }

        return self::$_deferFiles;
    }


    public function renderDeferred(): string
    {
        $rel = 'javascript';
        switch ($this->_helper->getDeferCssMode()) {
            case self::DEFAULT_BROWSER:
                $rel = 'preload';
                break;
            case self::JAVASCRIPT_PRELOAD:
                $rel = 'iods_preload';
                break;
        }
        $tpl = '<link rel="%s" as="style" type="text/css" %s href="%s" />' . "\n";
        $output = '';
        foreach (self::$_deferFiles as $url => $attribute) {
            $output .= sprintf($tpl, $rel, $attribute, $url);
        }
        return $output;
    }
}