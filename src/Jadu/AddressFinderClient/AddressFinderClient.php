<?php

namespace Jadu\AddressFinderClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Jadu\AddressFinderClient\Exception\AddressFinderException;
use Jadu\AddressFinderClient\Exception\AddressFinderHttpResponseException;
use Jadu\AddressFinderClient\Helpers\ModelMapper as Mapper;
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
     * @var Client
     */
    protected $client;

    protected $mapper;

    /**
     * @param AddressFinderClientConfigurationModel $configuration
     * @param HttpAdapter $httpAdapter
     */
    public function __construct(AddressFinderClientConfigurationModel $configuration, Client $client)
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->mapper = new Mapper();
    }

    /**
     * @param string $postcode
     *
     * @return Address[]
     */
    public function findPropertiesByPostCode($postcode)
    {
        try {
            $endpointExtenstion = str_replace('{postcode}', urlencode($postcode), $this->configuration->propertyLookupSearchPath);
            $endpoint = $this->configuration->baseUri . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $results = $this->mapper->mapFindResponse($responseBody->getContents());

                return $results;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                throw new AddressFinderHttpResponseException($statusCode);
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            throw new AddressFinderHttpResponseException($e->getResponse()->getStatusCode());
        } catch (AddressFinderHttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            throw new AddressFinderException();
        }
    }

    /**
     * @param string $identifier
     *
     * @return Address
     */
    public function getPropertyByIdentifier($identifier)
    {
        try {
            $endpointExtenstion = str_replace('{identifier}', urlencode($identifier), $this->configuration->propertyLookupFetchPath);
            $endpoint = $this->configuration->baseUri . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $result = $this->mapper->mapGetResponse($responseBody->getContents());

                return $result;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                throw new AddressFinderHttpResponseException($statusCode);
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            throw new AddressFinderHttpResponseException($e->getResponse()->getStatusCode());
        } catch (AddressFinderHttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            throw new AddressFinderException();
        }
    }

    /**
     * @param string $term
     *
     * @return Address[]
     */
    public function findStreetsByTerm($term)
    {
        try {
            $endpointExtenstion = str_replace('{term}', urlencode($postcode), $this->configuration->streetLookupSearchPath);
            $endpoint = $this->configuration->baseUri . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                //This is probably not the right mapping method
                $results = $this->mapper->mapFindResponse($responseBody->getContents());

                return $results;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                throw new AddressFinderHttpResponseException($statusCode);
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            throw new AddressFinderHttpResponseException($e->getResponse()->getStatusCode());
        } catch (AddressFinderHttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            throw new AddressFinderException();
        }
    }

    /**
     * @param string $identifier
     *
     * @return Address
     */
    public function getStreetByIdentifier($identifier)
    {
        try {
            $endpointExtenstion = str_replace('{identifier}', urlencode($identifier), $this->configuration->streetLookupFetchPath);
            $endpoint = $this->configuration->baseUri . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $result = $this->mapper->mapGetResponse($responseBody->getContents());

                return $result;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                throw new AddressFinderHttpResponseException($statusCode);
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            throw new AddressFinderHttpResponseException($e->getResponse()->getStatusCode());
        } catch (AddressFinderHttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            throw new AddressFinderException();
        }
    }
}
