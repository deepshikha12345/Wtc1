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
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;

class JsonImporter implements ImportInterface
{
    /**
     * CsvImporter constructor.
     * @param File $file
     * @param DirectoryList $dir
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        private File $file,
        protected DirectoryList $dir,
        private SerializerInterface $serializer,
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
     */
    public function readData(string $file): array
    {
        try {
            $import_dir_path = $this->dir->getPath('var');
            $file_path = $import_dir_path.'/import1/'.$file;
            if (!$this->file->isExists($file_path)) {
                throw new LocalizedException(__('Invalid file path or no file found.'));
            }
            $data = $this->file->fileGetContents($file_path);
            $this->logger->info('JSON file is parsed');
        } catch (FileSystemException $e) {
            $this->logger->info($e->getMessage());
            throw new LocalizedException(__('File system exception' . $e->getMessage()));
        }

        return $this->formatData($data);
    }

    /**
     * Format Data
     *
     * @param string $data
     * @return array
     */
    public function formatData($data): array
    {
        return $this->serializer->unserialize($data);
    }
}
