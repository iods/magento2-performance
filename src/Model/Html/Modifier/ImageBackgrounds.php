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

class ImageBackgrounds extends AbstractModifier
{
    public function isEnabled(): bool
    {
        return true;
    }

    public function modify(string $html): string
    {
        // process the HTML <background-image> tags
        $_html = preg_replace_callback(

            '/background-image\s*?:\s*?url.*?>/is',
            function ($matches) {
                $content = $matches[0];
                $search = ['>'];
                if (false !== preg_match('/url\s*?\((.*?)\)/is', $content, $match)) {
                    if (count($match) > 1) {
                        $imageUrl = trim($match[1], '"\'');
                        $replace = [sprintf('><img alt="" src="%s" style="display: none"/>', $imageUrl)];
                        return str_replace($search, $replace, $content);
                    }
                }
                return $content;
            },

            $html
        );

        // should preg_replace_callback err, revert the html
        if (null !== $_html) {
            $html = $_html;
        }

        return $html;
    }
}