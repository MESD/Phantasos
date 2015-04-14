<?php

namespace Component\Alerter;

use Component\Alerter\AlerterInterface;
use GuzzleHttp\Client;

class Alerter implements AlerterInterface
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Timeout for the alerts request in seconds
     * @var int
     */
    private $timeout;


    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param integer $timeout Timeout for request in seconds
     */
    public function __construct($timeout = 15)
    {
        // Set
        $this->timeout = $timeout;
    }

    ///////////////////////
    // INTERFACE METHODS //
    ///////////////////////

    /**
     * Call the client application and tell them that a particular media is go
     * @param strings $callbackRoute Route to send the request to
     * @param strings $mediaId       Id of the media in question
     */
    public function alertMediaIsReady($callbackRoute, $mediaId)
    {
        // Create a new guzzle client
        $client = new Client();

        // Attempt to make the callback
        try {
            $response = $client->post(
                $callbackRoute,
                array(
                    'body' => array(
                        'media_id' => $mediaId
                    )
                ),
                array(
                    'timeout' => $this->timeout
                )
            );
            if (200 == $response->getStatusCode()) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
