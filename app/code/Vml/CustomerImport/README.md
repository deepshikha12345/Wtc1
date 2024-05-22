# Vml_CustomerImport
Import Customer with cron command in CSV or JSON format

# Magento2 Module Vml CustomerImport

    ``vml/magento2-module-customerimport``

 - [Main Functionalities](#main-functionalities)
 - [Installation](#installation)
 - [Features](#features)
 - [Configurations](#configurations)
 - [Screenshots](#screenshots)


## Main Functionalities
Import Customer with cron command which is supplied via CSV or JSON format.

## Installation
### Type 1: Zip file

 - Unzip the zip file in `app/code/Vml/CustomerImport`
 - Enable the module by running `php bin/magento module:enable Vml_CustomerImport`
 - Run Magento commands by running

   `php bin/magento set:upg && php bin/magento set:d:c && php bin/magento set:s:d -f && php bin/magento c:c && php bin/magento c:f`
 - Give `var` and `pub` folder to 777 permission by running `chmmod -R 0777 var/ pub/`

### Type 2: Composer

 - Install the module composer by running `composer require vml/magento2-module-customerimport`
 - Enable the module by running `php bin/magento module:enable Vml_CustomerImport`
 - Run Magento commands by running

   `php bin/magento set:upg && php bin/magento set:d:c && php bin/magento set:s:d -f && php bin/magento c:c && php bin/magento c:f`
 - Give `var` and `pub` folder to 777 permission by running `chmmod -R 0777 var/ pub/`


## Features

Create customers from CSV or JSON file from command line terminal.

## Configurations

`bin/magento customer:import <profile-name> <source>`

##### So to import from the CSV and the JSON respectively the user would execute either one of the following

##### `profile-name` is 'sample-csv' or 'sample-json'

##### `source` is your file path name added in `var/import` folder (eg. 'sample.csv' or 'sample.json')

*    `php bin/magento customer:import --help`
    
*    Description:
      Customer Import via CSV & JSON

*    Usage:
      customer:import <profile> <source>

*    Arguments:
      profile               Profile name ex: sample-csv or sample-json
      source                Source Path ex: sample.csv or sample.json
  
    
    bin/magento customer:import sample-csv sample.csv
    bin/magento customer:import sample-json sample.json
    
*    [csv](/assests/sample.csv) and [json](/assests/sample.json) files you can find inside `assests` folder of this module.

Once we run our customer import script, we also need to make sure to re-index the Customer Grid indexer

    `php bin/magento indexer:reindex customer_grid`

## Screenshots

![cli-help](/assests/image.png)
