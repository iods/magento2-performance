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

use Iods\Performance\Model\Html\Modifier\AbstractModifier;
use Iods\Performance\Service\Html\OutputModifierInterface;
use Psr\Log\LoggerInterface;

class OutputModifier extends AbstractModifier
{
    protected bool $_isDebug = false;

    protected LoggerInterface $_logger;

    protected OutputModifierInterface $_modifiers;

    protected string $_uid;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
        $modifiers = []
    ) {
        parent::__construct($context);
        $this->_isDebug = $this->_helper->isDebugMode();
        $this->_logger = $logger;
        $this->_modifiers = $modifiers;
        if ($this->_isDebug) {
            $this->_uid = uniqid('Modifier_');
        }
    }

    public function isEnabled(): bool
    {
        return $this->_helper->isEnabled();
    }

    public function modify(string $html): string
    {
        $tt = 0; // total time
        if ($this->isEnabled()) {
            if (strpos($html, '</body') !== false) {
                foreach ($this->_modifiers as $name => $modifier) {
                    if ($modifier instanceof OutputModifierInterface) {
                        $start_time = microtime(true);
                        if ($modifier->isEnabled()) {
                            $html = $modifier->modify($html);
                        }
                        $end_time = microtime(true);
                        if ($this->_isDebug) {
                            // Calculate the script execution time
                            $execution_time = ($end_time - $start_time);
                            $tt += $execution_time;
                            $this->_logger->info(implode([
                                $this->_uid,
                                ":" . $name . ": ",
                                $execution_time, " seconds"]));
                        }
                    }
                }
            }
            if ($this->_isDebug) {
                $this->_logger->info(implode([$this->_uid, ":OutputModifier: ", $tt, " seconds"]));
            }
        }
        return $html;
    }
}