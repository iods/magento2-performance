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

namespace Iods\Performance\Model\Asset;

use Iods\Performance\Service\Asset\MinifierInterface;
use Magento\Framework\Code\Minifier\AdapterInterface;

class Minify implements MinifierInterface
{
    protected AdapterInterface $_adapter;

    public function __construct(
        AdapterInterface $adapter
    ) {
        $this->_adapter = $adapter;
    }

    public function minify($content): string
    {
        return $this->_adapter->minify($content);
    }
}