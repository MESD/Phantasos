<?php

namespace Component\Retrieval;

/**
 * Super simple container for some return varibles
 */
class FilePath
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Path to file
     * @var string
     */
    private $path;

    /**
     * Content type of the file
     * @var string
     */
    private $contentType;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param string $path        Path to the file
     * @param string $contentType Content type
     */
    public function __construct($path, $contentType)
    {
        // Set
        $this->path = $path;
        $this->contentType = $contentType;
    }

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Get the value of Path to file
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of Path to file
     * @param string path
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get the value of Content type of the file
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set the value of Content type of the file
     * @param string contentType
     * @return self
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }
}
