<?php

namespace Component\Exceptions\Api;

use Component\Exceptions\RuntimeException;

class MissingRouteException extends RuntimeException
{
    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param Exception $previous       Previous exception if exists
     */
    public function __construct(\Exception $previous = null)
    {
        // Call the super constructor
        parent::__construct(
            'The API frontend needs to give the upload ticket a route.',
            0,
            $previous
        );
    }
}
