<?php

namespace Component\Preparer;

use Component\Preparer\PreparerInterface;
use Component\Storage\StorageInterface;
use Component\Storage\Document\Media;
use Component\Processor\ProcessorInterface;
use Component\Preparer\UploadTicketRequest;
use Component\Preparer\UploadTicket;
use Component\Exceptions\Files\UnsupportedFileTypeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Preparer implements PreparerInterface
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
     * Processor Module
     * @var ProcessorInterface
     */
    private $processor;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param StorageInterface   $storage   Storage Module
     * @param ProcessorInterface $processor Processor Module
     */
    public function __construct(
        StorageInterface $storage,
        ProcessorInterface $processor)
    {
        // Set the modules
        $this->storage = $storage;
        $this->processor = $processor;
    }

    ///////////////////////
    // INTERFACE METHODS //
    ///////////////////////

    /**
     * Request a new upload ticket
     * @param UploadTicketRequest $uploadRequest Request Information
     */
    public function requestUploadTicket(UploadTicketRequest $uploadRequest)
    {
        // Determine the expiration time
        $expiration = new \DateTime();
        $expiration->modify('+2 hours');

        // Call the storage module and create a new document
        $mediaId = $this->storage->createUploadSpot($uploadRequest, $expiration);

        // Create the Upload Ticket object to return
        return new UploadTicket($mediaId, $expiration);
    }

    /**
     * Handle upload
     * @param UploadedFile $file  Uploaded file
     * @param Media        $media Media document
     * @return boolean Whether the module was successful in handling the file
     */
    public function handleUpload(UploadedFile $file, Media $media)
    {
        throw new UnsupportedFileTypeException('monkey');
    }
}
