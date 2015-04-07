<?php

namespace Component\Storage\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="mediaFile")
 */
class MediaFile
{
    /**
     * Database id
     * @MongoDB\Id
     */
    private $id;

    /**
     * Name of the file
     * @MongoDB\String
     */
    private $fileName;

    /**
     * Whether the file is original or created via the processor
     * @MongoDB\Boolean
     */
    private $original;

    /**
     * File type
     * @MongoDB\String
     */
    private $contentType;

    /**
     * Bitrate
     * @MongoDB\String
     */
    private $bitRate;

    /**
     * Height if applicable
     * @MongoDB\Int
     */
    private $height;

    /**
     * Width if applicable
     * @MongoDB\Int
     */
    private $width;

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Get the value of the database Id
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of Path of the file
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set the value of Path of the file
     * @param string filePath
     * @return self
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * Get the value of Name of the file
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set the value of Name of the file
     * @param string fileName
     * @return self
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Get the value of Whether the file is original or created via the processor
     * @return boolean
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * Set the value of Whether the file is original or created via the processor
     * @param boolean original
     * @return self
     */
    public function setOriginal($original)
    {
        $this->original = $original;
        return $this;
    }

    /**
     * Get the value of content type
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set the value of content type
     * @param string contentType
     * @return self
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * Get the value of Bitrate
     * @return string
     */
    public function getBitRate()
    {
        return $this->bitRate;
    }

    /**
     * Set the value of Bitrate
     * @param string bitRate
     * @return self
     */
    public function setBitRate($bitRate)
    {
        $this->bitRate = $bitRate;
        return $this;
    }

    /**
     * Get the value of Height if applicable
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the value of Height if applicable
     * @param int height
     * @return self
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Get the value of Width if applicable
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set the value of Width if applicable
     * @param int width
     * @return self
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }
}
