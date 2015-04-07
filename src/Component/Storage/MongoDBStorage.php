<?php

namespace Component\Storage;

use Component\Storage\FileInfo;
use Component\Storage\StorageInterface;
use Component\Storage\Document\Media;
use Component\Storage\Document\MediaFile;
use Component\Storage\Util\Converter\UploadTicketRequestToMedia;
use Component\Storage\FileKey\FileKeyInterface;
use Component\Preparer\UploadTicketRequest;
use Component\Exceptions\Media\DoesNotExistException;
use Component\Exceptions\Media\NotUploadedException;
use Component\Exceptions\Media\OriginalNoLongerExistsException;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gaufrette\Filesystem;
use Gaufrette\File;

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

    /**
     * Abstracted filesystem
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * File key
     * @var FileKeyInterface
     */
    protected $fileKey;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param DocumentManager  $documentManager Document Manager
     * @param Filesystem       $filesystem      Filesystem
     * @param FileKeyInterface $fileKey         File key
     */
    public function __construct(
        DocumentManager $documentManager,
        Filesystem $filesystem,
        FileKeyInterface $fileKey)
    {
        // Set the properties
        $this->documentManager = $documentManager;
        $this->filesystem = $filesystem;
        $this->fileKey = $fileKey;
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

    /**
     * Add the original file
     * @param UploadedFile $original Uploaded File
     * @param string       $mediaId  Media id
     * @return boolean Operation success
     */
    public function addOriginalFile(UploadedFile $original, $mediaId)
    {
        // Generate the file key
        $fileKey = $this->fileKey->getBaseFileKey($mediaId);
        $fileName = 'original.' . $original->guessExtension();

        // Create a new media file document
        $file = new MediaFile();
        $file->setFileName($fileName);
        $file->setContentType($original->getMimeType());
        $file->setOriginal(true);

        // Load the media
        $media = $this->getMediaById($mediaId);
        if (null === $media) {
            throw new DoesNotExistException($mediaId);
        }

        // Add the document to the original media document
        $media->addFile($file);

        // Write the file to the filesystem
        $this->filesystem->write(
            $fileKey . $fileName,
            file_get_contents($original->getPathname())
        );

        // Persist and flush
        $this->documentManager->persist($file);
        $this->documentManager->flush();

        // return
        return true;
    }

    /**
     * Get the original file for a given media id
     * @param string $mediaId Media Id
     * @return FileInfo Original file if exists
     */
    public function getOriginalFile($mediaId)
    {
        // Load the media
        $media = $this->getMediaById($mediaId);
        if (null === $media) {
            throw new DoesNotExistException($mediaId);
        }

        // Check that the file was uploaded
        if (!$media->getUploaded()) {
            throw new NotUploadedException($mediaId);
        }

        // Get the original file
        foreach($media->getFiles() as $mediaFile) {
            // Check if the file is the original
            if ($mediaFile->getOriginal()) {
                // Get the file out of the file store
                $file = $this->filesystem->get(
                    $this->fileKey->getBaseFileKey($mediaId) .
                    $media->getFileName()
                );

                // Create the file info set
                return new FileInfo($file, $media, $mediaFile);
            }
        }

        // If the original was not returned at this point, it no longer exists
        throw new OriginalNoLongerExistsException($mediaId);
    }
}
