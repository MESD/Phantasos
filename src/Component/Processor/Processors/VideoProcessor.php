<?php

namespace Component\Processor\Processors;

use Component\Processor\Processors\AbstractProcessor;
use Component\Processor\StatusManager;
use Component\Processor\Enum\StatusEnum;
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

    /**
     * To fallback on with audio files in video containers
     * @var AudioProcessor
     */
    private $audioProcessor;

    /**
     * Logger
     * @var \Psr\Logger\LoggerInterface
     */
    private $logger;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    public function __construct(
        AudioProcessor $audioProcessor,
        $timeout = 60000,
        $threads = 8,
        $ffmpegBinary = '/usr/bin/ffmpeg',
        $ffprobeBinary = '/usr/bin/ffprobe',
        $logger = null
    ) {
        // Set
        $this->audioProcessor = $audioProcessor;
        $this->timeout = $timeout;
        $this->threads = $threads;
        $this->ffmpegBinary = $ffmpegBinary;
        $this->ffprobeBinary = $ffprobeBinary;
        $this->logger = $logger;
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
            'application/x-winamp',
            'video/3gpp'
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
            'small' => array('width' => 640, 'height' => 360, 'bitrate' => 750, 'audio-bitrate' => 48),
            'large' => array('width' => 1280, 'height' => 720, 'bitrate' => 2500, 'audio-bitrate' => 96)
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
        ), $this->logger);

        // Prep the storage for streaming
        $originalPath = $file->getBasePath()
            . $file->getMediaFile()->getFilename();

        // Check that the video is not actually a misguided audio file
        $test = $ffmpeg->open($originalPath);
        if (!method_exists($test->filters(), 'resize')) {
            // Change media type to audio
            $this->storage->changeMediaType($file->getMediaId(), MediaTypes::AUDIO);
            // Send to the audio processor
            return $this->audioProcessor->process($file);
            // END
        }

        // Create a new status manager
        $statusManager = new StatusManager(
            $file->getMediaId(),
            $this->storage,
            count($this->getExports()) * 3
        );

        // Create the different sizes
        foreach ($this->getExports() as $name => $size)
        {
            // Create an anonymous function for progress callback
            $progressFunc = function($video, $format, $percentage) use ($statusManager)
            {
                $statusManager->setCurrentPercentage($percentage);
            };

            // Generate the formats
            $h264 = new X264('libmp3lame', 'libx264');
            $h264->setKiloBitrate($size['bitrate']);
            $h264->setAudioKiloBitrate($size['audio-bitrate']);
            $h264->on('progress', $progressFunc);
            $webm = new WebM();
            $webm->setKiloBitrate($size['bitrate']);
            $webm->setAudioKiloBitrate($size['audio-bitrate']);
            $webm->on('progress', $progressFunc);
            $ogg = new Ogg();
            $ogg->setKiloBitrate($size['bitrate']);
            $ogg->setAudioKiloBitrate($size['audio-bitrate']);
            $ogg->on('progress', $progressFunc);

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

            $statusManager->startNewPhase();
            $video->save($h264, $file->getBasePath() . $name . '.mp4');
            $statusManager->endPhase();

            $statusManager->startNewPhase();
            $video->save($webm, $file->getBasePath() . $name . '.webm');
            $statusManager->endPhase();

            $statusManager->startNewPhase();
            $video->save($ogg, $file->getBasePath() . $name . '.ogv');
            $statusManager->endPhase();

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
