<?php

namespace Component\Storage;

class MediaFileInfo
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Mime type of the media file
     * @var string
     */
    private $mimeType;

    /**
     * Media File id
     * @var string
     */
    private $mediaFileId;

    /**
     * Size Array
     * @var array
     */
    private $size;

    /**
     * Filename
     * @var string
     */
    private $fileName;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     */
    public function __construct()
    {
        // Init
        $this->size = array();
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Convert object to array representation
     * @return array Array representation
     */
    public function toArray()
    {
        return array(
            'file_name' => $this->fileName,
            'content_type' => $this->mimeType,
            'media_file_id' => $this->mediaFileId,
            'size' => $this->size
        );
    }

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Get the value of Mime type of the media file
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set the value of Mime type of the media file
     * @param string mimeType
     * @return self
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Get the value of Media File id
     * @return string
     */
    public function getMediaFileId()
    {
        return $this->mediaFileId;
    }

    /**
     * Set the value of Media File id
     * @param string mediaFileId
     * @return self
     */
    public function setMediaFileId($mediaFileId)
    {
        $this->mediaFileId = $mediaFileId;
        return $this;
    }

    /**
     * Get the value of Size Array
     * @return array
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of Size Array
     * @param array size
     * @return self
     */
    public function setSize($width = null, $height = null, $bitrate = null)
    {
        if ($width !== null) {
            $this->size['width'] = $width;
        }
        if ($height !== null) {
            $this->size['height'] = $height;
        }
        if ($bitrate !== null) {
            $this->size['bitrate'] = $bitrate;
        }
        return $this;
    }

    /**
     * Get the value of Filename
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set the value of Filename
     * @param string fileName
     * @return self
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

}
