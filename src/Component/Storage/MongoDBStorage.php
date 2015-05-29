<?php

namespace Component\Storage;

use Component\Storage\FileInfo;
use Component\Storage\StorageInterface;
use Component\Storage\Document\Media;
use Component\Storage\Document\MediaFile;
use Component\Storage\Util\Converter\UploadTicketRequestToMedia;
use Component\Storage\FileKey\FileKeyInterface;
use Component\Preparer\UploadTicketRequest;
use Component\Alerter\AlerterInterface;
use Component\Exceptions\Media\DoesNotExistException;
use Component\Exceptions\Media\NotUploadedException;
use Component\Exceptions\Media\OriginalNoLongerExistsException;
use Component\Exceptions\Media\NotReadyException;
use Component\Processor\Enum\StatusEnum;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * File key
     * @var FileKeyInterface
     */
    protected $fileKey;

    /**
     * Base directory
     * @var string
     */
    protected $directory;

    /**
     * Alerter Component
     * @var AlerterInterface
     */
    protected $alerter;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param DocumentManager  $documentManager Document Manager
     * @param string           $directory       Media directory
     * @param FileKeyInterface $fileKey         File key
     * @param AlerterInterface $alerter         Alerter Component
     */
    public function __construct(
        DocumentManager $documentManager,
        $directory,
        FileKeyInterface $fileKey,
        AlerterInterface $alerter)
    {
        // Set the properties
        $this->documentManager = $documentManager;
        $this->directory = $directory;
        $this->fileKey = $fileKey;
        $this->alerter = $alerter;
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
     * Get a media document by a media file
     * @param MediaFile $mediaFileId Media file to get the parent of
     * @return Media Parent media document
     */
    public function getMediaByMediaFile(MediaFile $mediaFile)
    {
        return $this
            ->documentManager
            ->createQueryBuilder('Storage:Media')
                ->field('files')
                    ->includesReferenceTo($mediaFile)
            ->getQuery()
            ->getSingleResult()
        ;

    }

    /**
     * Get a media file document by its id
     * @param string $id Media file id
     * @return MediaFile Media file document
     */
    public function getMediaFileById($id)
    {
        // Grab the media file document
        return $this
            ->documentManager
            ->getRepository('Storage:MediaFile')
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

        // If the media document has a callback route, call it
        if (null !== $media->getCallback()) {
            $ret = $this->alerter->alertMediaIsReady(
                $media->getCallback(),
                $media->getId()
            );
            $media->setCallbackSent($ret);
        }

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

        // Place the file permnately into the media store
        $original->move($this->directory . '/' . $fileKey, $fileName);

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
    public function getOriginalFileInfo($mediaId)
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
                // Create the path to the file
                $basePath = $this->directory . '/' .
                    $this->fileKey->getBaseFileKey($mediaId);

                // Create the file info set
                return new FileInfo($media, $mediaFile, $basePath);
            }
        }

        // If the original was not returned at this point, it no longer exists
        throw new OriginalNoLongerExistsException($mediaId);
    }

    /**
     * Get file info for a file by its own id
     * @param string $mediaFileId Id for the media file
     * @return FileInfo File info for the file
     */
    public function getFileInfo($mediaFileId)
    {
        // Get the media file in question
        $mediaFile = $this->getMediaFileById($mediaFileId);
        if (null === $mediaFile) {
            throw new DoesNotExistException($mediaFileId);
        }

        // Get the parent media document
        $media = $this->getMediaByMediaFile($mediaFile);
        if (null === $mediaFile) {
            throw new DoesNotExistException($mediaFileId);
        }

        // Generate the base path
        $basePath = $this->directory . '/' .
            $this->fileKey->getBaseFileKey($media->getId());

        // Create the file info object and return
        return new FileInfo($media, $mediaFile, $basePath);
    }

    /**
     * Get the info on a media file
     * @param string $mediaId Id of the media
     * @return MediaInfo Info
     */
    public function getMediaInfo($mediaId)
    {
        // Get the media document
        $media = $this->getMediaById($mediaId);
        if (null === $media) {
            throw new DoesNotExistException($mediaId);
        }

        // Create the new media info object
        $mediaInfo = new MediaInfo();
        $mediaInfo->setApplicationName($media->getApplicationName());
        $mediaInfo->setHideToOthers($media->getHideToOthers());
        $mediaInfo->setTags($media->getTags());
        $mediaInfo->setSecurity($media->getSecurityTags());
        $mediaInfo->setMediaType($media->getMediaType());
        $mediaInfo->setStatus($media->getStatus());
        $mediaInfo->setProcessingPercentage($media->getProcessingPercentage());
        $mediaInfo->setReady($media->getReady());

        // Go through each file and generate its info
        if ($media->isReady()) {
            foreach($media->getFiles() as $file)
            {
                // Create a new media file info object
                $mediaFileInfo = new MediaFileInfo();
                $mediaFileInfo->setMimeType($file->getContentType());
                $mediaFileInfo->setMediaFileId($file->getId());
                $mediaFileInfo->setFileName($file->getFileName());
                $mediaFileInfo->setSize(
                    $file->getWidth(),
                    $file->getHeight(),
                    $file->getBitrate()
                );

                // Check if this is an original file
                if ($file->getOriginal()) {
                    $mediaInfo->setOriginalExists(true);
                }

                // Add the media file info to the media info
                $mediaInfo->addMediaFileInfo($mediaFileInfo);
            }
        }

        // return
        return $mediaInfo;
    }

    /**
     * Update the status of media
     * @param string  $mediaId    Media id of the media to update
     * @param string  $status     Status to set
     */
    public function updateMediaStatus($mediaId, $status)
    {
        // Load the media
        $media = $this->getMediaById($mediaId);
        if (null === $media) {
            throw new DoesNotExistException($mediaId);
        }

        // Set
        $media->setStatus($status);

        // Persist and flush
        $this->documentManager->persist($media);
        $this->documentManager->flush($media);

        // Return
        return true;
    }

    /**
     * Update the percentage for the media
     * @param string $mediaId    Media id of the media to update
     * @param float  $percentage Percentage complete
     */
    public function updateMediaPercentage($mediaId, $percentage)
    {
        // Load the media
        $media = $this->getMediaById($mediaId);
        if (null === $media) {
            throw new DoesNotExistException($mediaId);
        }

        // Set
        $media->setProcessingPercentage($percentage);

        // Persist and flush
        $this->documentManager->persist($media);
        $this->documentManager->flush($media);

        // Return
        return true;
    }

    /**
     * Add a file to the database
     * @param string $mediaId     Media Id
     * @param string $fullPath    Full file path
     * @param string $name        File name
     * @param string $contentType Mime type
     * @param int    $width       Width if applicable
     * @param int    $height      Height if applicable
     * @param string $bitRate     Bitrate if applicable
     */
    public function addFile(
        $mediaId,
        $fullPath,
        $name,
        $contentType,
        $width = null,
        $height = null,
        $bitRate = null)
    {
        // Load the media
        $media = $this->getMediaById($mediaId);
        if (null === $media) {
            throw new DoesNotExistException($mediaId);
        }

        // Create a new media file document
        $file = new MediaFile();
        $file->setFileName($name);
        $file->setContentType($contentType);
        $file->setOriginal(false);
        $file->setWidth($width);
        $file->setHeight($height);
        $file->setBitRate($bitRate);

        // Add the document to the original media document
        $media->addFile($file);

        // Persist and flush
        $this->documentManager->persist($file);
        $this->documentManager->flush();

        return true;
    }

    /**
     * Change the media type (for use in cases where the MIME type was misleading)
     * @param string $mediaId Media Id
     * @param string $type    New media type
     */
    public function changeMediaType($mediaId, $type)
    {
        // Load the media
        $media = $this->getMediaById($mediaId);
        if (null === $media) {
            throw new DoesNotExistException($mediaId);
        }

        // Edit the media type
        $media->setMediaType($type);

        // Flush
        $this->documentManager->flush();

        return true;
    }

    /**
     * Return a list of medias that failed to process
     * @return array List of failures
     */
    public function getFailedMedia()
    {
        // Get the media items that have a failed status
        return $this
            ->documentManager
            ->getRepository('Storage:Media')
            ->findByStatus(StatusEnum::STATUS_FAILED)
        ;
    }
}
