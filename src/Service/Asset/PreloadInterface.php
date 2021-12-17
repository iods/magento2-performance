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

use Laminas\Http\Header\GenericMultiHeader;

interface PreloadInterface
{
    const TYPE_CSS = 'style';
    const TYPE_JS  = 'script';

    // check if the file is a preload or not
    public function isPreload($file): bool;

    public function getHeader($url, $type): GenericMultiHeader;

    // return the preloaded files
    public function getPreloads(): array;

    public function appendPreload($headers): \Iterator;

    public function registerPlugin($headers): \Iterator;

    // Register a file to static
    public function registerFile($url, $type): PreloadInterface;

    public function getSupportedTypes(): string;
}