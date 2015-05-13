<?php

namespace Component\Processor\Processors;

use Component\Processor\Processors\AbstractProcessor;
use Component\Processor\StatusManager;
use Component\Storage\FileInfo;
use Component\Preparer\Types\MediaTypes;

/**
 * Processor for PDFs (currently a pass thru)
 */
class PdfProcessor extends AbstractProcessor
{
    /////////////////////////
    // IMPLEMENTED METHODS //
    /////////////////////////

    /**
     * Get an array of supported mime types by this processor
     * @return array List of supported mime types
     */
    public function getSupportedTypes()
    {
        return array(
            'application/pdf',
            'application/x-pdf'
        );
    }

    /**
     * Get the media type that this processor handles
     * @return string Media type
     */
    public function getMediaType()
    {
        return MediaTypes::PDF;
    }


    /**
     * Process the media file
     * @param  FileInfo $file    Original File
     * @return boolean          Operation success
     */
    public function process(FileInfo $file)
    {
        // Create a new status manager
        $statusManager = new StatusManager(
            $file->getMediaId(),
            $this->storage,
            1
        );

        // Start and end the phase
        $statusManager->startNewPhase();
        $statusManager->endPhase();

        // Mark the pdf as ready
        $this->storage->markAsReady($file->getMediaId());

        // Complete
        return true;
    }
}
