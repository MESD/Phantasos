<?php

namespace Component\Retrieval;

interface RetrievalInterface
{
    /**
     * Get the path to the file for a given media
     * @param string $mediaFileId Media file to get
     * @param string $applicationName Name of the requesting application
     * @return FilePath File path
     */
    public function getFilePath($mediaFileId, $applicationName);

    /**
     * Get the media info for the requested media
     * @param string $mediaId         Id of the media
     * @param string $applicationName Name of the requesting application
     * @return MediaInfo Info
     */
    public function getMediaInfo($mediaId, $applicationName);
}
