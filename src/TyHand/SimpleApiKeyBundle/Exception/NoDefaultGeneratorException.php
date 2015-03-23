<?php

namespace TyHand\SimpleApiKeyBundle\Exception;

/**
 * Exception for when the key generator manager looks for the default key
 * generator but it does not exist
 *
 * @author Tyler Hand <https://github.com/tyhand>
 */
class NoDefaultGeneratorException extends RuntimeException
{
    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param Exception $previous Previous exception if exists
     */
    public function __construct(\Exception $previous = null)
    {
        // Call the parent constructor
        parent::__construct(
            'No default key generator defined.',
            0,
            $previous
        );
    }
}
