<?php

namespace Component\Processor;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

use Component\Storage\StorageInterface;
use Component\Exceptions\Api\MissingParameterException;
use Component\Processor\ProcessorContainer;
use Component\Processor\Enum\StatusEnum;

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

    /**
     * Container for processors
     * @var ProcessorContainer
     */
    private $processorContainer;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param StorageInterface $storage Storage Module
     */
    public function __construct(
        StorageInterface $storage,
        ProcessorContainer $processorContainer)
    {
        // Set the storage
        $this->storage = $storage;
        $this->processorContainer = $processorContainer;
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
        $original = $this->storage->getOriginalFileInfo($message['mediaId']);

        // Have the container find the processor and process
        $this->processorContainer->process($original);
    }
}
