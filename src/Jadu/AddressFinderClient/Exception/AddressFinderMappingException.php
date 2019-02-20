<?php

namespace Jadu\AddressFinderClient\Exception;

use Exception;

/**
 * AddressFinderMappingException.
 *
 * @author Jadu Ltd.
 */
class AddressFinderMappingException extends Exception
{
    /**
     * @var array
     */
    private $invalidObject;

    /**
     * set Invalid Object.
     *
     * @param array
     */
    public function setInvalidObject($invalidObject)
    {
        $this->invalidObject = $invalidObject;
    }

    /**
     * set Message.
     *
     * @param string
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get Invalid Object.
     *
     * @return array
     */
    public function getInvalidObject()
    {
        return $this->invalidObject;
    }
}
