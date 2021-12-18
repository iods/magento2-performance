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

class FooterJs extends AbstractModifier
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
        return $this->_helper->isInFooterJs();
    }


    public function modify(string $html): string
    {
        $script = [];
        $ignoreParts = $this->excludeScriptTags();
        $pattern = '#<script[^>]*+(?<!text/x-magento-template.)>(.*?)</script>#is';

        $_html = preg_replace_callback(
            $pattern,
            function ($matchPart) use (&$script, $ignoreParts) {
                $ignore = false;
                if (strpos($matchPart[1], 'BASE_URL') !== false) {
                    return $matchPart[0];
                }
                if (!empty($ignoreParts)) {
                    foreach ($ignoreParts as $ignorePart) {
                        if (strpos($matchPart[0], $ignorePart) !== false) {
                            $ignore = true;
                            break;
                        }
                    }
                }
                if (!$ignore) {
                    if ($this->_helper->isMinifyInlineJs()) {
                        preg_match('#<script[^>]*+(?<!text/x-magento-template.)>#is', $matchPart[0], $began);
                        if ($began) {
                            $began = $began[0];
                            $_script = $this->_minifier->minify($matchPart[1]);
                            $script[] = $began . $_script . '</script>';
                            return '';
                        }
                    }
                    $script[] = $matchPart[0];
                    return '';
                }
                return $matchPart[0];
            },
            $html
        );

        if (null !== $_html) {
            $html = $_html;
        }

        return str_replace('</body', implode("\n", $script) . "\n</body", $html);
    }


    public function excludeScriptTags(): array
    {
        return [
            'data-cfasync="false"'
        ];
    }
}