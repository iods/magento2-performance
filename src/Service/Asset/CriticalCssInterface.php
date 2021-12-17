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

namespace Iods\Performance\Service\Asset;

interface CriticalCssInterface
{
    const DEFAULT_CRITICAL_CSS_FILE = 'Iods_Performance::css/default.css';
    const IODS_CRITICAL_CSS_FILE    = 'Iods_Performance::css/iods.css';
    const FONTS_CRITICAL_CSS_FILE   = 'Iods_Performance::css/fonts.css';

    /**
     * @return mixed
     */
    public function getDefaultCriticalCss();

    public function getFontsCriticalCss();

    public function getCriticalContent($bodyClass): string;
}