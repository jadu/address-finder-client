<?php

namespace Jadu\AddressFinderClient\Model;

class AddressFinderClientConfigurationModel
{
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

    /**
     * Set Base Uri.
     *
     * @param string
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * Set Status Path.
     *
     * @param string
     */
    public function setStatusPath($statusPath)
    {
        $this->statusPath = $statusPath;
    }

    /**
     * Set Property Lookup Search Path.
     *
     * @param string
     */
    public function setPropertyLookupSearchPath($propertyLookupSearchPath)
    {
        $this->propertyLookupSearchPath = $propertyLookupSearchPath;
    }

    /**
     * Set Property Lookup Fetch Path.
     *
     * @param string
     */
    public function setPropertyLookupFetchPath($propertyLookupFetchPath)
    {
        $this->propertyLookupFetchPath = $propertyLookupFetchPath;
    }

    /**
     * Set Street Lookup Search Path.
     *
     * @param string
     */
    public function setStreetLookupSearchPath($streetLookupSearchPath)
    {
        $this->streetLookupSearchPath = $streetLookupSearchPath;
    }

    /**
     * Set Street Lookup Fetch Path.
     *
     * @param string
     */
    public function setStreetLookupFetchPath($streetLookupFetchPath)
    {
        $this->streetLookupFetchPath = $streetLookupFetchPath;
    }

    /**
     * Get Base Uri.
     *
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * Get Status Path.
     *
     * @return string
     */
    public function getStatusPath()
    {
        return $this->statusPath;
    }

    /**
     * Get Property Lookup Search Path.
     *
     * @return string
     */
    public function getPropertyLookupSearchPath()
    {
        return $this->propertyLookupSearchPath;
    }

    /**
     * Get Property Lookup Fetch Path.
     *
     * @return string
     */
    public function getPropertyLookupFetchPath()
    {
        return $this->propertyLookupFetchPath;
    }

    /**
     * Get Street Lookup Search Path.
     *
     * @return string
     */
    public function getStreetLookupSearchPath()
    {
        return $this->streetLookupSearchPath;
    }

    /**
     * Get Street Lookup Fetch Path.
     *
     * @return string
     */
    public function getStreetLookupFetchPath()
    {
        return $this->streetLookupFetchPath;
    }
}
