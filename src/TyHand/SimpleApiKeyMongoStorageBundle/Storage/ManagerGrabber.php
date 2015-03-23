<?php

namespace TyHand\SimpleApiKeyMongoStorageBundle\Storage;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Simple class to grab the configured manager from the container as I dont want
 * to inject the whole registry into the full storage class for my own purely
 * asthectical reasons
 */
class ManagerGrabber
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Document Manager
     * @var DocumentManager
     */
    private $manager;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param ManagerRegistry $registry    ODM Manager Registry
     * @param string          $managerName Name of the manager
     */
    public function __construct(ManagerRegistry $registry, $managerName)
    {
        // Attempt to get the manager
        $this->manager = $registry->getManager($managerName);
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Get the manager
     * @return DocumentManager Manager for the registry defined in config
     */
    public function getManager()
    {
        return $this->manager;
    }
}
