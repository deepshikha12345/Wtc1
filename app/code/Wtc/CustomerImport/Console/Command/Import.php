<?php
namespace Wtc\CustomerImport\Console\Command;

use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\InputException;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wtc\CustomerImport\Api\ImportInterface;
use Wtc\CustomerImport\Model\Customers;
use Wtc\CustomerImport\Model\Customer\CsvImportFactory;
use Wtc\CustomerImport\Model\Customer\JsonImportFactory;

class Import extends Command
{
    /**
     * @var ImportInterface
     */
    protected $Import;

    /**
     * Import constructor.
     *
     * @param Customers $customers
     * @param StoreManagerInterface $storeManager
     * @param CsvImportFactory $csvImportfactory
     * @param JsonImportFactory $jsonimportfactory
     */
    public function __construct(
        private Customers $customers,
        protected StoreManagerInterface $storeManager,
        private CsvImportFactory $csvImportfactory,
        private JsonImportFactory $jsonimportfactory
    ) {
        parent::__construct();
    }

    /**
     * @configure
     */
    protected function configure(): void
    {
        $this->setName("customers:import");
        $this->setDescription("Customers Import via CSV & JSON file");
        $this->setDefinition([ new InputArgument(
            ImportInterface::PROFILE,
            InputArgument::REQUIRED,
            "Profile name sample-csv or sample-json"
        ),
                            new InputArgument(
                                ImportInterface::SOURCE,
                                InputArgument::REQUIRED,
                                "Source Path sample.csv or sample.json"
                            )
                ]);
        parent::configure();
    }

    /**
     * @execute function
     */
    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $proType = $input->getArgument(ImportInterface::PROFILE);
        $filePath = $input->getArgument(ImportInterface::SOURCE);

        $output->writeln(sprintf("Import Customer Data Below : "));
        $output->writeln(sprintf("Profile : %s", $proType));
        $output->writeln(sprintf("Source : %s", $filePath));

        try {

            if ($customerimportData = $this->getCustomerImportDataInstance($proType)->getCustomerImportData($input)) {
                $storeId = $this->storeManager->getStore()->getId();
                $websiteId = $this->storeManager->getStore($storeId)->getWebsiteId();
                
                foreach ($customerimportData as $data) {
                    $this->customers->createCustomers($data, $websiteId, $storeId);
                }

                $output->writeln(sprintf("Total %s Customers are Successfully imported in magento", count($customerimportData)));
                return Cli::RETURN_SUCCESS;
            }

            return Cli::RETURN_FAILURE;
   
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $output->writeln("<error class='error'>$msg</error>", OutputInterface::OUTPUT_NORMAL);
            return Cli::RETURN_FAILURE;
        }
    }

    /**
     * Get Instance of class as per profile type.
     */
    protected function getCustomerImportDataInstance($proType): ImportInterface
    {
        if (!($this->Import instanceof ImportInterface)) {
            if ($proType === "sample-csv") {
                $class = $this->csvImportfactory->create();
            } elseif ($proType === "sample-json") {
                $class = $this->jsonimportfactory->create();
            } else {
                throw new InputException(__('Profile type %1 Not Supported', $type));
            }
        }
        return $class;
    }
}
