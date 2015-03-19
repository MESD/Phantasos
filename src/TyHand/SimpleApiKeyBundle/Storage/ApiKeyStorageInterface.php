<?php

namespace TyHand\SimpleApiKeyBundle\Storage;

use TyHand\SimpleApiKeyBundle\User\ApiUser;

/**
 * Interface for an api key storage mechanism
 *
 * @author Tyler Hand <https://github.com/TyHand>
 */
interface ApiKeyStorageInterface
{
    /**
     * Creates a new entry for an api key and application
     * @param ApiUser $apiUser The new API User object to persist
     * @return boolean The success of the operation
     */
    public function createNewEntry($apiUser);

    /**
     * Load the api user by the application name
     * @param string $appName Name of the application to load
     * @return ApiUser API user object associated with the application name
     */
    public function loadApiUserByAppName($appName);

    /**
     * Return a string application name related to the given api key
     * @param string $apiKey Api key to get an application name for
     * @return string The associated application name if exists
     */
    public function getAppNameForApiKey($apiKey);

    /**
     * Updates the api key for the given application
     * @param string $appName Application name to update the key for
     * @param string $newKey  Newly created api key
     * @return boolean The success of the operation
     */
    public function updateApiKey($appName, $newKey);

    /**
     * Changes the active status of the api user
     * @param string  $appName Application name of the api user
     * @param boolean $active  Active status (true for yes, false for no)
     * @return boolean The success of the operation
     */
    public function changeActiveStatus($appName, $active);
}
