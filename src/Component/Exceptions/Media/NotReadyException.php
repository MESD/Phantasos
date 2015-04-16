<?php

namespace Component\Exceptions\Media;

use Component\Exceptions\RuntimeException;

class NotReadyException extends RuntimeException
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Media id that caused the exception
     * @var string
     */
    private $mediaId;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param string    $mediaId Media id that caused the exception
     * @param Exception $previous Previous exception if exists
     */
    public function __construct($mediaId, \Exception $previous = null)
    {
        // Call the super constructor
        parent::__construct(
            sprintf(
                'Media by the id "%s" is still processing and not yet ready.',
                $mediaId
            ),
            0,
            $previous
        );

        // Set the properties
        $this->mediaId = $mediaId;
    }

    /////////////
    // GETTERS //
    /////////////

    /**
     * Get the value of Media id that caused the exception
     * @return string
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }
}
