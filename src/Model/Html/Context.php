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

namespace Iods\Performance\Model\Html;

use Iods\Performance\Helper\Data;

class Context
{
    protected Data $_helperData;

    public function __construction(
        Data $helperData
    ) {
        $this->_helperData = $helperData;
    }

    public function getHelper(): Data
    {
        return $this->_helperData;
    }
}