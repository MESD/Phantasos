<?php

namespace Component\Retrieval;

use Component\Retrieval\RetrievalInterface;
use Component\Storage\StorageInterface;

class Retrieval implements RetrievalInterface
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Storage Module
     * @var StorageInterface
     */
    private $storage;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param StorageInterface $storage Storage module
     */
    public function __construct(StorageInterface $storage)
    {
        // Set
        $this->storage = $storage;
    }

    ///////////////////////
    // INTERFACE METHODS //
    ///////////////////////

    /**
     * Get the path to the file for a given media
     * @param string $mediaFileId Media file to get
     * @param string $applicationName Name of the requesting application
     * @return FilePath File path
     */
    public function getFilePath($mediaFileId, $applicationName)
    {
        // Get the file info
        $fileInfo = $this->storage->getFileInfo($mediaFileId);
        if (null === $fileInfo) {
            return null;
        }

        // Check that the requesting application can view it
        if (!$fileInfo->canBeViewedBy($applicationName)) {
            return null;
        }

        // Return the file path
        return new FilePath(
            $fileInfo->getFullFilePath(),
            $fileInfo->getContentType()
        );
    }

    /**
     * Get the media info for the requested media
     * @param string $mediaId         Id of the media
     * @param string $applicationName Name of the requesting application
     * @return MediaInfo Info
     */
    public function getMediaInfo($mediaId, $applicationName)
    {
        // Get the media info from storage
        $mediaInfo = $this->storage->getMediaInfo($mediaId);

        // Check that the requesting application can view this
        if ($mediaInfo->getHideToOthers()) {
            if ($mediaInfo->getApplicationName() !== $applicationName) {
                return null;
            }
        }

        // Return
        return $mediaInfo;
    }
}
