<?php
/**
 * @author Deepshikha Makwana
 * @copyright Copyright © Deepshikha Makwana. All rights reserved.
 * @package Customer Import via csv and json file.
 */

namespace Wtc\CustomerImport\Model;
 
use Magento\Framework\Exception;
use Wtc\CustomerImport\Model\Import\CustomersImports;
 
class Customers
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
        private CustomersImports $customersImports
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
    public function createCustomers(array $data, int $websiteId, int $storeId): void
    {
        try {
            // set all customer data in array form
            $customerData = [
                '_website'      => 'base',
                '_store'        => 'default',
                'email'         => $data['emailaddress'],
                'firstname'     => $data['fname'],
                'lastname'      => $data['lname'],
                'store_id'      => $storeId,
                'website_id'    => $websiteId,
                'password'      => null,
                'city'      => $data['city'],
                'country_id'      => $data['country_id'],
                'postcode'      => $data['postcode'],
                'region'      => $data['region_id'],
                'street'      => $data['street'],
                'telephone'      => $data['telephone'],
                'default_shipping' => '1',
                'default_billing' => '1',
            ];
            
            // save the customer data
            $this->customersImports->importCustomer($customerData);
        } catch (Exception $e) {
            $this->output->writeln(
                '<error>'. $e->getMessage() .'</error>',
                OutputInterface::OUTPUT_NORMAL
            );
        }
    }
}
