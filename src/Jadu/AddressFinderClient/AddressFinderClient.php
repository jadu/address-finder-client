<?php

namespace Jadu\AddressFinderClient;

use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;

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
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @param AddressFinderClientConfigurationModel $configuration
     */
    public function __construct(AddressFinderClientConfigurationModel $configuration)
    {
        $this->configuration = $configuration;
        $this->httpClient = HttpClientDiscovery::find();
        $this->messageFactory = MessageFactoryDiscovery::find();
    }

    /**
     * @param string $postCode
     * 
     * @return array|Address
     */
    public function findPropertiesByPostCode(string $postCode)
    {
        $headers = array('X-Authentication-Key' => $this->configuration->apiKey);
        $response = $this->httpClient->sendRequest(
            $messageFactory->createRequest('GET', urlencode($this->configuration->baseUri . str_replace('{postCode}', $postcode, $this->configuration->propertyLookupSearchPath), $headers))
        );

        if ($response->getStatusCode() == 200)
        {
            // deserialise and return
            return '200';
        }

        return 'Error';
    }
}
