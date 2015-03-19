<?php

namespace TyHand\SimpleApiKeyBundle\Storage;

use Symfony\Component\DependencyInjection\ContainerInterface;
use TyHand\SimpleApiKeyBundle\Storage\ApiKeyStorageInterface;
use TyHand\SimpleApiKeyBundle\Exception\DoesNotImplementException;

/**
 * Simple class that handles getting the storage service for the api key auth
 *
 * @author Tyler Hand <https://github.com/tyhand>
 */
class StorageConfigurationHandler
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * The service that implements the API key storage interface
     * @var ApiKeyStorageInterface
     */
    private $storage;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param ContainerInterface $container DI container
     */
    public function __construct(ContainerInterface $container)
    {
        // Get the requested storage service
        $this->storage = $container->get(
            $container->getParameter('tyhand.simple_apikey.storage_service_name')
        );

        // Check that the storage service uses the required interface
        if (!($this->storage instanceof ApiKeyStorageInterface)) {
            throw new DoesNotImplementException(
                $this->storage,
                'TyHand\SimpleApiKeyBundle\Storage\ApiKeyStorageInterface'
            );
        }
    }

    /////////////
    // GETTERS //
    /////////////

    /**
     * Gets the storage service
     * @return ApiKeyStorageInterface Storage Service
     */
    public function getStorage()
    {
        return $this->storage;
    }
}
