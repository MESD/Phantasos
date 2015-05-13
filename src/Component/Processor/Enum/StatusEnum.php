<?php

namespace Component\Processor\Enum;

final class StatusEnum
{
    ///////////////
    // CONSTANTS //
    ///////////////

    const STATUS_QUEUED = 'In queue';
    const STATUS_PROCESSING = 'Processing';
    const STATUS_READY = 'Ready';

    ////////////////////
    // STATIC METHODS //
    ////////////////////

    /**
     * Gets the default status (STATUS_QUEUED)
     */
    public static function getDefaultStatus()
    {
        return self::STATUS_QUEUED;
    }

    /**
     * Check whether a status is the queue status
     * @param string $status Status to check
     */
    public static function isQueued($status)
    {
        return self::STATUS_QUEUED === $status;
    }

    /**
     * Check whether a statis is the processing status
     * @param string $status Status to check
     */
    public static function isProcessing($status)
    {
        return self::STATUS_PROCESSING === $status;
    }

    /**
     * Check whether a statis is the ready status
     * @param string $status Status to check
     */
    public static function isReady($status)
    {
        return self::STATUS_READY === $status;
    }
}
