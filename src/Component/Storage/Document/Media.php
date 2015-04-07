<?php

namespace Component\Storage\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Command\Collections\ArrayCollection;
use Component\Storage\Document\MediaFile;

/**
 * @MongoDB\Document(collection="media")
 */
class Media
{
    /**
     * Database Id
     * @MongoDB\Id
     */
    private $id;

    /**
     * Name of the owning application
     * @MongoDB\String
     */
    private $applicationName;

    /**
     * If true, only the owning application can view this media
     * @MongoDB\Boolean
     */
    private $hideToOthers;

    /**
     * Tags identifying the content of the media
     * @MongoDB\Collection
     */
    private $tags;

    /**
     * Tags identifying the roles that can view this media
     * @MongoDB\Collection
     */
    private $securityTags;

    /**
     * Whether the original media has been uploaded
     * @MongoDB\Boolean
     */
    private $uploaded;

    /**
     * Expiration time for the upload to take place
     * @MongoDB\Date
     */
    private $uploadExpiration;

    /**
     * Type of media (e.g. Video, Audio, Document, etc...)
     * @MongoDB\String
     */
    private $mediaType;

    /**
     * Whether the media is ready to be accessed by the client
     * @MongoDB\Boolean
     */
    private $ready;

    /**
     * Attached media files
     * @MongoDB\ReferenceMany(
     *      strategy="addToSet",
     *      targetDocument="Component\Storage\Document\MediaFile",
     *      cascade="all"
     *)
     */
    private $files;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set defualts
        $this->tags = array();
        $this->securityTags = array();
        $this->uploaded = false;
        $this->ready = false;
        $this->files = ArrayCollection();
    }

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Get the value of Database Id
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of Name of the owning application
     * @return mixed
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }

    /**
     * Set the value of Name of the owning application
     * @param mixed applicationName
     * @return self
     */
    public function setApplicationName($applicationName)
    {
        $this->applicationName = $applicationName;
        return $this;
    }

    /**
     * Get the value of If true, only the owning application can view this media
     * @return mixed
     */
    public function getHideToOthers()
    {
        return $this->hideToOthers;
    }

    /**
     * Set the value of If true, only the owning application can view this media
     * @param mixed hideToOthers
     * @return self
     */
    public function setHideToOthers($hideToOthers)
    {
        $this->hideToOthers = $hideToOthers;
        return $this;
    }

    /**
     * Get the value of Tags identifying the content of the media
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set the value of Tags identifying the content of the media
     * @param mixed tags
     * @return self
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Get the value of Tags identifying the roles that can view this media
     * @return mixed
     */
    public function getSecurityTags()
    {
        return $this->securityTags;
    }

    /**
     * Set the value of Tags identifying the roles that can view this media
     * @param mixed securityTags
     * @return self
     */
    public function setSecurityTags($securityTags)
    {
        $this->securityTags = $securityTags;
        return $this;
    }

    /**
     * Get the value of Whether the original media has been uploaded
     * @return mixed
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * Set the value of Whether the original media has been uploaded
     * @param mixed uploaded
     * @return self
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;
        return $this;
    }

    /**
     * Get the value of Type of media (e.g. Video, Audio, Document, etc...)
     * @return mixed
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Set the value of Type of media (e.g. Video, Audio, Document, etc...)
     * @param mixed mediaType
     * @return self
     */
    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;
        return $this;
    }

    /**
     * Get the value of Attached media files
     * @return ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add media file
     * @param MediaFile $file File to add
     * @return self
     */
    public function addFile(MediaFile $file)
    {
        $this->files->add($file);
        return $this;
    }

    /**
     * Remove a media file
     * @param MediaFile $file File to remove
     * @return self
     */
    public function removeFile(MediaFile $file)
    {
        $this->files->remove($this->files->indexOf($file));
        return $this;
    }

    /**
     * Set the value of Attached media files
     * @param ArrayCollection files
     * @return self
     */
    public function setFiles(ArrayCollection $files)
    {
        $this->files = $files;
        return $this;
    }

    /**
     * Get the value of Expiration time for the upload to take place
     *
     * @return mixed
     */
    public function getUploadExpiration()
    {
        return $this->uploadExpiration;
    }

    /**
     * Set the value of Expiration time for the upload to take place
     *
     * @param mixed uploadExpiration
     *
     * @return self
     */
    public function setUploadExpiration($uploadExpiration)
    {
        $this->uploadExpiration = $uploadExpiration;
        return $this;
    }

    /**
     * Get the value of Whether the media is ready to be accessed by the client
     * @return mixed
     */
    public function getReady()
    {
        return $this->ready;
    }

    /**
     * Alias for getReady()
     * @return boolean
     */
    public function isReady()
    {
        return $this->ready;
    }

    /**
     * Set the value of Whether the media is ready to be accessed by the client
     * @param mixed ready
     * @return self
     */
    public function setReady($ready)
    {
        $this->ready = $ready;
        return $this;
    }
}
