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

class LazyLoadImages extends AbstractModifier
{
    public function isEnabled(): bool
    {
        return !!$this->_helper->isLazyLoadImage();
    }


    public function excludeImageFromDomains(): array
    {
        return [
            'facebook.com',
            'gallery-placeholder__image'
        ];
    }


    public function modify(string $html): string
    {
        $exclude = $this->excludeImageFromDomains();

        $_html = preg_replace_callback(
            '#<img(?:\s+[-\w]+=(?:"[^"]*"|\'[^\']*\'))+\s*(?:/|)>#mu',
            function ($matches) use ($exclude) {
                $img = $matches[0];
                foreach ($exclude as $domain) {
                    if (strpos($img, $domain) !== false) {
                        return $img;
                    }
                }
                $search = [' src='];
                switch ($this->_helper->isLazyLoadImage()) {
                    case LazyLoadModel::JAVASCRIPT_LAZY:
                        $defaultImg = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';
                        $replace = [
                            ' onload="window.CWVLazyLoad({}, this);" src="' . ($defaultImg) . '" data-src='
                        ];
                        break;
                    default:
                        $replace = [
                            ' loading="lazy" src='
                        ];
                        break;
                }
                return str_replace($search, $replace, $img);
            },
            $html
        );

        if (null !== $_html) {
            $html = $_html;
        }

        return $html;
    }
}