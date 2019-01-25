<?php

namespace Jadu\AddressFinderClient;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client as HttpAdapter;
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
     * @param AddressFinderClientConfigurationModel $configuration
     */
    public function __construct(AddressFinderClientConfigurationModel $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $postcode
     *
     * @return Address[]
     */
    public function findPropertiesByPostCode($postcode)
    {
        $endpointExtenstion = str_replace('{postcode}', $postcode, $this->configuration->propertyLookupSearchPath);
        $endpoint = $this->configuration->baseUri . $endpointExtenstion;

        $headers = ['X-Authentication-Key' => $this->configuration->apiKey];
        $config = ['timeout' => 5, 'headers' => $headers];

        try {
            $httpClient = new HttpClient($config);
            $httpAdapter = new HttpAdapter($httpClient);
            $request = new Request('GET', $endpoint);

            $response = $httpAdapter->sendRequest($request);

            if (200 == $response->getStatusCode()) {
                $results = $this->mapProperties($response->getBody());

                return $results;
            } else {
                return 'Error';
            }
        } catch (\Exception $e) {
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
