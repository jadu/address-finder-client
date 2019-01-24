<?php

namespace Jadu\AddressFinderClient;

use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use GuzzleHttp\Psr7\Request;
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
     * @return Address[]
     */
    public function findPropertiesByPostCode($postcode)
    {
        $endpointExtenstion = str_replace('{postcode}', $postcode, $this->configuration->propertyLookupSearchPath);
 
        $endpoint = $this->configuration->baseUri .$endpointExtenstion;
        $headers = array('X-Authentication-Key' => $this->configuration->apiKey);
        // $response = $this->httpClient->sendRequest(
        //     $messageFactory->createRequest('GET', urlencode($endpoint, $headers))
        // );

        $config = ['timeout' => 5, 'headers' => $headers];
        // ...
        $guzzle = new GuzzleClient($config);
        $adapter = new GuzzleAdapter($guzzle);
        $request = new Request('GET', $endpoint);
        $response = $adapter->sendRequest($request);

        if ($response->getStatusCode() == 200)
        {
            // deserialise and return
            return '200';
        }

        return 'Error';
    }
}
