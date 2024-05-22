<?php
/**
 * @author Vijay Rami
 * @copyright Copyright © Vijay Rami. All rights reserved.
 * @package Customer Import Module for Magento 2.
 */

declare(strict_types=1);

namespace Vml\CustomerImport\Console\Command;

use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Filesystem;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vml\CustomerImport\Api\ImportInterface;
use Vml\CustomerImport\Model\Customer\CsvImporterFactory;
use Vml\CustomerImport\Model\Customer\JsonImporterFactory;
use Vml\CustomerImport\Model\Customer;

class ImportCustomer extends Command
{
    /**
     * @var ImportInterface
     */
    protected $importer;

    /**
     * CustomerImport constructor.
     *
     * @param Customer $customer
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     * @param State $state
     * @param CsvImporterFactory $csvimporterfactory
     * @param JsonImporterFactory $jsonimporterfactory
     */
    public function __construct(
        private Customer $customer,
        protected StoreManagerInterface $storeManager,
        private Filesystem $filesystem,
        private State $state,
        private CsvImporterFactory $csvimporterfactory,
        private JsonImporterFactory $jsonimporterfactory
    ) {
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName("customer:import");
        $this->setDescription("Customer Import via CSV & JSON");
        $this->setDefinition([
            new InputArgument(
                ImportInterface::PROFILE_NAME,
                InputArgument::REQUIRED,
                "Profile name ex: sample-csv or sample-json"
            ),
            new InputArgument(
                ImportInterface::SOURCE,
                InputArgument::REQUIRED,
                "Source Path ex: sample.csv or sample.json"
            )
        ]);
        parent::configure();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $profileType = $input->getArgument(ImportInterface::PROFILE_NAME);
        $filePath = $input->getArgument(ImportInterface::SOURCE);
        $output->writeln(sprintf("Profile type: %s", $profileType));
        $output->writeln(sprintf("Source Path: %s", $filePath));

        try {
            $this->state->setAreaCode(Area::AREA_GLOBAL);

            if ($importData = $this->getImporterInstance($profileType)->getImportData($input)) {
                $storeId = (int)$this->storeManager->getStore()->getId();
                $websiteId = (int)$this->storeManager->getStore($storeId)->getWebsiteId();
                
                foreach ($importData as $data) {
                    $this->customer->createCustomer($data, $websiteId, $storeId);
                }

                $output->writeln(sprintf("Total of %s Customers are imported", count($importData)));
                return Cli::RETURN_SUCCESS;
            }

            return Cli::RETURN_FAILURE;
   
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $output->writeln("<error>$msg</error>", OutputInterface::OUTPUT_NORMAL);
            return Cli::RETURN_FAILURE;
        }
    }

    /**
     * Get Instance of class as per profile type.
     *
     * @param string $profileType
     * @return ImportInterface
     */
    protected function getImporterInstance($profileType): ImportInterface
    {
        if (!($this->importer instanceof ImportInterface)) {
            if ($profileType === "sample-csv") {
                $class = $this->csvimporterfactory->create();
            } elseif ($profileType === "sample-json") {
                $class = $this->jsonimporterfactory->create();
            } else {
                throw new InputException(__('Unsupported Profile type specified %1', $type));
            }
        }
        return $class;
    }
}
