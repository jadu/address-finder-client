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
     * @var Client
     */
    private $client;

    private $mapper;

    /**
     * @param HttpAdapter $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->mapper = new Mapper();
    }

    /**
     * @param AddressFinderClientConfigurationModel $configuration
     * @param string $postcode
     *
     * @return Address[]
     */
    public function findPropertiesByPostCode($configuration, $postcode)
    {
        try {
            $endpointExtenstion = str_replace('{postcode}', urlencode($postcode), $configuration->getPropertyLookupSearchPath());
            $endpoint = $configuration->getBaseUri() . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $results = $this->mapper->mapFindResponse($responseBody->getContents());

                return $results;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                $exception = new AddressFinderHttpResponseException($statusCode);
                $exception->setMessage("The server didn't respond with a 200 status code or a status code over 400.");
                throw $exception;
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            $exception = new AddressFinderHttpResponseException($e->getResponse()->getStatusCode());
            $exception->setMessage($e->getMessage());
            throw $exception;
        } catch (AddressFinderHttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            $exception = new AddressFinderException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }

    /**
     * @param AddressFinderClientConfigurationModel $configuration
     * @param string $identifier
     *
     * @return Address
     */
    public function getPropertyByIdentifier($configuration, $identifier)
    {
        try {
            $endpointExtenstion = str_replace('{identifier}', urlencode($identifier), $configuration->getPropertyLookupFetchPath());
            $endpoint = $configuration->getBaseUri() . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $result = $this->mapper->mapGetResponse($responseBody->getContents());

                return $result;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                $exception = new AddressFinderHttpResponseException($statusCode);
                $exception->setMessage("The server didn't respond with a 200 status code or a status code over 400.");
                throw $exception;
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            $exception = new AddressFinderHttpResponseException($e->getResponse()->getStatusCode());
            $exception->setMessage($e->getMessage());
            throw $exception;
        } catch (AddressFinderHttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            $exception = new AddressFinderException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }

    /**
     * @param AddressFinderClientConfigurationModel $configuration
     * @param string $term
     *
     * @return Address[]
     */
    public function findStreetsByTerm($configuration, $term)
    {
        try {
            $endpointExtenstion = str_replace('{term}', urlencode($postcode), $configuration->getStreetLookupSearchPath());
            $endpoint = $configuration->getBaseUri() . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                //This is probably not the right mapping method
                $results = $this->mapper->mapFindResponse($responseBody->getContents());

                return $results;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                $exception = new AddressFinderHttpResponseException($statusCode);
                $exception->setMessage("The server didn't respond with a 200 status code or a status code over 400.");
                throw $exception;
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            $exception = new AddressFinderHttpResponseException($e->getResponse()->getStatusCode());
            $exception->setMessage($e->getMessage());
            throw $exception;
        } catch (AddressFinderHttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            $exception = new AddressFinderException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }

    /**
     * @param AddressFinderClientConfigurationModel $configuration
     * @param string $identifier
     *
     * @return Address
     */
    public function getStreetByIdentifier($configuration, $identifier)
    {
        try {
            $endpointExtenstion = str_replace('{identifier}', urlencode($identifier), $configuration->getStreetLookupFetchPath());
            $endpoint = $configuration->getBaseUri() . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $result = $this->mapper->mapGetResponse($responseBody->getContents());

                return $result;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                $exception = new AddressFinderHttpResponseException($statusCode);
                $exception->setMessage("The server didn't respond with a 200 status code or a status code over 400.");
                throw $exception;
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            $exception = new AddressFinderHttpResponseException($e->getResponse()->getStatusCode());
            $exception->setMessage($e->getMessage());
            throw $exception;
        } catch (AddressFinderHttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            $exception = new AddressFinderException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }
}
