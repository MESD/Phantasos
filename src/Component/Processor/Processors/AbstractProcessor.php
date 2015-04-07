<?php

namespace Component\Processor\Processors;

use Component\Storage\StorageInterface;
use Component\Storage\FileInfo;

/**
 * Base processor
 */
abstract AbstractProcessor
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

    /**
     * Constructor
     * @param StorageInterface $storage Storage Module
     */
    public function __construct(StorageInterface $storage)
    {
        // Set
        $this->storage = $storage;
    }

    //////////////////////
    // ABSTRACT METHODS //
    //////////////////////

    /**
     * Process the media file
     * @param  FileInfo $file    Original File
     * @return boolean          Operation success
     */
    public abstract function process(FileInfo $file);
}
