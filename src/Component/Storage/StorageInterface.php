<?php

namespace Component\Storage;

use Component\Preparer\UploadTicketRequest;

/**
 * Interface for storage services
 */
interface StorageInterface
{
    /**
     * Register a new spot to upload media to
     * @param UploadTicketRequest $uploadRequest     Upload Ticket Request
     * @param \DateTime           $uploadExpiration  Time when the upload slot will expire
     * @return string Id that the media will be referred to as
     */
    public function createUploadSpot(UploadTicketRequest $uploadRequest, \DateTime $uploadExpiration);

    /**
     * Get the media file by id
     * @param string $id Id identifying the media
     * @return Media Media object
     */
    public function getMediaById($id);
}
