<?php

namespace Wtc\CustomerImport\Model\Customer;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Wtc\CustomerImport\Api\ImportInterface;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\DirectoryList;
use Symfony\Component\Console\Input\InputInterface;
use Psr\Log\LoggerInterface;

class CsvImport implements ImportInterface
{
    /**
     * @var $keys
     */
    protected $keys;

    /**
     * CsvImporter constructor.
     * @param File $file
     * @param Csv $csv
     * @param DirectoryList $directory
     * @param LoggerInterface $logger
     */
    public function __construct(
        private File $file,
        protected Csv $csv,
        protected DirectoryList $directory,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getCustomerImportData(InputInterface $input): array
    {
        $file = $input->getArgument(ImportInterface::SOURCE);
        return $this->readfileData($file);
    }

    public function readfileData(string $file): array
    {
        try {
            $import_directory_path = $this->directory->getPath('var');
            $file_path = $import_directory_path.'/import/'.$file;
            if (!$this->file->isExists($file_path)) {
                throw new LocalizedException(__('Invalid file path OR no file found.'));
            }
            $this->csv->setDelimiter(",");
            $data = $this->csv->getData($file_path);
            $this->logger->info('CSV file is parsed');
        } catch (FileSystemException $e) {
            $this->logger->info($e->getMessage());
            throw new LocalizedException(__('File system exception' . $e->getMessage()));
        }

        return $this->formatCustomerData($data);
    }

    /**
     * Format Data
     *
     * @param array $data
     * @return array
     */
    public function formatCustomerData($data): array
    {
        //Removing headers
        $this->keys = array_shift($data);
        array_walk($data, function (&$v) {
            $v = array_combine($this->keys, $v);
        });

        return $data;
    }
}
