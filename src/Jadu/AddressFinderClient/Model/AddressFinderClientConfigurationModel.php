<?php

namespace Jadu\AddressFinderClient\Model;

class AddressFinderClientConfigurationModel
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var string
     */
    private $statusPath;

    /**
     * @var string
     */
    private $propertyLookupSearchPath;

    /**
     * @var string
     */
    private $propertyLookupFetchPath;

    /**
     * @var string
     */
    private $streetLookupSearchPath;

    /**
     * @var string
     */
    private $streetLookupFetchPath;

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    public function setStatusPath($statusPath)
    {
        $this->statusPath = $statusPath;
    }

    public function setPropertyLookupSearchPath($propertyLookupSearchPath)
    {
        $this->propertyLookupSearchPath = $propertyLookupSearchPath;
    }

    public function setPropertyLookupFetchPath($propertyLookupFetchPath)
    {
        $this->propertyLookupFetchPath = $propertyLookupFetchPath;
    }

    public function setStreetLookupSearchPath($streetLookupSearchPath)
    {
        $this->streetLookupSearchPath = $streetLookupSearchPath;
    }

    public function setStreetLookupFetchPath($streetLookupFetchPath)
    {
        $this->streetLookupFetchPath = $streetLookupFetchPath;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getBaseUri()
    {
        return $this->baseUri;
    }

    public function getStatusPath()
    {
        return $this->statusPath;
    }

    public function getPropertyLookupSearchPath()
    {
        return $this->propertyLookupSearchPath;
    }

    public function getPropertyLookupFetchPath()
    {
        return $this->propertyLookupFetchPath;
    }

    public function getStreetLookupSearchPath()
    {
        return $this->streetLookupSearchPath;
    }

    public function getStreetLookupFetchPath()
    {
        return $this->streetLookupFetchPath;
    }
}
