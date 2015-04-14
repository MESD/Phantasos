<?php

namespace Component\Processor\Processors;

use Component\Processor\Processors\AbstractProcessor;
use Component\Storage\FileInfo;
use Component\Preparer\Types\MediaTypes;

use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;

/**
 * Processor for standard image types
 */
class ImageProcessor extends AbstractProcessor
{
    /////////////////////////
    // IMPLEMENTED METHODS //
    /////////////////////////

    /**
     * Get an array of supported mime types by this processor
     * @return array List of supported mime types
     */
    public function getSupportedTypes()
    {
        return array(
            'image/bmp',
            'image/x-windows-bmp',
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/x-xbitmap',
            'image/x-xbm',
            'image/xbm',
            'image/xpm',
            'image/x-xpixmap'
        );
    }

    /**
     * Get the media type that this processor handles
     */
    public function getMediaType()
    {
        return MediaTypes::IMAGE;
    }

    /**
     * Process the media file
     * @param  FileInfo $file    Original File
     * @return boolean          Operation success
     */
    public function process(FileInfo $file)
    {
        // Init the Imagine library
        $imagine = new Imagine();

        // Get the path to the original
        $originalPath = $file->getBasePath()
            . $file->getMediaFile()->getFilename();

        // Create a thumbnail sized image (75 x 75)
        $image = $imagine
            ->open($originalPath)
            ->resize(new Box(75, 75))
            ->save($file->getBasePath() . 'thumbnail.png');
        ;
        $this->storage->addFile($file->getMediaId(),
            $file->getBasePath() . 'thumbnail.png',
            'thumbnail.png', 'image/png', 75, 75, '72ppi');

        // Create a small image (320 x 240)
        $image = $imagine
            ->open($originalPath)
            ->resize(new Box(320, 240))
            ->save($file->getBasePath() . 'small.png');
        ;
        $this->storage->addFile($file->getMediaId(),
            $file->getBasePath() . 'small.png',
            'small.png', 'image/png', 320, 240, '72ppi');

        // Create a medium image (640 x 480)
        $image = $imagine
            ->open($originalPath)
            ->resize(new Box(640, 480))
            ->save($file->getBasePath() . 'medium.png');
        ;
        $this->storage->addFile($file->getMediaId(),
            $file->getBasePath() . 'medium.png',
            'medium.png', 'image/png', 640, 480, '72ppi');

        // Create a large image (1024 x 768)
        $image = $imagine
            ->open($originalPath)
            ->resize(new Box(1024, 768))
            ->save($file->getBasePath() . 'large.png');
        ;
        $this->storage->addFile($file->getMediaId(),
            $file->getBasePath() . 'large.png',
            'large.png', 'image/png', 1024, 768, '72ppi');

        // Mark the image as ready
        $this->storage->markAsReady($file->getMediaId());

        // return
        return true;
    }
}
