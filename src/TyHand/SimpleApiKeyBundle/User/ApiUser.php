<?php

namespace TyHand\SimpleApiKeyBundle\User;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * A basic API use class that extends the symfony user interface
 * @author Tyler Hand <https://github.com/tyhand>
 */
class ApiUser implements UserInterface
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Whether the application is active
     * @var boolean
     */
    protected $active;

    /**
     * API Key
     * @var string
     */
    protected $apiKey;

    /**
     * Name of the application
     * @var string
     */
    protected $applicationName;

    /**
     * URI of the application
     * @var string
     */
    protected $applicationUri;

    /**
     * Description of the application
     * @var string
     */
    protected $applicationDescription;

    /**
     * Email of the point of contact for the application
     * @var string
     */
    protected $contactEmail;

    /**
     * Name of the point of contact for the application
     * @var string
     */
    protected $contactName;

    /**
     * List of roles the application has
     * @var array
     */
    protected $roles;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set the defaults
        $this->active = true;
        $this->roles = ['ROLE_USER'];
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Return null for the password as the API key has no password
     * @return null
     */
    public function getPassword()
    {
        // API keys have no password
        return null;
    }

    /**
     * Return the salt (which is non existant)
     * @return null
     */
    public function getSalt()
    {
        // No salt
        return null;
    }

    /**
     * Return the username of the application
     * @return string
     */
    public function getUsername()
    {
        return $this->applicationName;
    }

    /**
     * Removes sensitive data from the user
     */
    public function eraseCredentials()
    {
        $this->active = false;
    }

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Get the value of Whether the application is active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set the value of Whether the application is active
     *
     * @param boolean active
     *
     * @return self
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get the value of API Key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set the value of API Key
     *
     * @param string apiKey
     *
     * @return self
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get the value of Name of the application
     *
     * @return string
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }

    /**
     * Set the value of Name of the application
     *
     * @param string applicationName
     *
     * @return self
     */
    public function setApplicationName($applicationName)
    {
        $this->applicationName = $applicationName;

        return $this;
    }

    /**
     * Get the value of URI of the application
     *
     * @return string
     */
    public function getApplicationUri()
    {
        return $this->applicationUri;
    }

    /**
     * Set the value of URI of the application
     *
     * @param string applicationUri
     *
     * @return self
     */
    public function setApplicationUri($applicationUri)
    {
        $this->applicationUri = $applicationUri;

        return $this;
    }

    /**
     * Get the value of Description of the application
     *
     * @return string
     */
    public function getApplicationDescription()
    {
        return $this->applicationDescription;
    }

    /**
     * Set the value of Description of the application
     *
     * @param string applicationDescription
     *
     * @return self
     */
    public function setApplicationDescription($applicationDescription)
    {
        $this->applicationDescription = $applicationDescription;

        return $this;
    }

    /**
     * Get the value of Email of the point of contact for the application
     *
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * Set the value of Email of the point of contact for the application
     *
     * @param string contactEmail
     *
     * @return self
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    /**
     * Get the value of Name of the point of contact for the application
     *
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set the value of Name of the point of contact for the application
     *
     * @param string contactName
     *
     * @return self
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * Get the value of List of roles the application has
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set the value of List of roles the application has
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

}
