<?php

namespace Jadu\AddressFinderClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Jadu\AddressFinderClient\Exception\AddressFinderException;
use Jadu\AddressFinderClient\Exception\AddressFinderHttpResponseException;
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
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $results = $this->mapProperties($responseBody->getContents());

                return $results;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                throw new AddressFinderHttpResponseException($statusCode);
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            throw new AddressFinderHttpResponseException($e->getResponse()->getStatusCode());
        } catch (\Exception $e) {
            throw new AddressFinderException();
        }
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
