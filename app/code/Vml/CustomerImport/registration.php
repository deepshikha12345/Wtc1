<?php
/**
 * @author Vijay Rami
 * @copyright Copyright © Vijay Rami. All rights reserved.
 * @package Customer Import Module for Magento 2.
 */
use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Vml_CustomerImport',
    __DIR__
);
