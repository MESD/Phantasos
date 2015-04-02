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

    /**
     * Mark a piece of media as having been uploaded
     * @param string $id   Media id
     * @param string $type Media type
     */
    public function markAsUploaded($id, $type);

    /**
     * Mark media as ready to be accessed by user
     * @param string  $id    Media id
     * @param boolean $ready Ready status (true by default)
     */
    public function markAsReady($id, $ready = true);
}
