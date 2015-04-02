<?php

namespace Component\Storage;

use Component\Storage\StorageInterface;
use Component\Storage\Document\Media;
use Component\Storage\Util\Converter\UploadTicketRequestToMedia;
use Component\Preparer\UploadTicketRequest;
use Component\Exceptions\Media\DoesNotExistException;

use Doctrine\ODM\MongoDB\DocumentManager;

class MongoDBStorage implements StorageInterface
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Document Manager
     * @var DocumentManager
     */
    protected $documentManager;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param DocumentManager $documentManager Document Manager
     */
    public function __construct(DocumentManager $documentManager)
    {
        // Set the properties
        $this->documentManager = $documentManager;
    }

    ///////////////////////
    // INTERFACE METHODS //
    ///////////////////////

    /**
     * Request an upload ticket
     * @param UploadTicketRequest $uploadRequest     Upload Ticket Request
     * @param \DateTime           $uploadExpiration  Time when the upload slot will expire
     * @return string Ticket id that this media will be referred to as
     */
    public function createUploadSpot(
        UploadTicketRequest $uploadRequest,
        \DateTime $uploadExpiration)
    {
        // Create a new media document that will be partially filled out
        $mediaSlot = UploadTicketRequestToMedia::convert($uploadRequest);

        // Set the expiration time
        $mediaSlot->setUploadExpiration($uploadExpiration);

        // Persist and flush
        $this->documentManager->persist($mediaSlot);
        $this->documentManager->flush($mediaSlot);

        // return the object id
        return $mediaSlot->getId();
    }

    /**
     * Get media document by id
     * @param string $id Id to get media by
     * @return Media Media object
     */
    public function getMediaById($id)
    {
        // Grab the media document
        return $this
            ->documentManager
            ->getRepository('Storage:Media')
            ->findOneById($id)
        ;
    }

    /**
     * Mark a piece of media as having been uploaded
     * @param string $id   Media id
     * @param string $type Media type
     */
    public function markAsUploaded($id, $type)
    {
        // Load the media
        $media = $this->getMediaById($id);
        if (null === $media) {
            throw new DoesNotExistException($id);
        }

        // Update the media
        $media->setUploaded(true);
        $media->setMediaType($type);

        // Persist and flush
        $this->documentManager->persist($media);
        $this->documentManager->flush($media);
    }

    /**
     * Mark media as ready to be accessed by user
     * @param string  $id    Media id
     * @param boolean $ready Ready status (true by default)
     */
    public function markAsReady($id, $ready = true)
    {
        // Load the media
        $media = $this->getMediaById($id);
        if (null === $media) {
            throw new DoesNotExistException($id);
        }

        // Update the media
        $media->setReady($ready);

        // Persist and flush
        $this->documentManager->persist($media);
        $this->documentManager->flush($media);
    }
}
