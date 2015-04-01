<?php

namespace Component\Processor;

use Component\Processor\ProcessorInterface;
use Component\Storage\StorageInterface;

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

    }
}
