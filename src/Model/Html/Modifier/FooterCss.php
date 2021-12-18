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
use Iods\Performance\Service\Asset\DeferCssInterface;
use Iods\Performance\Service\Asset\FooterCssInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Magento\Framework\View\Asset\Merged;
use Magento\Framework\View\Asset\MergeStrategy\Checksum;
use Magento\Framework\View\Asset\MergeStrategy\FileExists;

class FooterCss extends AbstractModifier implements FooterCssInterface
{
    /** @var array|null */
    protected ?array $_allowedFiles = null;

    /** @var State */
    protected State $_state;

    /** @var array */
    private static array $footerFiles = [];

    public function __construct(
        Context $context,
        State $state
    ) {
        $this->_state = $state;
        parent::__construct($context);
    }

    public function isEnabled(): bool
    {
        return $this->_helper->isInFooterCss();
    }


    public function getAllowedFiles(): array
    {
        if (null === $this->_allowedFiles) {
            $files = [
                "css/styles-custom.css",
                "mage/gallery/gallery.css",
                "mage/calendar.css",
            ];
            $this->_allowedFiles = array_merge($files, $this->_helper->listFooterCss());
        }
        return $this->_allowedFiles;
    }

    /**
     * @inheridoc
     */
    public function registerCssFile($fileId, $asset): FooterCss
    {
        if (!isset(self::$footerFiles[$fileId])) {
            self::$footerFiles[$fileId] = $asset;
        }
        return $this;
    }


    public function getFooterCssFiles(): array
    {
        return self::$footerFiles;
    }


    protected function mergeCssFiles($assets)
    {
        $mergeStrategyClass = FileExists::class;
        if ($this->_state->getMode() === State::MODE_DEVELOPER) {
            $mergeStrategyClass = Checksum::class;
        }
        $mergeStrategy = ObjectManager::getInstance()->get($mergeStrategyClass);
        return  ObjectManager::getInstance()->create(
            Merged::class,
            [
                'assets' => $assets,
                'mergeStrategy' => $mergeStrategy
            ]
        );
    }


    public function modify(string $html): string
    {
        $assets = $this->getFooterCssFiles();

        if (count($assets)) {
            $this->mergeCssFiles($assets);
            $rel = 'javascript';

            switch ($this->_helper->getDeferCssMode()) {
                case DeferCssInterface::DEFAULT_BROWSER:
                    $rel = 'preload';
                    break;
                case DeferCssInterface::JAVASCRIPT_PRELOAD:
                    $rel = 'iods_preload';
                    break;
            }

            $tpl = '<link rel="%s" as="style" type="text/css" media="all" href="%s" />' . "\n";
            $css = '';

            foreach ($assets as $asset) {
                $css .= sprintf($tpl, $rel, $asset->getUrl());
            }
            return str_replace('</body', $css . '</body', $html);
        }

        return $html;
    }

    // check if the file is allowed for the footer
    public function isMovable($fileId): bool
    {
        return in_array($fileId, $this->getAllowedFiles());
    }
}