<?php

namespace Component\Preparer;

use Symfony\Component\HttpFoundation\Request;
use Component\Exceptions\Api\MissingParameterException;

/**
 * Basic helper class that sends the request data to the storage module
 */
class UploadTicketRequest
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * IP of the requesting client
     * @var string
     */
    private $uploaderIp;

    /**
     * Name of the application making this request
     * @var string
     */
    private $applicationName;

    /**
     * Array of string tags for this upload
     * @var array
     */
    private $tags;

    /**
     * Array of string security tags for this upload
     * @var array
     */
    private $securityTags;

    /**
     * Whether other applications can see this media upload
     * @var boolean
     */
    private $hideToOthers;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set defaults
        $this->tags = array();
        $this->securityTags = array();
        $this->hideToOthers = true;
    }

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Get the value of IP of the requesting client
     * @return string
     */
    public function getUploaderIp()
    {
        return $this->uploaderIp;
    }

    /**
     * Set the value of IP of the requesting client
     * @param string uploaderIp
     * @return self
     */
    public function setUploaderIp($uploaderIp)
    {
        $this->uploaderIp = $uploaderIp;

        return $this;
    }

    /**
     * Get the value of Name of the application making this request
     * @return string
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }

    /**
     * Set the value of Name of the application making this request
     * @param string applicationName
     * @return self
     */
    public function setApplicationName($applicationName)
    {
        $this->applicationName = $applicationName;

        return $this;
    }

    /**
     * Get the value of Array of string tags for this upload
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set the value of Array of string tags for this upload
     * @param array tags
     * @return self
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get the value of Array of string security tags for this upload
     * @return array
     */
    public function getSecurityTags()
    {
        return $this->securityTags;
    }

    /**
     * Set the value of Array of string security tags for this upload
     * @param array securityTags
     * @return self
     */
    public function setSecurityTags(array $securityTags)
    {
        $this->securityTags = $securityTags;
        return $this;
    }

    /**
     * Get the value of Whether other applications can see this media upload
     * @return boolean
     */
    public function getHideToOthers()
    {
        return $this->hideToOthers;
    }

    /**
     * Set the value of Whether other applications can see this media upload
     * @param boolean hideToOthers
     * @return self
     */
    public function setHideToOthers($hideToOthers)
    {
        $this->hideToOthers = $hideToOthers;
        return $this;
    }
}
