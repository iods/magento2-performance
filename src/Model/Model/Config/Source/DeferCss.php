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

use Iods\Performance\Service\Asset\DeferCssInterface;
use Magento\Framework\Data\OptionSourceInterface;

class DeferCss implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        $optionArray[] = [
            'value' => 0,
            'label' => __("Disable"),
        ];

        $optionArray[] = [
            'value' => DeferCssInterface::DEFAULT_BROWSER,
            'label' => __("Default Browser rel='preload'"),
        ];

        $optionArray[] = [
            'value' => DeferCssInterface::JAVASCRIPT_PRELOAD,
            'label' => __("JavaScript rel='iods_preload'"),
        ];

        return $optionArray;
    }
}