<?php

namespace Component\Storage\FileKey;

/**
 * Interface for file key tools
 */
interface FileKeyInterface
{
    /**
     * Get the base file key for a media id
     * @param string $mediaId Media id to get the base file key for
     * @return string Base file key
     */
    public function getBaseFileKey($mediaId);
}
