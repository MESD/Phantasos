<?php

namespace Component\Storage;

class MediaInfo
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Name of the owning application
     * @var string
     */
    private $applicationName;

    /**
     * Whether other applications can view this media or owning app only
     * @var boolean
     */
    private $hideToOthers;

    /**
     * Type of media
     * @var string
     */
    private $mediaType;

    /**
     * Whether the original file still exists on the server
     * @var boolean
     */
    private $originalExists;

    /**
     * Type tags
     * @var array
     */
    private $tags;

    /**
     * Security tags
     * @var array
     */
    private $security;

    /**
     * Array of children files
     * @var array
     */
    private $files;

    /**
     * Whether the file is ready for viewing
     * @var boolean
     */
    private $ready;

    /**
     * Current status
     * @var string
     */
    private $status;

    /**
     * Processing percentage if in status is processing
     * @var float
     */
    private $processingPercentage;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     */
    public function __construct()
    {
        // Init
        $this->files = array();
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Convert this object to an array for json transmission
     * @return array Array representation of the object
     */
    public function toArray()
    {
        // Generate the media files array
        $mediaFiles = array();
        foreach($this->files as $file) {
            $mediaFiles[] = $file->toArray();
        }

        // Create the array
        return array(
            'owner' => $this->applicationName,
            'type' => $this->mediaType,
            'original_exists' => $this->originalExists,
            'tags' => $this->tags,
            'security' => $this->security,
            'files' => $mediaFiles,
            'ready' => $this->ready,
            'status' => $this->status,
            'processing_percentage' => $this->processingPercentage
        );
    }

    /**
     * Add a media file info to this media info
     * @param MediaFileInfo $mediaFileInfo Media file info to add
     * @return self
     */
    public function addMediaFileInfo(MediaFileInfo $mediaFileInfo)
    {
        // append to the files array
        $this->files[] = $mediaFileInfo;
        return $this;
    }

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Get the value of Name of the owning application
     * @return string
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }

    /**
     * Set the value of Name of the owning application
     * @param string applicationName
     * @return self
     */
    public function setApplicationName($applicationName)
    {
        $this->applicationName = $applicationName;
        return $this;
    }

    /**
     * Get the value of Whether other applications can view this media or owning app only
     * @return boolean
     */
    public function getHideToOthers()
    {
        return $this->hideToOthers;
    }

    /**
     * Set the value of Whether other applications can view this media or owning app only
     * @param boolean hideToOthers
     * @return self
     */
    public function setHideToOthers($hideToOthers)
    {
        $this->hideToOthers = $hideToOthers;
        return $this;
    }

    /**
     * Get the value of Type of media
     * @return string
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Set the value of Type of media
     * @param string mediaType
     * @return self
     */
    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    /**
     * Get the value of Whether the original file still exists on the server
     * @return boolean
     */
    public function getOriginalExists()
    {
        return $this->originalExists;
    }

    /**
     * Set the value of Whether the original file still exists on the server
     * @param boolean originalExists
     * @return self
     */
    public function setOriginalExists($originalExists)
    {
        $this->originalExists = $originalExists;
        return $this;
    }

    /**
     * Get the value of Type tags
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set the value of Type tags
     * @param array tags
     * @return self
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Get the value of Security tags
     * @return array
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * Set the value of Security tags
     * @param array security
     * @return self
     */
    public function setSecurity(array $security)
    {
        $this->security = $security;
        return $this;
    }

    /**
     * Get the value of Array of children files
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Get the value of Whether the file is ready for viewing
     * @return boolean
     */
    public function getReady()
    {
        return $this->ready;
    }

    /**
     * Set the value of Whether the file is ready for viewing
     * @param boolean ready
     * @return self
     */
    public function setReady($ready)
    {
        $this->ready = $ready;
        return $this;
    }

    /**
     * Get the value of Current status
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of Current status
     * @param string status
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the value of Processing percentage if in status is processing
     * @return float
     */
    public function getProcessingPercentage()
    {
        return $this->processingPercentage;
    }

    /**
     * Set the value of Processing percentage if in status is processing
     * @param float processingPercentage
     * @return self
     */
    public function setProcessingPercentage($processingPercentage)
    {
        $this->processingPercentage = $processingPercentage;
        return $this;
    }
}
