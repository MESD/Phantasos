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
    public function getOriginalFileInfo($mediaId);

    /**
     * Get file info for a file by its own id
     * @param string $mediaFileId Id for the media file
     * @return FileInfo File info for the file
     */
    public function getFileInfo($mediaFileId);

    /**
     * Get the info on a media file
     * @param string $mediaId Id of the media
     * @return MediaInfo Info
     */
    public function getMediaInfo($mediaId);

    /**
     * Update the status of media
     * @param string  $mediaId    Media id of the media to update
     * @param string  $status     Status to set
     */
    public function updateMediaStatus($mediaId, $status);

    /**
     * Update the percentage for the media
     * @param string $mediaId    Media id of the media to update
     * @param float  $percentage Percentage complete
     */
    public function updateMediaPercentage($mediaId, $percentage);

    /**
     * Change the media type (for use in cases where the MIME type was misleading)
     * @param string $mediaId Media Id
     * @param string $type    New media type
     */
    public function changeMediaType($mediaId, $type);

    /**
     * Return a list of medias that failed to process
     * @return array List of failures
     */
    public function getFailedMedia();

    /**
     * Add a file to the database
     * @param string $mediaId     Media Id
     * @param string $fullPath    Full file path
     * @param string $name        File name
     * @param string $contentType Mime type
     * @param int    $width       Width if applicable
     * @param int    $height      Height if applicable
     * @param string $bitRate     Bitrate if applicable
     */
    public function addFile(
        $mediaId,
        $fullPath,
        $name,
        $contentType,
        $width = null,
        $height = null,
        $bitRate = null);
}
