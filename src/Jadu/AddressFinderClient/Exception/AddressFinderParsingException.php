<?php

namespace Jadu\AddressFinderClient\Exception;

use Exception;

/**
 * AddressFinderParsingException.
 *
 * @author Jadu Ltd.
 */
class AddressFinderParsingException extends Exception
{
    /**
     * @var array
     */
    private $responseObject;

    /**
     * set Response Object.
     *
     * @param array
     */
    public function setResponseObject($responseObject)
    {
        $this->responseObject = $responseObject;
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
     * Get Response Object.
     *
     * @return array
     */
    public function getResponseObject()
    {
        return $this->responseObject;
    }
}
