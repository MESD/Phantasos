<?php

namespace Component\Alerter;

interface AlerterInterface
{
    /**
     * Call the client application and tell them that a particular media is go
     * @param strings $callbackRoute Route to send the request to
     * @param strings $mediaId       Id of the media in question
     */
    public function alertMediaIsReady($callbackRoute, $mediaId);
}
