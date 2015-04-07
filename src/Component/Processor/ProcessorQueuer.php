<?php

namespace Component\Processor;

use Component\Processor\ProcessorQueuerInterface;
use Component\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

class ProcessorQueuer implements ProcessorQueuerInterface
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Storage Module
     * @var StorageInterface
     */
    protected $storage;

    /**
     * RabbitMQ producer
     * @var Producer
     */
    protected $producer;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param StorageInterface $storage Storage module
     */
    public function __construct(StorageInterface $storage, Producer $producer)
    {
        // Set the properties
        $this->storage = $storage;
        $this->producer = $producer;
    }

    ///////////////////////
    // INTERFACE METHODS //
    ///////////////////////

    /**
     * Get a list of mime types the processor module can handle
     * @return array List of mime types that the processor can handle
     */
    public function getSupportedTypes()
    {
        return array(
            'image/jpeg'
        );
    }

    /**
     * Queue an uploaded file for processing
     * @param UploadedFile $original File to process
     * @param string       $mediaId  Media id of the upload
     * @return boolean True if the file is placed into a work queue
     */
    public function queueFile(UploadedFile $original, $mediaId)
    {
        // Place the original into storage
        $this->storage->addOriginalFile($original, $mediaId);

        // Create a message
        $msg = array('mediaId' => $mediaId);

        // Publish the message to the Rabbit MQ server
        $ret  = $this->producer->publish(serialize($msg));
        var_dump($ret); die;

        // Return that the file is going to be worked on
        return true;
    }
}
