<?php

namespace Jadu\AddressFinderClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Jadu\AddressFinderClient\Exception\AddressFinderException;
use Jadu\AddressFinderClient\Exception\AddressFinderHttpResponseException;
use Jadu\AddressFinderClient\Helpers\AddressFinderClientConfigurationMapper;
use Jadu\AddressFinderClient\Helpers\ModelMapper;
use Jadu\AddressFinderClient\Model\Address\Contract\AddressInterface;
use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;
use Stash\Pool;

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

    /**
     * @var ModelMapper
     */
    private $modelMapper;

    /**
     * @var AddressFinderClientConfigurationMapper
     */
    private $addressFinderClientConfigurationMapper;

    /**
     * @var Pool
     */
    protected $pool;

    /**
     * @param Client $client
     * @param Pool   $pool
     */
    public function __construct(Client $client, Pool $pool)
    {
        $this->client = $client;
        $this->modelMapper = new ModelMapper();
        $this->addressFinderClientConfigurationMapper = new AddressFinderClientConfigurationMapper();
        $this->pool = $pool;
    }

    /**
     * @param string $url
     * @param int $cacheTimeout
     *
     * @return AddressFinderClientConfigurationModel
     */
    public function fetchAddressFinderClientConfiguration($url, $cacheTimeout)
    {
        try {
            $item = $this->pool->getItem($url);

            $addressFinderClientConfiguration = $item->get();

            if ($item->isMiss()) {
                $response = $this->client->request('GET', $url);
                $responseBody = $response->getBody();
                $statusCode = $response->getStatusCode();

                if (200 == $statusCode) {
                    $addressFinderClientConfiguration = $this->addressFinderClientConfigurationMapper->mapFetchAddressFinderClientConfigurationResponse($responseBody->getContents());
                    $item->expiresAfter($cacheTimeout);
                    $item->set($addressFinderClientConfiguration);
                } else {
                    // Throw Exception for any errors less than a 400 status code.
                    $exception = new AddressFinderHttpResponseException($statusCode);
                    $exception->setMessage("The server didn't respond with a 200 status code or a status code over 400.");
                    throw $exception;
                }
            }

            return $addressFinderClientConfiguration;
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
     * @param AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel
     *
     * @return bool
     */
    public function fetchStatus(AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel)
    {
        try {
            $endpoint = $addressFinderClientConfigurationModel->getBaseUri() . $addressFinderClientConfigurationModel->getStatusPath();

            $response = $this->client->request('GET', $endpoint);
            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                return true;
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
     * @param AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel
     * @param string $postcode
     *
     * @return Address[]
     */
    public function searchPropertiesByPostCode(AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel, $postcode)
    {
        try {
            $endpointExtenstion = str_replace('{postcode}', urlencode($postcode), $addressFinderClientConfigurationModel->getPropertyLookupSearchPath());
            $endpoint = $addressFinderClientConfigurationModel->getBaseUri() . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $results = $this->modelMapper->mapSearchResponse($responseBody->getContents(), AddressInterface::TYPE_PROPERTY);

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
     * @param AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel
     * @param string $identifier
     *
     * @return Address
     */
    public function fetchPropertyByIdentifier(AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel, $identifier)
    {
        try {
            $endpointExtenstion = str_replace('{identifier}', urlencode($identifier), $addressFinderClientConfigurationModel->getPropertyLookupFetchPath());
            $endpoint = $addressFinderClientConfigurationModel->getBaseUri() . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $result = $this->modelMapper->mapFetchResponse($responseBody->getContents(), AddressInterface::TYPE_PROPERTY);

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
     * @param AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel
     * @param string $term
     *
     * @return Address[]
     */
    public function searchStreetsByTerm(AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel, $term)
    {
        try {
            $endpointExtenstion = str_replace('{term}', urlencode($term), $addressFinderClientConfigurationModel->getStreetLookupSearchPath());
            $endpoint = $addressFinderClientConfigurationModel->getBaseUri() . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                //This is probably not the right mapping method
                $results = $this->modelMapper->mapSearchResponse($responseBody->getContents(), AddressInterface::TYPE_STREET);

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
     * @param AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel
     * @param string $identifier
     *
     * @return Address
     */
    public function fetchStreetByIdentifier(AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel, $identifier)
    {
        try {
            $endpointExtenstion = str_replace('{identifier}', urlencode($identifier), $addressFinderClientConfigurationModel->getStreetLookupFetchPath());
            $endpoint = $addressFinderClientConfigurationModel->getBaseUri() . $endpointExtenstion;

            $response = $this->client->request('GET', $endpoint);
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $result = $this->modelMapper->mapFetchResponse($responseBody->getContents(), AddressInterface::TYPE_STREET);

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
