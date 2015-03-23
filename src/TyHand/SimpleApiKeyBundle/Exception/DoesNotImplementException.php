<?php

namespace TyHand\SimpleApiKeyBundle\Exception;

/**
 * Exception for when a given service does not have the necessary interface
 *
 * @author Tyler Hand <https://github.com/tyhand>
 */
class DoesNotImplementException extends RuntimeException
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * Class string of the object that caused the exception
     * @var string
     */
    private $exceptingClass;

    /**
     * String name of the interface that is needed
     * @var string
     */
    private $neededInterface;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param mixed     $failedObject    Object that triggered the exception
     * @param mixed     $neededInterface String name of the needed interface
     * @param Exception $previous        Previous exception if exists
     */
    public function __construct(
        $failedObject,
        $neededInterface,
        \Exception $previous = null)
    {
        // Set the properties
        $this->exceptingClass = get_class($failedObject);
        $this->neededInterface = $neededInterface;

        // Call the parent constructor
        parent::__construct(
            sprintf(
                '"%s" failed to implement required interface "%s"',
                $this->exceptingClass,
                $this->neededInterface
            ),
            0,
            $previous
        );
    }

    /////////////
    // GETTERS //
    /////////////

    /**
     * Get the value of Class string of the object that caused the exception
     * @return string
     */
    public function getExceptingClass()
    {
        return $this->exceptingClass;
    }

    /**
     * Get the value of String name of the interface that is needed
     * @return string
     */
    public function getNeededInterface()
    {
        return $this->neededInterface;
    }
}
