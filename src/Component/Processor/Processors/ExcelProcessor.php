<?php

namespace Component\Processor\Processors;

use Component\Processor\Processors\AbstractProcessor;
use Component\Processor\StatusManager;
use Component\Storage\FileInfo;
use Component\Preparer\Types\MediaTypes;

/**
 * Processor for Microsoft Word Files
 */
class ExcelProcessor extends AbstractProcessor
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
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel.sheet.macroEnabled.12',
            'application/vnd.ms-excel'
        );
    }

    /**
     * Get the media type that this processor handles
     * @return string Media type
     */
    public function getMediaType()
    {
        return MediaTypes::EXCEL;
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
            2
        );

        // Get the path of the original
        $originalPath = $file->getBasePath() . $file->getMediaFile()->getFilename();

        // Convert to HTML
        $statusManager->startNewPhase();

        $phpExcel = \PHPExcel_IOFactory::load($originalPath);

        $htmlWriter = \PHPExcel_IOFactory::createWriter($phpExcel, 'HTML');
        $htmlWriter->save($file->getBasePath() . 'output.html');

        $statusManager->endPhase();

        // Convert to CSV
        $statusManager->startNewPhase();

        $htmlWriter = \PHPExcel_IOFactory::createWriter($phpExcel, 'CSV');
        $htmlWriter->save($file->getBasePath() . 'output.csv');

        $statusManager->endPhase();

        // Register new files in the database
        $this->storage->addFile($file->getMediaId(),
            $file->getBasePath() . 'output.html',
            'output.html', 'text/html');
        $this->storage->addFile($file->getMediaId(),
            $file->getBasePath() . 'output.csv',
            'output.csv', 'text/csv');

        // Mark the pdf as ready
        $this->storage->markAsReady($file->getMediaId());

        // Complete
        return true;
    }
}
