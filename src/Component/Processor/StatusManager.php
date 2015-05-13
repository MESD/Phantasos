<?php

namespace Component\Processor;

use Component\Storage\StorageInterface;
use Component\Processor\Enum\StatusEnum;

/**
 * Tracks the status of a processing job
 */
class StatusManager
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Media id
     * @var string
     */
    protected $mediaId;

    /**
     * Storage Module
     * @var StorageInterface
     */
    protected $storage;

    /**
     * Number of 'sub-jobs' for the processing job (e.g. converting to three
     * video formats in 2 resolutions would have 6 phases)
     * @var int
     */
    protected $phases;

    /**
     * Current phase
     * @var int
     */
    protected $currentPhase;

    /**
     * Percentage (as int) complete of the current phase
     * @var int
     */
    protected $currentPhasePercentage;

    /**
     * Last update time
     * @var int
     */
    protected $lastUpdate;

    /**
     * Number of seconds between database calls
     * @var float
     */
    protected $timer;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param string           $mediaId Media Id
     * @param StorageInterface $storage Storage Module
     * @param int              $phases  Phases
     * @param int              $timer   Number of seconds between database calls
     */
    public function __construct(
        $mediaId,
        StorageInterface $storage,
        $phases = 1,
        $timer = 1.0)
    {
        // Set
        $this->storage = $storage;
        $this->phases = $phases;
        $this->timer = $timer;
        $this->mediaId = $mediaId;

        // Init
        $this->currentPhase = 0;
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Starts a new phase
     */
    public function startNewPhase()
    {
        // If current phase is 0, update the status
        if (0 === $this->currentPhase) {
            $this->updateStatus(StatusEnum::STATUS_PROCESSING);
        }

        // Increment the current phase
        $this->currentPhase++;

        // Reset the percentage
        $this->currentPhasePercentage = 0;
    }

    /**
     * Performs the operations for the end of a phase
     */
    public function endPhase()
    {
        // Set the current percentage to 100 because math
        $this->currentPhasePercentage = 100;

        // Force an update
        $this->update();

        // If this phase is the last one, update the status
        if ($this->phases === $this->currentPhase) {
            $this->updateStatus(StatusEnum::STATUS_READY);
        }
    }

    /**
     * Set the percentage for the current phase
     * @param int $percentage Percentage as int
     */
    public function setCurrentPercentage($percentage)
    {
        $this->currentPhasePercentage = $percentage;
        $this->changed();
    }

    /**
     * Check if it is time to update
     */
    public function changed()
    {
        // Check if the difference between now and the last is greater than the timer
        $now = microtime(true);
        if ($this->lastUpdate === null || ($now - $this->lastUpdate) >= $this->timer) {
            $this->update();
        }
    }

    /**
     * Gets the overall percentage as int
     * @return int overall percentage
     */
    public function getOverallPercentage()
    {
        // Overall = (((current phase - 1) * 100) + current percentage) / phases
        return round(
            ((($this->currentPhase - 1) * 100) + $this->currentPhasePercentage) / $this->phases,
            2
        );
    }

    /**
     * Updates the storage with the current status
     */
    protected function update()
    {
        // Update Percentage
        $this->storage->updateMediaPercentage($this->mediaId, $this->getOverallPercentage());

        // Set the last update time to now
        $this->lastUpdate = microtime(true);
    }

    /**
     * Updates the status of the media
     * @param string $status Status to set
     */
    protected function updateStatus($status)
    {
        // Update
        $this->storage->updateMediaStatus($this->mediaId, $status);
    }
}
