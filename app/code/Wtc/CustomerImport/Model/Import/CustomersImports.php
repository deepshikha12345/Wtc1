<?php

/**
 * Import Customer Data
 *
 */

namespace Wtc\CustomerImport\Model\Import;
 
use Magento\CustomerImportExport\Model\Import\Customer;
 
class CustomersImports extends Customer
{
    /**
     * Function to Import Customer Data
     *
     */
    public function importCustomer(array $rowData)
    {
        $this->prepareCustomerData($rowData);
        $entityCreate = [];
        $entityUpdate = [];
        $entityDelete = [];
        
        $proceedData = $this->_prepareDataForUpdate($rowData);
        $entityCreate = array_merge($entityCreate, $proceedData[self::ENTITIES_TO_CREATE_KEY]);
        $entityUpdate = array_merge($entityUpdate, $proceedData[self::ENTITIES_TO_UPDATE_KEY]);

        $this->updateItemsCounterStats($entityCreate, $entityUpdate, $entityDelete); //UpdateItemcounter using entiry
        
        /**
        * Save prepared data
        */
        if ($entityCreate || $entityUpdate) {
            $this->_saveCustomerEntities($entityCreate, $entityUpdate);
        }
        
        return $entityCreate[0]['entity_id'] ?? $entityUpdate[0]['entity_id'] ?? null;
    }
}
