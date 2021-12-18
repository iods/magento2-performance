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

use Iods\Performance\Model\Html\Context;
use Iods\Performance\Service\Asset\MinifierInterface;

class MinifyHtml extends AbstractModifier
{
    protected MinifierInterface $_minifier;
    

    public function __construct(
        Context $context,
        MinifierInterface $minifier
    ) {
        $this->_minifier = $minifier;
        parent::__construct($context);
    }


    public function isEnabled(): bool
    {
        return $this->_helper->isMinifyHtml();
    }


    public function modify(string $html): string
    {
        $_html = $this->_minifier->minify($html);
        if (null !== $_html) {
            $html = $_html;
        }
        return $html;
    }
}