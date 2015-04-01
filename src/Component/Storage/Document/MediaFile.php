<?php

namespace Component\Storage\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="mediaFile")
 */
class MediaFile
{
    /**
     * Database ID
     * @MongoDB\Id
     */
    private $id;

    /**
     * File name
     * @MongoDB\String
     */
    private $name;

    /**
     * GridFS file
     * @MongoDB\File
     */
    private $file;

    /**
     * Upload Date
     * @MongoDB\Date
     */
    private $uploadDate;

    /**
     * File length
     * @MongoDB\Int
     */
    private $length;

    /**
     * Chunksize
     * @MongoDB\Int
     */
    private $chunkSize;

    /**
     * MD5
     * @MongoDB\String
     */
    private $md5;

    /**
     * Mimetype
     * @MongoDB\String
     */
    private $mimeType;

    /**
     * Width if applicable
     * @MongoDB\Int
     */
    private $width;

    /**
     * Height if applicable
     * @MongoDB\Int
     */
    private $height;

    /**
     * Bitrate
     * @MongoDB\String
     */
    private $bitrate;

    /**
     * Encoding format
     * @MongoDB\String
     */
    private $encodingFormat;

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Get the value of Database ID
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of File name
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of File name
     * @param mixed name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of GridFS file
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of GridFS file
     * @param mixed file
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get the value of Upload Date
     * @return mixed
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * Set the value of Upload Date
     * @param mixed uploadDate
     * @return self
     */
    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }

    /**
     * Get the value of File length
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set the value of File length
     * @param mixed length
     * @return self
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get the value of Chunksize
     * @return mixed
     */
    public function getChunkSize()
    {
        return $this->chunkSize;
    }

    /**
     * Set the value of Chunksize
     * @param mixed chunkSize
     * @return self
     */
    public function setChunkSize($chunkSize)
    {
        $this->chunkSize = $chunkSize;

        return $this;
    }

    /**
     * Get the value of MD5
     * @return mixed
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * Set the value of MD5
     * @param mixed md5
     * @return self
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;

        return $this;
    }

    /**
     * Get the value of Mimetype
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set the value of Mimetype
     * @param mixed mimeType
     * @return self
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get the value of Width if applicable
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set the value of Width if applicable
     * @param mixed width
     * @return self
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get the value of Height if applicable
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the value of Height if applicable
     * @param mixed height
     * @return self
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get the value of Bitrate
     * @return mixed
     */
    public function getBitrate()
    {
        return $this->bitrate;
    }

    /**
     * Set the value of Bitrate
     * @param mixed bitrate
     * @return self
     */
    public function setBitrate($bitrate)
    {
        $this->bitrate = $bitrate;

        return $this;
    }

    /**
     * Get the value of Encoding format
     * @return mixed
     */
    public function getEncodingFormat()
    {
        return $this->encodingFormat;
    }

    /**
     * Set the value of Encoding format
     * @param mixed encodingFormat
     * @return self
     */
    public function setEncodingFormat($encodingFormat)
    {
        $this->encodingFormat = $encodingFormat;

        return $this;
    }
}
