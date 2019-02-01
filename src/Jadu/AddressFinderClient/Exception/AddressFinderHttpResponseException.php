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
    protected $message;
    protected $statusCode;

    public function __construct($statusCode)
    {
        $this->message = 'The server responded with a ' . $statusCode . ' status code.';
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}
