<?php

namespace Component\Processor;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ProcessorInterface
{
    /**
     * Get a list of mime types the processor module can handle
     * @return array List of mime types that the processor can handle
     */
    public function getSupportedTypes();

    /**
     * Process an uploaded file
     * @param UploadedFile $original File to process
     * @param string       $mediaId  Media id of the upload
     * @return boolean True if the file is placed into a work queue
     */
    public function processFile(UploadedFile $original, $mediaId);
}
