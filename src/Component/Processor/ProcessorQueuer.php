<?php

namespace Component\Processor;

use Component\Processor\ProcessorQueuerInterface;
use Component\Processor\ProcessorContainer;
use Component\Processor\Enum\StatusEnum;
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

    /**
     * Processor Container
     * @var ProcessorContainer
     */
    protected $processorContainer;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param StorageInterface $storage  Storage module
     * @param Producer         $producer RabbitMQ producer
     * @param ProcessorContainer $processorContainer Processor Container
     */
    public function __construct(
        StorageInterface $storage,
        Producer $producer,
        ProcessorContainer $processorContainer)
    {
        // Set the properties
        $this->storage = $storage;
        $this->producer = $producer;
        $this->processorContainer = $processorContainer;
    }

    ///////////////////////
    // INTERFACE METHODS //
    ///////////////////////

    /**
     * Get the media type from the mime type
     * @param string $mimeType Mime type of the uploaded file
     * @return string|null Media type or null if unsupported
     */
    public function getMediaType($mimeType)
    {
        return $this->processorContainer->getMediaType($mimeType);
    }

    /**
     * Queue an uploaded file for processing
     * @param UploadedFile $original File to process
     * @param string       $mediaId  Media id of the upload
     * @param string       $callback Route to call when processing is done
     * @return boolean True if the file is placed into a work queue
     */
    public function queueFile(UploadedFile $original, $mediaId)
    {
        // Place the original into storage
        $this->storage->addOriginalFile($original, $mediaId);

        // Create a message
        $msg = array('mediaId' => $mediaId);

        // Publish the message to the Rabbit MQ server
        $ret = $this->producer->publish(serialize($msg));

        // Return that the file is going to be worked on
        return true;
    }

    /**
     * Requeue a media file
     * @param string $mediaId Id of media to requeue
     * @return boolean True if the file is placed into a work queue
     */
    public function requeueMedia($mediaId)
    {
        // Set the status to queued
        $this->storage->updateMediaStatus($mediaId, StatusEnum::STATUS_QUEUED);

        // Create a message
        $msg = array('mediaId' => $mediaId);

        // Publish the message to the Rabbit MQ server
        $ret = $this->producer->publish(serialize($msg));

        // Return that the file is going to be worked on
        return true;
    }
}
