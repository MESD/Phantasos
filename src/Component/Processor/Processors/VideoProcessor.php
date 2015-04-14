<?php

namespace Component\Processor\Processors;

use Component\Processor\Processors\AbstractProcessor;
use Component\Storage\FileInfo;
use Component\Preparer\Types\MediaTypes;

use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\Ogg;
use FFMpeg\Format\Video\WebM;

/**
 * Processor for standard video types
 */
class VideoProcessor extends AbstractProcessor
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Timeout for a ffmpeg job
     * @var int
     */
    private $timeout;

    /**
     * Number of threads to allow ffmpeg to use
     * @var int
     */
    private $threads;

    /**
     * Location of the FFMpeg Binary
     * @var string
     */
    private $ffmpegBinary;

    /**
     * Location of the FFProbe Binary
     * @var string
     */
    private $ffprobeBinary;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    public function __construct(
        $timeout = 60000,
        $threads = 8,
        $ffmpegBinary = '/usr/bin/ffmpeg',
        $ffprobeBinary = '/usr/bin/ffprobe'
    ) {
        // Set
        $this->timeout = $timeout;
        $this->threads = $threads;
        $this->ffmpegBinary = $ffmpegBinary;
        $this->ffprobeBinary = $ffprobeBinary;
    }

    //////////////////// /////
    // IMPLEMENTED METHODS //
    /////////////////////////

    /**
     * Get an array of supported mime types by this processor
     * @return array List of supported mime types
     */
    public function getSupportedTypes()
    {
        return array(
            'video/x-ms-asf',
            'video/avi',
            'video/msvideo',
            'video/x-msvideo',
            'video/x-ms-wmv',
            'video/x-dv',
            'video/x-flv',
            'video/x-m4v',
            'video/x-matroska',
            'video/quicktime',
            'video/mp4',
            'video/mpeg',
            'video/ogg',
            'video/webm',
            'application/x-winamp'
        );
    }

    /**
     * Get the media type that this processor handles
     * @return string Media type
     */
    public function getMediaType()
    {
        return MediaTypes::VIDEO;
    }

    /**
     * Get array of export data
     * @return array Export info
     */
    private function getExports()
    {
        return array(
            'small' => array('width' => 640, 'height' => 360, 'bitrate' => 750),
            'large' => array('width' => 1280, 'height' => 720, 'bitrate' => 2500)
        );
    }

    /**
     * Process the media file
     * @param  FileInfo $file    Original File
     * @return boolean          Operation success
     */
    public function process(FileInfo $file)
    {
        // Init the FFmpeg library
        $ffmpeg = FFMpeg::create(array(
            'ffmpeg.binaries'  => $this->ffmpegBinary,
            'ffprobe.binaries' => $this->ffprobeBinary,
            'timeout' => $this->timeout,
            'ffmpeg.threads' => $this->threads
        ));

        // Prep the storage for streaming
        $originalPath = $file->getBasePath()
            . $file->getMediaFile()->getFilename();

        // Create the different sizes
        foreach ($this->getExports() as $name => $size)
        {
            // Generate the formats
            $h264 = new X264('libmp3lame', 'libx264');
            $h264->setKiloBitrate($size['bitrate']);
            $webm = new WebM();
            $webm->setKiloBitrate($size['bitrate']);
            $ogg = new Ogg();
            $ogg->setKiloBitrate($size['bitrate']);

            // Resize and encode the video
            $video = $ffmpeg->open($originalPath);
            $video
                ->filters()
                ->resize(new Dimension($size['width'], $size['height']))
                ->synchronize()
            ;
            $video
                ->frame(TimeCode::fromSeconds(10))
                ->save($file->getBasePath() . $name . '-frame.png')
            ;
            $video
                ->save($h264, $file->getBasePath() . $name . '.mp4')
                ->save($webm, $file->getBasePath() . $name . '.webm')
                ->save($ogg, $file->getBasePath() . $name . '.ogv')
            ;

            // Register the files in the db
            $this->storage->addFile($file->getMediaId(),
                $file->getBasePath() . $name . '-frame.png',
                $name . '-frame.png', 'image/png', $size['width'], $size['height']);

            $this->storage->addFile($file->getMediaId(),
                $file->getBasePath() . $name . '.mp4',
                $name . '.mp4', 'video/mp4',
                $size['width'], $size['height'], $size['bitrate']);

            $this->storage->addFile($file->getMediaId(),
                $file->getBasePath() . $name . '.webm',
                $name . '.webm', 'video/webm',
                $size['width'], $size['height'], $size['bitrate']);

            $this->storage->addFile($file->getMediaId(),
                $file->getBasePath() . $name . '.ogv',
                $name . '.ogv', 'video/ogg',
                $size['width'], $size['height'], $size['bitrate']);
        }

        // Mark the video as ready
        $this->storage->markAsReady($file->getMediaId());

        // Complete
        return true;
    }
}
