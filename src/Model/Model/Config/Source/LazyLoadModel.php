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

namespace Iods\Performance\Model\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class LazyLoadModel implements OptionSourceInterface
{
    const DEFAULT_BROWSER = 1;
    const JAVASCRIPT_LAZY = 2;

    public function toOptionArray(): array
    {
        $optionArray[] = [
            'value' => 0,
            'label' => __("Disable"),
        ];

        $optionArray[] = [
            'value' => self::DEFAULT_BROWSER,
            'label' => __("Default Browser loading='lazy'"),
        ];

        $optionArray[] = [
            'value' => self::JAVASCRIPT_LAZY,
            'label' => __("Javascript Lazy Loading"),
        ];

        return $optionArray;
    }
}