<?php

namespace Component\Processor;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ProcessorQueuerInterface
{
    /**
     * Get the media type from the mime type
     * @param string $mimeType Mime type of the uploaded file
     * @return string|null Media type or null if unsupported
     */
    public function getMediaType($mimeType);

    /**
     * Queue an uploaded file for processing
     * @param UploadedFile $original File to process
     * @param string       $mediaId  Media id of the upload
     * @return boolean True if the file is placed into a work queue
     */
    public function queueFile(UploadedFile $original, $mediaId);
}
