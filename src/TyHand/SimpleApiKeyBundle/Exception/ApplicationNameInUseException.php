<?php

namespace TyHand\SimpleApiKeyBundle\Exception;

/**
 * Exception for when a new API user is attempted to be added using an existing
 * Application Name
 *
 * @author Tyler Hand <https://github.com/tyhand>
 */
class ApplicationNameInUseException extends RuntimeException
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Application Name that caused the exception
     * @var string
     */
    private $applicationName;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param string    $applicationName  The app name that caused the exception
     * @param Exception $previous         Previous exception if exists
     */
    public function __construct($applicationName, \Exception $previous = null)
    {
        // Call the parent constructor
        parent::__construct(
            sprintf(
                'The application name "%s" already has a registered API key',
                $applicationName
            ),
            0,
            $previous
        );

        // Set the properties
        $this->applicationName = $applicationName;
    }

    /////////////
    // GETTERS //
    /////////////

    /**
     * Get the key generator name that caused the exception
     * @return string
     */
    public function getKeyGeneratorName()
    {
        return $this->keyName;
    }
}
