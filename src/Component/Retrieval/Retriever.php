<?php

namespace Component\Retrieval;

use Component\Retrieval\RetrievalInterface;
use Component\Storage\StorageInterface;

class Retriever implements RetrievalInterface
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Storage Module
     * @var StorageInterface
     */
    private $storage;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param StorageInterface $storage Storage module
     */
    public function __construct(StorageInterface $storage)
    {
        // Set
        $this->storage = $storage;
    }

    ///////////////////////
    // INTERFACE METHODS //
    ///////////////////////


    
}
