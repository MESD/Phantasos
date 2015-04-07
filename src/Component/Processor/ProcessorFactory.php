<?php

namespace Component\Processor;

use Component\Storage\StorageInterface;

/**
 * Builds the necessary processor
 */
class ProcessorFactory
{
    /////////////
    // METHODS //
    /////////////

    /**
     * Build the necessary processor for the given media type
     * @param  string           $mimeType Mimetype for the processor
     * @param  StorageInterface $storage  Storage Module
     * @return AbstractProcessor          Processor
     */
    public function build($mimeType, StorageInterface $storage)
    {

    }
}
