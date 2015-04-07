<?php

namespace Component\Processor;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

use Component\Storage\StorageInterface;
use Component\Exceptions\Api\MissingParameterException;
use Component\Exceptions\Media\DoesNotExistException;

/**
 * Consumer for the media processing should be called via RabbitMQ
 */
class ProcessorConsumer implements ConsumerInterface
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Storage Module
     * @var StorageInterface
     */
    private $storage;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param StorageInterface $storage Storage Module
     */
    public function __construct(StorageInterface $storage)
    {
        // Set the storage
        $this->storage = $storage;
    }

    ///////////////////////
    // INTERFACE METHODS //
    ///////////////////////

    /**
     * Method to be called by RabbitMQ
     * @param  AMQPMessage $msg Message
     * @return boolean          Operation Success
     */
    public function execute(AMQPMessage $msg)
    {
        // Unserialize the message of the body and get the media id
        $message = unserialize($msg->body);

        // Get the media id
        if (!array_key_exists('mediaId', $message)) {
            throw new MissingParameterException('mediaId');
        }

        // Get the original file
        $original = $this->storage->getOriginal($media->getId());

        // Build the necessary processor and process
        $factory = new ProcessorFactory();
        $processor = $factory->build(
            $original->getMediaFileDocument()->getContentType(),
            $this->storage
        );

        // Process and return
        return $processor->process($original);
    }
}
