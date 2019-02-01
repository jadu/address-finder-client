<?php

namespace Jadu\AddressFinderClient\Exception;

use Exception;

/**
 * AddressFinderException.
 *
 * @author Jadu Ltd.
 */
class AddressFinderMappingException extends Exception
{
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
