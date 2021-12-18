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

interface DeferCssInterface
{
    const DEFAULT_BROWSER = 1;
    const JAVASCRIPT_PRELOAD = 2;

    // return if the file is deferred or not
    public function isDeferred($fileId): bool;

    // register file to static
    public function registerFile($url, $attribute): PreloadInterface;

    // return the rendered files
    public function renderDeferred(): string;
}