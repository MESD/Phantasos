<?php

namespace Component\Processor\Processors;

use Component\Storage\StorageInterface;
use Component\Storage\FileInfo;

/**
 * Base processor
 */
abstract class AbstractProcessor
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Storage Module
     * @var StorageInterface
     */
    protected $storage;

    /////////////
    // METHODS //
    /////////////

    /**
     * Set the storage module
     * @param StorageInterface $storage Storage module
     * @return self
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    //////////////////////
    // ABSTRACT METHODS //
    //////////////////////

    /**
     * Get an array of supported mime types by this processor
     * @return array List of supported mime types
     */
    public abstract function getSupportedTypes();

    /**
     * Get the media type that this processor handles
     */
    public abstract function getMediaType();

    /**
     * Process the media file
     * @param  FileInfo $file    Original File
     * @return boolean          Operation success
     */
    public abstract function process(FileInfo $file);
}
