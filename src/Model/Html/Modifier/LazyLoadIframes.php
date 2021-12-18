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

namespace Iods\Performance\Model\Html\Modifier;

use Iods\Performance\Model\Model\Config\Source\LazyLoadModel;

class LazyLoadIframes extends AbstractModifier
{
    public function isEnabled(): bool
    {
        return !!$this->_helper->isLazyLoadIframe();
    }





    public function modify(string $html): string
    {


        $_html = preg_replace_callback(
            '/<iframe.*?>.*?<\/iframe>/is',
            function ($matches) {
                if (strpos($matches[0], 'googletagmanager') === false) {
                    $img = $matches[0];
                    $search = [' src='];
                    switch ($this->helper->isLazyLoadingIframe()) {
                        case LazyLoadModel::JAVASCRIPT_LAZY:
                            $replace = [' onload="window.CWVLazyLoad({}, this);" data-src='];
                            break;
                        default:
                            $replace = [' loading="lazy" src='];
                            break;
                    }
                    return str_replace($search, $replace, $img);
                }
                return $matches[0];
            },
            $html
        );

        if (null !== $_html) {
            $html = $_html;
        }

        return $html;
    }
}