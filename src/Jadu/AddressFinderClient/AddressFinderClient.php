<?php

namespace Jadu\AddressFinderClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Jadu\AddressFinderClient\Exception\AddressFinderException;
use Jadu\AddressFinderClient\Exception\AddressFinderHttpResponseException;
use Jadu\AddressFinderClient\Helpers\AddressFinderClientConfigurationMapper;
use Jadu\AddressFinderClient\Helpers\ModelMapper;
use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;
use Jadu\ContinuumCommon\Address\Contract\AddressInterface;
use Jadu\ContinuumCommon\Address\Model\Address;
use Psr\Http\Message\ResponseInterface;

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
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->modelMapper = new ModelMapper();
        $this->addressFinderClientConfigurationMapper = new AddressFinderClientConfigurationMapper();
    }

    /**
     * @param string $url
     * @param $apiKey
     *
     * @return AddressFinderClientConfigurationModel
     *
     * @throws AddressFinderException
     * @throws AddressFinderHttpResponseException
     * @throws GuzzleException
     */
    public function fetchAddressFinderClientConfiguration($url, $apiKey)
    {
        try {
            $response = $this->client->request('GET', $url, ['headers' => ['X-Authentication-Key' => $apiKey]]);
            $responseBody = $response->getBody();
            $statusCode = $response->getStatusCode();

            if (200 == $statusCode) {
                $addressFinderClientConfiguration = $this->addressFinderClientConfigurationMapper
                    ->mapFetchAddressFinderClientConfigurationResponse($responseBody->getContents());
            } else {
                // Throw Exception for any errors less than a 400 status code.
                $exception = new AddressFinderHttpResponseException($statusCode);
                $exception->setMessage($response->getReasonPhrase());
                throw $exception;
            }

            return $addressFinderClientConfiguration;
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            throw new AddressFinderHttpResponseException($e->getCode());
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
     *
     * @throws AddressFinderException
     * @throws AddressFinderHttpResponseException
     * @throws GuzzleException
     */
    public function fetchStatus(AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel)
    {
        try {
            $response = $this->request(
                $addressFinderClientConfigurationModel,
                $addressFinderClientConfigurationModel->getStatusPath()
            );
            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                return true;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                $exception = new AddressFinderHttpResponseException($statusCode);
                $exception->setMessage($response->getReasonPhrase());
                throw $exception;
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            $exception = new AddressFinderHttpResponseException($e->getCode());
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
     * @return AddressInterface[]
     *
     * @throws AddressFinderException
     * @throws AddressFinderHttpResponseException
     * @throws GuzzleException
     */
    public function searchPropertiesByPostCode(
        AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel,
        $postcode
    ) {
        try {
            $endpointExtenstion = str_replace(
                '{postcode}',
                rawurlencode($postcode),
                $addressFinderClientConfigurationModel->getPropertyLookupSearchPath()
            );
            $response = $this->request(
                $addressFinderClientConfigurationModel,
                $endpointExtenstion
            );
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $results = $this->modelMapper->mapSearchResponse(
                    $responseBody->getContents(),
                    AddressInterface::TYPE_PROPERTY
                );

                return $results;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                $exception = new AddressFinderHttpResponseException($statusCode);
                $exception->setMessage($response->getReasonPhrase());
                throw $exception;
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            $exception = new AddressFinderHttpResponseException($e->getCode());
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
     *
     * @throws AddressFinderException
     * @throws AddressFinderHttpResponseException
     * @throws GuzzleException
     */
    public function fetchPropertyByIdentifier(
        AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel,
        $identifier
    ) {
        try {
            $endpointExtenstion = str_replace(
                '{identifier}',
                rawurlencode($identifier),
                $addressFinderClientConfigurationModel->getPropertyLookupFetchPath()
            );
            $response = $this->request(
                $addressFinderClientConfigurationModel,
                $endpointExtenstion
            );
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $result = $this->modelMapper->mapFetchResponse(
                    $responseBody->getContents(),
                    AddressInterface::TYPE_PROPERTY
                );

                return $result;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                $exception = new AddressFinderHttpResponseException($statusCode);
                $exception->setMessage($response->getReasonPhrase());
                throw $exception;
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            $exception = new AddressFinderHttpResponseException($e->getCode());
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
     * @return AddressInterface[]
     *
     * @throws AddressFinderException
     * @throws AddressFinderHttpResponseException
     * @throws GuzzleException
     */
    public function searchStreetsByTerm(
        AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel,
        $term
    ) {
        try {
            $endpointExtenstion = str_replace(
                '{term}',
                rawurlencode($term),
                $addressFinderClientConfigurationModel->getStreetLookupSearchPath()
            );
            $response = $this->request(
                $addressFinderClientConfigurationModel,
                $endpointExtenstion
            );
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                //This is probably not the right mapping method
                $results = $this->modelMapper->mapSearchResponse(
                    $responseBody->getContents(),
                    AddressInterface::TYPE_STREET
                );

                return $results;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                $exception = new AddressFinderHttpResponseException($statusCode);
                $exception->setMessage($response->getReasonPhrase());
                throw $exception;
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            $exception = new AddressFinderHttpResponseException($e->getCode());
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
     *
     * @throws AddressFinderException
     * @throws AddressFinderHttpResponseException
     * @throws GuzzleException
     */
    public function fetchStreetByIdentifier(
        AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel,
        $identifier
    ) {
        try {
            $endpointExtenstion = str_replace(
                '{identifier}',
                rawurlencode($identifier),
                $addressFinderClientConfigurationModel->getStreetLookupFetchPath()
            );
            $response = $this->request(
                $addressFinderClientConfigurationModel,
                $endpointExtenstion
            );
            $responseBody = $response->getBody();

            $statusCode = $response->getStatusCode();
            if (200 == $statusCode) {
                $result = $this->modelMapper->mapFetchResponse(
                    $responseBody->getContents(),
                    AddressInterface::TYPE_STREET
                );

                return $result;
            } else {
                // Throw Exception for any errors less than a 400 status code.
                $exception = new AddressFinderHttpResponseException($statusCode);
                $exception->setMessage($response->getReasonPhrase());
                throw $exception;
            }
        } catch (RequestException $e) {
            // Throw Exception for any errors grater than than a 400 status code.
            $exception = new AddressFinderHttpResponseException($e->getCode());
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
     * @param string $path
     *
     * @return mixed|ResponseInterface
     *
     * @throws GuzzleException
     */
    private function request(AddressFinderClientConfigurationModel $addressFinderClientConfigurationModel, $path)
    {
        return $this->client->request(
            'GET',
            $addressFinderClientConfigurationModel->getBaseUri() . $path,
            ['headers' => ['X-Authentication-Key' => $addressFinderClientConfigurationModel->getApiKey()]]
        );
    }
}
