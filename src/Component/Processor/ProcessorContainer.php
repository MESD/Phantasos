<?php

namespace Component\Processor;

use Component\Processor\Processors\AbstractProcessor;
use Component\Storage\FileInfo;
use Component\Storage\StorageInterface;

/**
 * Container for the processors
 */
class ProcessorContainer
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Map linking processors with content types
     * @var array
     */
    protected $processorMap;

    /**
     * Storage module
     * @var StorageInterface
     */
    protected $storage;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param StorageInterface $storage StorageInterface
     */
    public function __construct(StorageInterface $storage)
    {
        // Set
        $this->storage = $storage;

        // Init
        $this->processorMap = array();
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Get the content type
     * @param string $contentType Mime Type
     * @return string|null Media type or null if unsupported
     */
    public function getMediaType($contentType)
    {
        // Check if the processor is in the map
        if (array_key_exists($contentType, $this->processorMap)) {
            return $this->processorMap[$contentType]->getMediaType();
        } else {
            return null;  // Type is unsupported
        }
    }

    /**
     * Add a processor to the processor map
     * @param AbstractProcessor $processor Processor to add
     * @return self
     */
    public function addProcessor(AbstractProcessor $processor)
    {
        // Set the storage of the processor
        $processor->setStorage($this->storage);

        // Get the list of content types from the processor
        foreach($processor->getSupportedTypes() as $type)
        {
            $this->processorMap[$type] = $processor;
        }

        // Return
        return $this;
    }

    /**
     * Send a file to the appropiate processor
     * @param  FileInfo $file File to process
     * @return boolean Success of the processor
     */
    public function process(FileInfo $file)
    {
        if (array_key_exists(
                $file->getMediaFile()->getContentType(),
                $this->processorMap)) {
            return $this
                ->processorMap[$file->getMediaFile()->getContentType()]
                ->process($file)
            ;
        } else {
            throw NoProcessorForFileTypeException(
                $file->getMediaFile()->getContentType()
            );
        }
    }

}
