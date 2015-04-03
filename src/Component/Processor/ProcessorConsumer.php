<?php

namespace Component\Processor;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

use Component\Storage\StorageInterface;

/**
 * Consumer for the media processing
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
        return false;
    }
}
