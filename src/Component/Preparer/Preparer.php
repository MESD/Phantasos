<?php

namespace Component\Preparer;

use Component\Preparer\PreparerInterface;
use Component\Storage\StorageInterface;
use Component\Storage\Document\Media;
use Component\Processor\ProcessorQueuerInterface;
use Component\Preparer\UploadTicketRequest;
use Component\Preparer\UploadTicket;
use Component\Preparer\Types\MediaTypes;
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
     * @var ProcessorQueuerInterface
     */
    private $processor;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param StorageInterface         $storage   Storage Module
     * @param ProcessorQueuerInterface $processorQueuer Processor Module
     */
    public function __construct(
        StorageInterface $storage,
        ProcessorQueuerInterface $processorQueuer)
    {
        // Set the modules
        $this->storage = $storage;
        $this->processorQueuer = $processorQueuer;
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
     * @param UploadedFile $file     Uploaded file
     * @param string       $mediaId  Id of the media
     * @return boolean Whether the module was successful in handling the file
     */
    public function handleUpload(UploadedFile $file, $mediaId)
    {
        // Check that the file type is supported
        $type = $this->processorQueuer->getMediaType($file->getMimeType());
        if (null === $type) {
            throw new UnsupportedFileTypeException($file->getMimeType());
        }

        // Pass onto the processor
        $this->processorQueuer->queueFile($file, $mediaId);

        // Mark as having been uploaded
        $this->storage->markAsUploaded($mediaId, $type);

        // return
        return true;
    }
}
