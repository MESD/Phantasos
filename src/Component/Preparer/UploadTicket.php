<?php

namespace Component\Preparer;

use Component\Exceptions\Api\MissingRouteException;

/**
 * Basic object to pass the upload ticket request information
 */
class UploadTicket
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Secured route to upload files to
     * @var string
     */
    private $uploadRoute;

    /**
     * Expire time
     * @var \Datetime
     */
    private $expireTime;

    /**
     * Ticket id
     * @var string
     */
    private $ticketId;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     */
    public function __construct($ticketId, \DateTime $expiration)
    {
        // Set stuff
        $this->ticketId = $ticketId;
        $this->expiration = $expiration;
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Convert the object to an array that can be converted to JSON
     * @return array
     */
    public function toArray()
    {
        // Check that the route was added
        if (null === $this->uploadRoute) {
            throw new MissingRouteException();
        }

        return array(
            'upload_route' => $this->uploadRoute,
            'media_id' => $this->ticketId,
            'expiration' => $this->expiration->format('Y-m-d H:i:s')
        );
    }

    /////////////////////////
    // GETTERS AND SETTERS //
    /////////////////////////

    /**
     * Get the value of Secured route to upload files to
     *
     * @return string
     */
    public function getUploadRoute()
    {
        return $this->uploadRoute;
    }

    /**
     * Set the value of Secured route to upload files to
     *
     * @param string uploadRoute
     *
     * @return self
     */
    public function setUploadRoute($uploadRoute)
    {
        $this->uploadRoute = $uploadRoute;

        return $this;
    }

    /**
     * Get the value of Expire time
     *
     * @return \Datetime
     */
    public function getExpireTime()
    {
        return $this->expireTime;
    }

    /**
     * Set the value of Expire time
     *
     * @param \Datetime expireTime
     *
     * @return self
     */
    public function setExpireTime(\Datetime $expireTime)
    {
        $this->expireTime = $expireTime;

        return $this;
    }

    /**
     * Get the value of Ticket id
     *
     * @return string
     */
    public function getTicketId()
    {
        return $this->ticketId;
    }

    /**
     * Set the value of Ticket id
     *
     * @param string ticketId
     *
     * @return self
     */
    public function setTicketId($ticketId)
    {
        $this->ticketId = $ticketId;

        return $this;
    }
}
