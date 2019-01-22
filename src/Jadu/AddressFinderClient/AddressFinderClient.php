<?php

namespace Jadu\AddressFinderClient;

use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;

/**
 * AddressFinderClient.
 *
 * @author Jadu Ltd.
 */
class AddressFinderClient
{
    /**
     * @var AddressFinderClientConfigurationModel
     */
    protected $configuration;

    /**
     * @param AddressFinderClientConfigurationModel $configuration
     */
    public function __construct(AddressFinderClientConfigurationModel $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Place holder Method.
     */
    public function getAllProperties()
    {
        return 'TODO : Get All Properties';
    }
}
