# Wtc_CustomerImport
Import Customer with command in CSV or JSON format

# Magento2 Module Wtc CustomerImport

## Functionalities
Import Customer with command  via CSV or JSON format.

## Installation
### Via Zip file

 - Unzip the zip file in 'app/code/Wtc/CustomerImport'
 - Enable the module by running 'php bin/magento module:enable Wtc_CustomerImport'
 - Run Magento commands

   'php bin/magento settp:upgrade && php bin/magento setup:di:compile && php bin/magento setup:static-content:deploy -f && php bin/magento cache:clean && php bin/magento cache:flush'

### Via Composer

 - Install the module composer by running 'composer require wtc/magento2-module-customerimport'
 - Enable the module by running 'php bin/magento module:enable Wtc_CustomerImport'
 - Run Magento commands

   'php bin/magento settp:upgrade && php bin/magento setup:di:compile && php bin/magento setup:static-content:deploy -f && php bin/magento cache:clean && php bin/magento cache:flush'

  'Give 'var' and 'pub' folder to 777 permission'


## Configuration

'php bin/magento customer:import <profile> <source>'

##### 'profile' is 'sample-csv' or 'sample-json'

##### 'source' is your file path name added in 'var/import' folder (eg. sample.csv or sample.json)

*    'php bin/magento customer:import --help'
    
*    Description:
      Customers Import via CSV & JSON uploaded file

*    Usage:
      customer:import <profile> <source>

*    Arguments:
      profile               Profile name ex: sample-csv or sample-json
      source                Source Path ex: sample.csv or sample.json
  
    
    bin/magento customer:import sample-csv sample.csv
    bin/magento customer:import sample-json sample.json
    
*   sample.csv and sample.json files can find inside 'files' folder.

Once we run our customer import script, we also need to make sure to re-index the Customer Grid indexer

    'php bin/magento indexer:reindex customer_grid'

## Features

Import customers from CSV or JSON file from command.

