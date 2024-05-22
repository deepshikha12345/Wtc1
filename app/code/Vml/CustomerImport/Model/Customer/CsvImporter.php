<?php
/**
 * @author Vijay Rami
 * @copyright Copyright © Vijay Rami. All rights reserved.
 * @package Customer Import Module for Magento 2.
 */

declare(strict_types=1);

namespace Vml\CustomerImport\Model\Customer;

use Vml\CustomerImport\Api\ImportInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\DirectoryList;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;

class CsvImporter implements ImportInterface
{
    /**
     * @var $keys
     */
    protected $keys;

    /**
     * CsvImporter constructor.
     * @param File $file
     * @param Csv $csv
     * @param DirectoryList $dir
     * @param LoggerInterface $logger
     */
    public function __construct(
        private File $file,
        protected Csv $csv,
        protected DirectoryList $dir,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getImportData(InputInterface $input): array
    {
        $file = $input->getArgument(ImportInterface::SOURCE);
        return $this->readData($file);
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     * @throws Exception
     * @throws LocalizedException
     */
    public function readData(string $file): array
    {
        try {
            $import_dir_path = $this->dir->getPath('var');
            $file_path = $import_dir_path.'/import1/'.$file;
            if (!$this->file->isExists($file_path)) {
                throw new LocalizedException(__('Invalid file path or no file found.'));
            }
            $this->csv->setDelimiter(",");
            $data = $this->csv->getData($file_path);
            $this->logger->info('CSV file is parsed');
        } catch (FileSystemException $e) {
            $this->logger->info($e->getMessage());
            throw new LocalizedException(__('File system exception' . $e->getMessage()));
        }

        return $this->formatData($data);
    }

    /**
     * Format Data
     *
     * @param array $data
     * @return array
     */
    public function formatData($data): array
    {
        //Removing headers
        $this->keys = array_shift($data);
        array_walk($data, function (&$v) {
            $v = array_combine($this->keys, $v);
        });

        return $data;
    }
}
