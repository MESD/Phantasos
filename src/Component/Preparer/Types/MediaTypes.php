<?php

namespace Component\Preparer\Types;

/**
 * Basic enum-ish class for media types
 */
class MediaTypes
{
    ///////////////
    // CONSTANTS //
    ///////////////

    const VIDEO = 'video';
    const AUDIO = 'audio';
    const DOCUMENT = 'document';
    const UNKNOWN = 'unkown';

    ////////////////////
    // STATIC METHODS //
    ////////////////////

    /**
     * Determine which media type to set by the mime type of the file
     * @param string $mimeType Mime type of the file
     * @return string Media type
     */
    public static function determineFromMimeType($mimeType)
    {
        /**
         * @TODO fill this out
         */

        // If not in the list, return unknown
        return self::UNKNOWN;
    }
}
