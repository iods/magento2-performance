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

namespace Iods\Performance\Plugin\Controller;

use Iods\Performance\Service\Html\OutputModifierInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;

class RenderResultPlugin
{
    protected OutputModifierInterface $_outputModifier;

    protected Registry $_registry;

    public function __construct(
        OutputModifierInterface $outputModifier,
        Registry $registry
    ) {
        $this->_outputModifier = $outputModifier;
        $this->_registry = $registry;
    }

    public function afterRenderResult(
        ResultInterface $subject,
        $result,
        ResponseInterface $response
    ) {
        $plugin = $this->_registry->registry('use_page_cache_plugin');
        if ($plugin) {
            if ($response instanceof Http) {
                $content = $response->getBody();
                $content = $this->_outputModifier->modify($content);
                $response->setBody($content);
            }
        }
        return $result;
    }
}