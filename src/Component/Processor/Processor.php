<?php

namespace Component\Processor;

use Component\Processor\ProcessorInterface;
use Component\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Processor implements ProcessorInterface
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Storage Module
     * @var StorageInterface
     */
    protected $storage;

    /////////////////
    // CONSTRUCTOR //
    /////////////////


    public function __construct(StorageInterface $storage)
    {
        // Set the properties
        $this->storage = $storage;
    }

    ///////////////////////
    // INTERFACE METHODS //
    ///////////////////////

    /**
     * Get a list of mime types the processor module can handle
     * @return array List of mime types that the processor can handle
     */
    public function getSupportedTypes()
    {
        return array(
            'image/jpeg'
        );
    }

    /**
     * Process an uploaded file
     * @param UploadedFile $original File to process
     * @param string       $mediaId  Media id of the upload
     * @return boolean True if the file is placed into a work queue
     */
    public function processFile(UploadedFile $original, $mediaId)
    {
        // Find a queue for the file

        // Place into the queue

        // Return that the file is going to be worked on
        return true;
    }
}
