<?php

namespace Wtc\CustomerImport\Api;

use Symfony\Component\Console\Input\InputInterface;

interface ImportInterface
{
    
    public const PROFILE = "profile";
    public const SOURCE = "source";

    /**
     * Get customer Import Data
     *
     * @param InputInterface $input
     * @return array
     */
    public function getCustomerImportData(InputInterface $input): array;
    /**
     * Format customer Data
     *
     * @param mixed $data
     * @return array
     */
    public function formatCustomerData($data): array;
     /**
      * Read file Import Data
      *
      * @param string $data
      * @return array
      */
    public function readfileData(string $data): array;
}
