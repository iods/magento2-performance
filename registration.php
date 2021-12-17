<?php
/**
 * Description of a module goes here for Magento 2
 *
 * @package   Iods_Bones
 * @author    Rye Miller <rye@drkstr.dev>
 * @copyright Copyright © 2020, Rye Miller (https://ryemiller.io)
 * @license   MIT (https://en.wikipedia.org/wiki/MIT_License)
 */
declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Iods_Bones',
    __DIR__
);
