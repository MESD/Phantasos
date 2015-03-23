<?php

namespace TyHand\SimpleApiKeyBundle\Generator;

use TyHand\SimpleApiKeyBundle\Generator\KeyGeneratorInterface;
use TyHand\SimpleApiKeyBundle\Exception\KeyGeneratorNameInUseException;
use TyHand\SimpleApiKeyBundle\Exception\KeyGeneratorNameNotExistException;
use TyHand\SimpleApiKeyBundle\Exception\NoDefaultGeneratorException;

/**
 * Manages the key generators
 *
 * @author Tyler Hand <https://github.com/tyhand>
 */
class KeyGeneratorManager
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * The array of generators
     * @var array
     */
    private $generators;

    /**
     * The key of the default generator in the generators array
     * @var string
     */
    private $defaultGenerator;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     */
    public function __construct()
    {
        // Init the properties
        $this->generators = array();
        $this->defaultGenerator = null;
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Adds a key generator to this manager
     * @param KeyGeneratorInterface $generator Generator to add
     * @param string                $name      Name to refer to the generator by
     * @param boolean               $default   Whether this is the default
     */
    public function addKeyGenerator(
        KeyGeneratorInterface $generator,
        $name,
        $default = false)
    {
        // Check that the given name is not already in use
        if (array_key_exists($name, $this->generators)) {
            throw new KeyGeneratorNameInUseException($name);
        }

        // Add the generator
        $this->generators[$name] = $generator;

        // If default is true, set as the new default
        if ($default) {
            $this->defaultGenerator = $name;
        }
    }

    /**
     * Get a key generator by name
     * @param string $name Name of the generator to get, or null for default
     */
    public function getKeyGenerator($name = null)
    {
        // If the name is null, get the default
        if (null === $name) {
            // Check that a default generator exists
            if (null === $this->defaultGenerator) {
                throw new NoDefaultGeneratorException();
            } else {
                $name = $this->defaultGenerator;
            }
        }

        // Check that the name is valid
        if (!(array_key_exists($name, $this->generators))) {
            throw new KeyGeneratorNameNotExistException($name);
        }

        // Get and return the generator
        return $this->generators[$name];
    }

    /**
     * Returns a list of the key generator names in this manager
     * @return array The list of key generator names
     */
    public function getListOfKeyGeneratorNames()
    {
        return array_keys($this->generators);
    }

    /**
     * Check if the given name exist in the manager
     * @param string $name Name to check
     * @return boolean Whether the name refers to a key generator in the manager
     */
    public function doesNameExist($name)
    {
        return array_key_exists($name, $this->generators);
    }

    /**
     * Get the name of the default key generator
     */
    public function getDefaultKeyGeneratorName()
    {
        return $this->defaultGenerator;
    }
}
