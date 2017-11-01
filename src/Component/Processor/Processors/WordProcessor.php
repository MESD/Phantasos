<?php

namespace Component\Processor\Processors;

use Component\Processor\Processors\AbstractProcessor;
use Component\Processor\StatusManager;
use Component\Storage\FileInfo;
use Component\Preparer\Types\MediaTypes;
use PhpOffice\PhpWord\IOFactory;

/**
 * Processor for Microsoft Word Files
 */
class WordProcessor extends AbstractProcessor
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
            'application/msword'
        );
    }

    /**
     * Get the media type that this processor handles
     * @return string Media type
     */
    public function getMediaType()
    {
        return MediaTypes::WORD;
    }


    /**
     * Process the media file
     * @param  FileInfo $file    Original File
     * @return boolean          Operation success
     */
    public function process(FileInfo $file)
    {
        // Create a new status manager
        $statusManager = new StatusManager(
            $file->getMediaId(),
            $this->storage,
            1
        );

        // Get the path of the original
        $originalPath = $file->getBasePath() . $file->getMediaFile()->getFilename();

        // Convert to HTML
        $statusManager->startNewPhase();

        try {
            $phpWord = IOFactory::load($originalPath);
        } catch(\Exception $e) {
            $phpWord = IOFactory::load($originalPath, 'MsDoc');
        }

        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        $htmlWriter->save($file->getBasePath() . 'output.html');

        $statusManager->endPhase();

        // Register new files in the database
        $this->storage->addFile($file->getMediaId(),
            $file->getBasePath() . 'output.html',
            'output.html', 'text/html');

        // Mark the pdf as ready
        $this->storage->markAsReady($file->getMediaId());

        // Complete
        return true;
    }
}
