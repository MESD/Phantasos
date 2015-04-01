<?php

namespace Component\Exceptions\Files;

use Component\Exceptions\RuntimeException;

class UnsupportedFileTypeException extends RuntimeException
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * File type that caused the exception
     * @var string
     */
    private $fileType;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param string    $fileType File type causing the exception
     * @param Exception $previous Previous exception if exists
     */
    public function __construct($fileType, \Exception $previous = null)
    {
        // Call the super constructor
        parent::__construct(
            sprintf(
                'Filetype "%s" is not supported.',
                $fileType
            ),
            0,
            $previous
        );

        // Set the properties
        $this->fileType = $fileType;
    }

    /////////////
    // GETTERS //
    /////////////

    /**
     * Get the value of File type that caused the exception
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }
}
