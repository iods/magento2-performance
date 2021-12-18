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

use Iods\Performance\Helper\Data;
use Iods\Performance\Model\Html\Context;
use Iods\Performance\Service\Html\OutputModifierInterface;

abstract class AbstractModifier implements OutputModifierInterface
{
    protected Data $_helper;

    public function __construct(
        Context $context
    ) {
        $this->_helper = $context->getHelper();
    }

    public function isEnabled(): bool
    {
        return false;
    }
}