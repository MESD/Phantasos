<?php

namespace TyHand\SimpleApiKeyBundle\Generator;

/**
 * Interface for an API key generator
 */
interface KeyGeneratorInterface
{
    /**
     * Generate a new unique API key
     * @return string
     */
    public function generateKey();
}
