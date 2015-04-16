<?php

namespace Component\Storage\Util\Converter;

use Component\Preparer\UploadTicketRequest;
use Component\Storage\Document\Media;

class UploadTicketRequestToMedia
{
    ////////////////////
    // STATIC METHODS //
    ////////////////////

    /**
     * Create a partial media document from an upload request
     * @param  UploadTicketRequest $uploadRequest Upload Ticket Request
     * @return Media Partial media document
     */
    public static function convert(UploadTicketRequest $uploadRequest)
    {
        // Create the document
        $media = new Media();

        // Set the uploaded to false
        $media->setUploaded(false);
        $media->setReady(false);

        // Pull in the others fields
        $media->setApplicationName($uploadRequest->getApplicationName());
        $media->setTags($uploadRequest->getTags());
        $media->setSecurityTags($uploadRequest->getSecurityTags());
        $media->setHideToOthers($uploadRequest->getHideToOthers());
        $media->setCallback($uploadRequest->getCallback());

        // Return the new document
        return $media;
    }
}
