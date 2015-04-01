<?php

namespace Component\Exceptions\Api;

use Component\Exceptions\RuntimeException;

class MissingParameterException extends RuntimeException
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * The name of the missing parameter name
     * @var string
     */
    private $parameterName;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param string    $parameterName  Parameter name that is missing
     * @param Exception $previous       Previous exception if exists
     */
    public function __construct($parameterName, \Exception $previous = null)
    {
        // Call the super constructor
        parent::__construct(
            sprintf(
                'The parameter "%s" required for this action was not given',
                $parameterName
            ),
            0,
            $previous
        );

        // Set the parameters
        $this->parameterName = $parameterName;
    }

    /////////////
    // GETTERS //
    /////////////

    /**
     * Get the value of The name of the missing parameter name
     * @return string
     */
    public function getParameterName()
    {
        return $this->parameterName;
    }
}
