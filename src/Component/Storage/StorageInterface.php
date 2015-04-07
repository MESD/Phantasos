<?php

namespace Component\Storage;

use Component\Preparer\UploadTicketRequest;
use Component\Storage\FileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    /**
     * Add the original file
     * @param UploadedFile $original Uploaded File
     * @param string       $mediaId  Media id
     * @return boolean Operation success
     */
    public function addOriginalFile(UploadedFile $original, $mediaId);

    /**
     * Get the original file for a media id
     * @param string $mediaId Media Id
     * @return FileInfo Original file if exists
     */
    public function getOriginalFile($mediaId);

}
