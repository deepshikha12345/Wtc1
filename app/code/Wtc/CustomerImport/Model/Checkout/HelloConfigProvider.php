<?php
namespace Wtc\CustomerImport\Model\Checkout;

use Magento\Checkout\Model\ConfigProviderInterface;

class HelloConfigProvider implements ConfigProviderInterface
{
    public function getConfig()
    {
        $config = [];
        $config['text'] = "Hello it is working";
        return $config;
    }
}
