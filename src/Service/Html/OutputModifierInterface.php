<?php
/**
 * CWV and SEO enhancements to improve Magento performance.
 *
 * @package   Iods_Performance
 * @author    Rye Miller <rye@drkstr.dev>
 * @copyright Copyright © 2021, Rye Miller (https://ryemiller.io)
 * @license   MIT (https://en.wikipedia.org/wiki/MIT_License)
 */
declare(strict_types=1);

namespace Iods\Performance\Service\Html;

/**
 * Interface OutputModifierInterface
 * @package Iods\Performance\Service\Html
 */
interface OutputModifierInterface
{
    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @param string $html
     * @return string
     */
    public function modify(string $html): string;
}