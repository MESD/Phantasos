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
     * File system file
     * @var File
     */
    protected $file;

    /**
     * Parent media document
     * @var Media
     */
    protected $mediaDocument;

    /**
     * Document for the media file
     * @var MediaFile
     */
    protected $mediaFileDocument;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param File      $file              Filesystem file
     * @param Media     $mediaDocument     Media document
     * @param MediaFile $mediaFileDocument Media file document
     */
    public function __construct(
        File $file,
        Media $mediaDocument,
        MediaFile $mediaFileDocument)
    {
        // Set
        $this->file = $file;
        $this->mediaDocument = $mediaDocument;
        $this->mediaFileDocument = $mediaFileDocument;
    }

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Get the value of File system file
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of File system file
     * @param File file
     * @return self
     */
    public function setFile(File $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get the value of Parent media document
     * @return Media
     */
    public function getMediaDocument()
    {
        return $this->mediaDocument;
    }

    /**
     * Set the value of Parent media document
     * @param Media mediaDocument
     * @return self
     */
    public function setMediaDocument(Media $mediaDocument)
    {
        $this->mediaDocument = $mediaDocument;
        return $this;
    }

    /**
     * Get the value of Document for the media file
     * @return MediaFile
     */
    public function getMediaFileDocument()
    {
        return $this->mediaFileDocument;
    }

    /**
     * Set the value of Document for the media file
     * @param MediaFile mediaFileDocument
     * @return self
     */
    public function setMediaFileDocument(MediaFile $mediaFileDocument)
    {
        $this->mediaFileDocument = $mediaFileDocument;
        return $this;
    }

}
