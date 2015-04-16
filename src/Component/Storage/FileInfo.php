<?php

namespace Component\Storage;

use Gaufrette\File;
use Component\Storage\Document\MediaFile;
use Component\Storage\Document\Media;

/**
 * Container for a set of data defining a media file
 */
class FileInfo
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Parent media
     * @var Media
     */
    protected $media;

    /**
     *  for the media file
     * @var MediaFile
     */
    protected $mediaFile;

    /**
     * Path to the file barring out the file name
     * @var string
     */
    protected $basePath;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param Media     $media     Media
     * @param MediaFile $mediaFile Media file
     * @param strgin    $basePath  Base path
     */
    public function __construct(Media $media, MediaFile $mediaFile, $basePath)
    {
        // Set
        $this->media = $media;
        $this->mediaFile = $mediaFile;
        $this->basePath = $basePath;
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Get media id
     * @return string Media Id
     */
    public function getMediaId()
    {
        return $this->media->getId();
    }

    /**
     * Check if a given application name is allowed to view this file
     * @param string $applicationName Name of the application to check
     * @return boolean Whether the application can view or not
     */
    public function canBeViewedBy($applicationName)
    {
        // if ($this->media->getHideToOthers()) {
        //     if ($this->media->getApplicationName() === $applicationName) {
        //         return true;
        //     } else {
        //         return false;
        //     }
        // } else {
        //     return true;
        // }
        return true;
    }

    /**
     * Get the full file path for the media file contained in this class
     * @return string Full file path
     */
    public function getFullFilePath()
    {
        return $this->basePath . $this->mediaFile->getFileName();
    }

    /**
     * Get the content type of the file
     * @return string Content type
     */
    public function getContentType()
    {
        return $this->mediaFile->getContentType();
    }

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Get the value of Parent media
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set the value of Parent media
     * @param Media media
     * @return self
     */
    public function setMedia(Media $media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * Get the value of  for the media file
     * @return MediaFile
     */
    public function getMediaFile()
    {
        return $this->mediaFile;
    }

    /**
     * Set the value of  for the media file
     * @param MediaFile mediaFile
     * @return self
     */
    public function setMediaFile(MediaFile $mediaFile)
    {
        $this->mediaFile = $mediaFile;
        return $this;
    }

    /**
     * Get the value of Path to the file barring out the file name
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Set the value of Path to the file barring out the file name
     * @param string basePath
     * @return self
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
        return $this;
    }
}
