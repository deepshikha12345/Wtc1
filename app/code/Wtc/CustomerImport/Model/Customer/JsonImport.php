<?php
namespace Wtc\CustomerImport\Model\Customer;

use Wtc\CustomerImport\Api\ImportInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;

class JsonImport implements ImportInterface
{
    /**
     * CsvImporter constructor.
     * @param File $file
     * @param DirectoryList $directory
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        private File $file,
        protected DirectoryList $directory,
        private SerializerInterface $serializer,
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
                throw new LocalizedException(__('Invalid file path or no file found.'));
            }
            $data = $this->file->fileGetContents($file_path);
            $this->logger->info('JSON file is parsed');
        } catch (FileSystemException $e) {
            $this->logger->info($e->getMessage());
            throw new LocalizedException(__('File system exception' . $e->getMessage()));
        }

        return $this->formatCustomerData($data);
    }

    /**
     * Format Data
     *
     * @param string $data
     * @return array
     */
    public function formatCustomerData($data): array
    {
        return $this->serializer->unserialize($data);
    }
}
