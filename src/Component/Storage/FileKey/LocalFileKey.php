<?php

namespace Component\Storage\FileKey;

use Component\Storage\FileKey\FileKeyInterface;

/**
 * Creates a file key for a local file structure
 */
class LocalFileKey implements FileKeyInterface
{
    ///////////////////////
    // INTERFACE METHODS //
    ///////////////////////

    /**
     * Get the base file key for a media id
     * @param string $mediaId Media id to get the base file key for
     * @return string Base file key
     */
    public function getBaseFileKey($mediaId)
    {
        // Take the media id and split it into 3 chunks of size 5
        return substr($mediaId,  0, 5) . '/' .
               substr($mediaId,  5, 5) . '/' .
               substr($mediaId, 10, 5) . '/' .
               $mediaId . '/';
    }
}
