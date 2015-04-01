<?php

namespace Component\Storage;

use Component\Storage\StorageInterface;
use Component\Storage\Document\Media;
use Component\Storage\Util\Converter\UploadTicketRequestToMedia;
use Component\Preparer\UploadTicketRequest;

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
}
