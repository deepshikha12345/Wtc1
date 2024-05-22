<?php
/**
 * @author Deepshikha Makwana
 * @copyright Copyright © Deepshikha Makwana. All rights reserved.
 * @package Customer Import via csv and json file.
 */
use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Wtc_CustomerImport',
    __DIR__
);
