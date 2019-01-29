<?php

namespace Jadu\AddressFinderClient;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;
use Jadu\AddressFinderClient\Model\Property as PropertyModel;

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
     * @var Client
     */
    protected $client;

    /**
     * @param AddressFinderClientConfigurationModel $configuration
     * @param HttpAdapter $httpAdapter
     */
    public function __construct(AddressFinderClientConfigurationModel $configuration, Client $client)
    {
        $this->configuration = $configuration;
        $this->client = $client;
    }

    /**
     * @param string $postcode
     *
     * @return Address[]
     */
    public function findPropertiesByPostCode($postcode)
    {
        $endpointExtenstion = str_replace('{postcode}', urlencode($postcode), $this->configuration->propertyLookupSearchPath);
        $endpoint = $this->configuration->baseUri . $endpointExtenstion;

        try {

            $response = $this->client->request('GET', $endpoint);
             $responseBody =$response->getBody();
            
            if (200 == $response->getStatusCode()) {
                $results = $this->mapProperties($responseBody->getContents());
                return $results;
            }
            else{
                echo 'null';
                return null;
            }
        } catch (\Exception $e) {
            echo "Caught Exception:" . $e->getMessage();
            return 'Error';
        }

        return 'Error';
    }

    /**
     * @param string $responseBody
     *
     * @return PropertyModel[]
     */
    private function mapProperties($responseBody)
    {
        $body = json_decode($responseBody, true);
        
        $results = [];

        foreach ($body as $properties) {
          
            foreach ($properties as $property) {
                $propertiesModel = new PropertyModel();
                $propertiesModel->mapData($property);
                array_push($results, $propertiesModel);
            }
        }
        return $results;
    }
}
