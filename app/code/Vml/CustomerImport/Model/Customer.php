<?php
/**
 * @author Vijay Rami
 * @copyright Copyright © Vijay Rami. All rights reserved.
 * @package Customer Import Module for Magento 2.
 */

declare(strict_types=1);

namespace Vml\CustomerImport\Model;
 
use Magento\Framework\Exception;
use Magento\Framework\Filesystem\Io\File;
use Magento\Store\Model\StoreManagerInterface;
use Vml\CustomerImport\Model\Import\CustomerImport;
 
class Customer
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param File $file
     * @param StoreManagerInterface $storeManagerInterface
     * @param CustomerImport $customerImport
     */
    public function __construct(
        private File $file,
        private StoreManagerInterface $storeManagerInterface,
        private CustomerImport $customerImport
    ) {
    }

    /**
     * Create customer
     *
     * @param array $data
     * @param int $websiteId
     * @param int $storeId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createCustomer(array $data, int $websiteId, int $storeId): void
    {
        try {
            // collect the customer data
            $customerData = [
                'email'         => $data['emailaddress'],
                '_website'      => 'base',
                '_store'        => 'default',
                'confirmation'  => null,
                'dob'           => null,
                'firstname'     => $data['fname'],
                'gender'        => null,
                //'group_id'      => 1,
                'lastname'      => $data['lname'],
                'middlename'    => null,
                //'password_hash' => $data['password_hash'],
                'prefix'        => null,
                'store_id'      => $storeId,
                'website_id'    => $websiteId,
                'password'      => null,
                'disable_auto_group_change' => 0,
                //'some_custom_attribute'     => 'some_custom_attribute_value'
            ];
            
            // save the customer data
            $this->customerImport->importCustomerData($customerData);
        } catch (Exception $e) {
            $this->output->writeln(
                '<error>'. $e->getMessage() .'</error>',
                OutputInterface::OUTPUT_NORMAL
            );
        }
    }
}
