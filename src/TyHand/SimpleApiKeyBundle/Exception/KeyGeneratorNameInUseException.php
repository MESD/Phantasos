<?php

namespace TyHand\SimpleApiKeyBundle\Exception;

/**
 * Exception for when the key generator is given a new generator with an
 * existing name
 *
 * @author Tyler Hand <https://github.com/tyhand>
 */
class KeyGeneratorNameInUseException extends RuntimeException
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * The key generator name that
     * @var string
     */
    private $keyName;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param string    $keyName  The key name that caused the exception
     * @param Exception $previous Previous exception if exists
     */
    public function __construct($keyName, \Exception $previous = null)
    {
        // Call the parent constructor
        parent::__construct(
            sprintf(
                'The key generator name "%s" is already in use in the manager',
                $keyName
            ),
            0,
            $previous
        );

        // Set the properties
        $this->keyName = $keyName;
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
