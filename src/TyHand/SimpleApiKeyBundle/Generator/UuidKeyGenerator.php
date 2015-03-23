<?php

namespace TyHand\SimpleApiKeyBundle\Generator;

use TyHand\SimpleApiKeyBundle\Generator\KeyGeneratorInterface;

/**
 * Generates a key using uniqid()
 */
class UuidKeyGenerator implements KeyGeneratorInterface
{
    /////////////////////////////////////
    // KEY GENERATOR INTERFACE METHODS //
    /////////////////////////////////////

    /**
     * Generates an API key using PHP's built in UUID functionality
     * @return string UUID for API key
     */
    public function generateKey()
    {
        return str_replace('.', '', uniqid('', true));
    }
}
