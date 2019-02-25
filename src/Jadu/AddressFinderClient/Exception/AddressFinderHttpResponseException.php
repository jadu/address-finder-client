<?php

namespace Jadu\AddressFinderClient\Exception;

use Exception;

/**
 * AddressFinderException.
 *
 * @author Jadu Ltd.
 */
class AddressFinderHttpResponseException extends Exception
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * AddressFinderHttpResponseException constructor.
     *
     * @param $statusCode
     */
    public function __construct($statusCode)
    {
        $this->message = 'The server responded with a ' . $statusCode . ' status code.';
        $this->statusCode = $statusCode;
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
     * Get Status Code.
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
