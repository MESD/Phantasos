<?php

namespace TyHand\SimpleApiKeyMongoStorageBundle\Util;

use TyHand\SimpleApiKeyBundle\User\ApiUser;
use TyHand\SimpleApiKeyMongoStorageBundle\Document\ApiUserDocument;

/**
 * Utility class to convert a parent ApiUser class to an ApiUserDocument child
 * class
 */
class ApiUserToDocumentConverter
{
    ////////////////////
    // STATIC METHODS //
    ////////////////////

    /**
     * Create a new ApiUserDocument from an ApiUser object
     * @param ApiUserInterface $parent ApiUser to convert
     * @return ApiUserDocument
     */
    public static function convertToNewDocument(ApiUser $parent)
    {
        // Create the new document
        $document = new ApiUserDocument();
        $document->setApplicationName($parent->getApplicationName());
        $document->setApplicationUri($parent->getApplicationUri());
        $document->setApplicationDescription($parent->getApplicationDescription());
        $document->setContactEmail($parent->getContactEmail());
        $document->setContactName($parent->getContactName());
        $document->setActive($parent->getActive());
        $document->setApiKey($parent->getApiKey());
        $document->setRoles($parent->getRoles());
        $document->setLastUse(new \DateTime());

        // Return the completed document
        return $document;
    }
}
