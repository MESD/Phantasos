<?php

namespace TyHand\SimpleApiKeyMongoStorageBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use TyHand\SimpleApiKeyBundle\User\ApiUser;

/**
 * @MongoDB\Document(collection="apiUsers")
 */
class ApiUserDocument extends ApiUser
{
    /**
     * Database ID
     * @MongoDB\Id
     */
    protected $id;

    /**
     * Name of the application using this api
     * @MongoDB\String @MongoDB\UniqueIndex
     */
    protected $applicationName;

    /**
     * URI of the application using this api
     * @MongoDB\String
     */
    protected $applicationUri;

    /**
     * Description of the application using this api
     * @MongoDB\String
     */
    protected $applicationDescription;

    /**
     * Email for point of contact for this application
     * @MongoDB\String
     */
    protected $contactEmail;

    /**
     * Name of the point of contact for this application
     * @MongoDB\String
     */
    protected $contactName;

    /**
     * Symfony roles
     * @MongoDB\Collection
     */
    protected $roles;

    /**
     * API Key
     * @MongoDB\String @MongoDB\UniqueIndex
     */
    protected $apiKey;

    /**
     * Active Status
     * @MongoDB\Boolean
     */
    protected $active;

    /**
     * Date of last use
     * @MongoDB\Date
     */
    protected $lastUse;

    ///////////////////////////////////
    // GENERATED GETTERS AND SETTERS //
    ///////////////////////////////////

    /**
     * Get the value of Database ID
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of Name of the application using this api
     *
     * @return mixed
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }

    /**
     * Set the value of Name of the application using this api
     *
     * @param mixed applicationName
     *
     * @return self
     */
    public function setApplicationName($applicationName)
    {
        $this->applicationName = $applicationName;

        return $this;
    }

    /**
     * Get the value of URI of the application using this api
     *
     * @return mixed
     */
    public function getApplicationUri()
    {
        return $this->applicationUri;
    }

    /**
     * Set the value of URI of the application using this api
     *
     * @param mixed applicationUri
     *
     * @return self
     */
    public function setApplicationUri($applicationUri)
    {
        $this->applicationUri = $applicationUri;

        return $this;
    }

    /**
     * Get the value of Description of the application using this api
     *
     * @return mixed
     */
    public function getApplicationDescription()
    {
        return $this->applicationDescription;
    }

    /**
     * Set the value of Description of the application using this api
     *
     * @param mixed applicationDescription
     *
     * @return self
     */
    public function setApplicationDescription($applicationDescription)
    {
        $this->applicationDescription = $applicationDescription;

        return $this;
    }

    /**
     * Get the value of Email for point of contact for this application
     *
     * @return mixed
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * Set the value of Email for point of contact for this application
     *
     * @param mixed contactEmail
     *
     * @return self
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    /**
     * Get the value of Name of the point of contact for this application
     *
     * @return mixed
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set the value of Name of the point of contact for this application
     *
     * @param mixed contactName
     *
     * @return self
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * Get the value of Symfony roles
     *
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set the value of Symfony roles
     *
     * @param array roles
     *
     * @return self
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of API Key
     *
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set the value of API Key
     *
     * @param mixed apiKey
     *
     * @return self
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get the value of Active Status
     *
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set the value of Active Status
     *
     * @param mixed active
     *
     * @return self
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get the value of Date of last use
     *
     * @return mixed
     */
    public function getLastUse()
    {
        return $this->lastUse;
    }

    /**
     * Set the value of Date of last use
     *
     * @param mixed lastUse
     *
     * @return self
     */
    public function setLastUse($lastUse)
    {
        $this->lastUse = $lastUse;

        return $this;
    }

}
