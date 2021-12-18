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

use Iods\Performance\Model\Html\Modifier\FooterCss;

interface FooterCssInterface
{
    // return the files moved to the footer
    public function getFooterCssFiles(): array;

    // return if the file is movable or not
    public function isMovable($fileId): bool;

    // register the file as a static asset
    public function registerCssFile($fileId, $asset): FooterCss;
}