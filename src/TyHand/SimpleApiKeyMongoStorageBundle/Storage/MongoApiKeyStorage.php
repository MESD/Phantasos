<?php

namespace TyHand\SimpleApiKeyMongoStorageBundle\Storage;

use TyHand\SimpleApiKeyBundle\Storage\ApiKeyStorageInterface;
use TyHand\SimpleApiKeyBundle\User\ApiUser;
use TyHand\SimpleApiKeyMongoStorageBundle\Storage\ManagerGrabber;
use TyHand\SimpleApiKeyMongoStorageBundle\Util\ApiUserToDocumentConverter;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Api Key storage with MongoDB
 */
class MongoApiKeyStorage implements ApiKeyStorageInterface
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Document Manager
     * @var DocumentManager
     */
    private $documentManager;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param ManagerGrabber $managerGrabber Wrapper around the manager
     */
    public function __construct(ManagerGrabber $managerGrabber)
    {
        // Set the properties
        $this->documentManager = $managerGrabber->getManager();
    }

    ///////////////////////////////////////
    // API KEY STORAGE INTERFACE METHODS //
    ///////////////////////////////////////

    /**
     * Creates a new entry for an api key and application
     * @param ApiUser $apiUser The new API User object to persist
     * @return boolean The success of the operation
     */
    public function createNewEntry(ApiUser $apiUser)
    {
        // Convert to the child class
        $document = ApiUserToDocumentConverter::ConvertToNewDocument($apiUser);;

        // Persist and save
        $this->documentManager->persist($document);
        $this->documentManager->flush();
    }

    /**
     * Load the api user by the application name
     * @param string $appName Name of the application to load
     * @return ApiUser API user object associated with the application name
     */
    public function loadApiUserByAppName($appName)
    {
        // Find an ApiUser Document by app name
        $apiUser = $this->documentManager
            ->getRepository('TyHandSimpleApiKeyMongoStorageBundle:ApiUserDocument')
            ->findOneByApplicationName($appName)
        ;
        if (null !== $apiUser) {
            $apiUser->setLastUse(new \DateTime());
            $this->documentManager->flush($apiUser);
        }
        return $apiUser;
    }

    /**
     * Return a string application name related to the given api key
     * @param string $apiKey Api key to get an application name for
     * @return string The associated application name if exists
     */
    public function getAppNameForApiKey($apiKey)
    {
        // Find an ApiUser by the given API key and return the application name
        $apiUser = $this->documentManager
            ->getRepository('TyHandSimpleApiKeyMongoStorageBundle:ApiUserDocument')
            ->findOneByApiKey($apiKey);

        if ($apiUser !== null) {
            return $apiUser->getApplicationName();
        } else {
            return null;
        }
    }

    /**
     * Updates the api key for the given application
     * @param string $appName Application name to update the key for
     * @param string $newKey  Newly created api key
     * @return boolean The success of the operation
     */
    public function updateApiKey($appName, $newKey)
    {
        // Find the existing user
        $apiUser = $this->loadApiUserByAppName($appName);

        // Return false if the apiUser is nonexistant
        if (null === $apiUser) {
            return false;
        }

        // Update the api key
        $apiUser->setApiKey($newKey);

        // Persist and flush
        $this->documentManager->persist($apiUser);
        $this->documentManager->flush();

        // Return success
        return true;
    }

    /**
     * Changes the active status of the api user
     * @param string  $appName Application name of the api user
     * @param boolean $active  Active status (true for yes, false for no)
     * @return boolean The success of the operation
     */
    public function changeActiveStatus($appName, $active)
    {
        // Find the existing user
        $apiUser = $this->loadApiUserByAppName($appName);

        // Return false if the api user doesnt exist
        if (null === $apiUser) {
            return false;
        }

        // Change the active status
        $apiUser->setActive($active);

        // Persist and Flush
        $this->documentManager->persist($apiUser);
        $this->documentManager->flush();

        // Return success
        return true;
    }
}
