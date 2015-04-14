<?php

namespace Component\Processor\Processors;

use Component\Processor\Processors\AbstractProcessor;
use Component\Storage\FileInfo;
use Component\Preparer\Types\MediaTypes;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Audio\Vorbis;

/**
 * Processor for standard video types
 */
class AudioProcessor extends AbstractProcessor
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
            'audio/mp4',
            'audio/mpeg',
            'audio/mpeg3',
            'audio/x-mpeg-3',
            'audio/x-mpeg',
            'application/ogg',
            'audio/ogg',
            'audio/flac',
            'audio/vorbis',
            'audio/vnd.rn-realaudio',
            'audio/vnd.wave',
            'audio/webm'
        );
    }

    /**
     * Get the media type that this processor handles
     * @return string Media type
     */
    public function getMediaType()
    {
        return MediaTypes::AUDIO;
    }

    /**
     * Get array of export data
     * @return array Export info
     */
    private function getExports()
    {
        return array(
            'low' => array('bitrate' => 64),
            'high' => array('bitrate' => 192)
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
            // Create the formats
            $mp3 = new Mp3();
            $mp3->setAudioChannels(2);
            $mp3->setAudioKiloBitrate($size['bitrate']);
            $vorbis = new Vorbis();
            $vorbis->setAudioChannels(2);
            $vorbis->setAudioKiloBitrate($size['bitrate']);

            // Resize and encode the video
            $audio = $ffmpeg->open($originalPath);
            $audio
                ->save($mp3, $file->getBasePath() . $name . '.mp3')
                ->save($vorbis, $file->getBasePath() . $name . '.oga')
            ;

            $this->storage->addFile($file->getMediaId(),
                $file->getBasePath() . $name . '.mp3',
                $name . '.mp3', 'audio/mpeg',
                null, null, $size['bitrate']);

            $this->storage->addFile($file->getMediaId(),
                $file->getBasePath() . $name . '.oga',
                $name . '.oga', 'audio/vorbis',
                null, null, $size['bitrate']);
        }

        // Mark the video as ready
        $this->storage->markAsReady($file->getMediaId());

        // Complete
        return true;
    }
}
