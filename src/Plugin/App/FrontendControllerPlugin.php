<?php
/**
 * CWV and SEO enhancements to improve Magento performance.
 *
 * @version   0.1.0
 * @author    Rye Miller <rye@drkstr.dev>
 * @copyright Copyright © 2021, Rye Miller (https://ryemiller.io)
 * @license   MIT (https://en.wikipedia.org/wiki/MIT_License)
 */
declare(strict_types=1);

namespace Iods\Performance\Plugin\App;

use Iods\Performance\Service\Html\OutputModifierInterface;
use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http;

/**
 * Class FrontendController
 * @package Iods\Performance\Plugin\App
 */
class FrontendControllerPlugin
{
    /** @var OutputModifierInterface */
    protected OutputModifierInterface $_outputModifier;

    public function __construct(
        OutputModifierInterface $outputModifier
    ) {
        $this->_outputModifier = $outputModifier;
    }

    /**
     * @param FrontControllerInterface $subject
     * @param $result
     * @param RequestInterface $request
     * @return Http|mixed
     */
    public function afterDispatch(
        FrontControllerInterface $subject,
        $result,
        RequestInterface $request
    ) {
        if ($result instanceof Http) {
            $content = $result->getContent();
            $content = $this->_outputModifier->modify($content);
            $result->setContent($content);
        }
        return $result;
    }
}