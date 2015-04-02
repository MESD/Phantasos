<?php

namespace Component\Preparer;

use Component\Preparer\UploadTicketRequest;
use Component\Storage\Document\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PreparerInterface
{
    /**
     * Request an upload ticket
     * @param UploadTicketRequest $uploadRequest Upload Ticket Request
     */
    public function requestUploadTicket(UploadTicketRequest $uploadRequest);

    /**
     * Handle upload
     * @param UploadedFile $file    Uploaded file
     * @param string       $mediaId Id of the media
     * @return boolean Whether the module was successful in handling the file
     */
    public function handleUpload(UploadedFile $file, $mediaId);
}
