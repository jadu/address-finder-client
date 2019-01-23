<?php

namespace Jadu\AddressFinderClient\Model;

class AddressFinderClientConfigurationModel
{
    /**
     * @var string
     */
    public $apiKey;

    /**
     * @var string
     */
    public $baseUri;

    /**
     * @var string
     */
    public $statusPath;

    /**
     * @var string
     */
    public $propertyLookupSearchPath;

    /**
     * @var string
     */
    public $propertyLookupFetchPath;

    /**
     * @var string
     */
    public $streetLookupSearchPath;

    /**
     * @var string
     */
    public $streetLookupFetchPath;
}
