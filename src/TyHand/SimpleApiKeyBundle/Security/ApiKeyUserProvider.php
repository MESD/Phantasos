<?php

namespace TyHand\SimpleApiKeyBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use TyHand\SimpleApiKeyBundle\Storage\StorageConfigurationHandler;

/**
 * User provider for api keys
 *    Taken from the Symfony example
 */
class ApiKeyUserProvider implements UserProviderInterface
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Storage Config Handler for getting the storage service
     * @var StorageConfigurationHandler
     */
    private $storageHandler;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param StorageConfigurationHandler $storageHandler Storage Config Handler
     */
    public function __construct(StorageConfigurationHandler $storageHandler)
    {
        // Set the properties
        $this->storageHandler = $storageHandler;
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Gets the application name for the given api key
     * @param string $apiKey API key
     * @return string Application Name if one exists
     */
    public function getAppNameForApiKey($apiKey)
    {
        // Call the storage service
        return $this->storageHandler->getStorage()->getAppNameForApiKey($apiKey);
    }

    /**
     * Load a new user object by application name
     * @param string $appName The application name to load a user object from
     */
    public function loadUserByUsername($appName)
    {
        // Call the storage service
        return $this->storageHandler->getStorage()->loadApiUserByAppName($appName);
    }

    /**
     * Refreshes the user from sessions
     * @param UserInterface $user User object implenting user interface
     */
    public function refreshUser(UserInterface $user)
    {
        // Stateless for now
        throw new UnsupportedUserException();
    }

    /**
     * Checks which user type is supported
     * @param string $class Class name to check
     */
    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}
