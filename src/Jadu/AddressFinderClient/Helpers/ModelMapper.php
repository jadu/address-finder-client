<?php

namespace Jadu\AddressFinderClient\Helpers;

use Exception;
use Jadu\AddressFinderClient\Exception\AddressFinderMappingException;
use Jadu\AddressFinderClient\Exception\AddressFinderParsingException;
use Jadu\AddressFinderClient\Model\Address;
use Jadu\ContinuumCommon\Address\Contract\AddressInterface;

/**
 * ModelMapper.
 *
 * @author Jadu Ltd.
 */
class ModelMapper
{
    /**
     * @param string $responseBody
     * @param string $responseType
     *
     * @return AddressInterface[]
     *
     * @throws AddressFinderMappingException
     * @throws AddressFinderParsingException
     */
    public function mapSearchResponse($responseBody, $responseType)
    {
        $body = null;
        try {
            $body = json_decode($responseBody, true);

            if ($body === null) {
                $exception = new AddressFinderParsingException();
                $exception->setResponseObject($responseBody);
                $exception->setMessage('The response contains invalid json');
                throw $exception;
            }

            if (array_key_exists('properties', $body) && null !== $body['properties']) {
                $properties = $body['properties'];

                return $this->mapSearchResponseArray($properties, $responseType);
            } elseif (array_key_exists('streets', $body) && null !== $body['streets']) {
                $streets = $body['streets'];

                return $this->mapSearchResponseArray($streets, $responseType);
            } else {
                $exception = new AddressFinderParsingException();
                $exception->setMessage(
                    'There is no root level key with the name "properties" or "streets" in the json response'
                );
                throw $exception;
            }
        } catch (AddressFinderParsingException $e) {
            throw $e;
        } catch (AddressFinderMappingException $e) {
            $e->setInvalidObject($body);
            throw $e;
        } catch (Exception $e) {
            $exception = new AddressFinderMappingException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }

    /**
     * @param string $responseBody
     * @param string $responseType
     *
     * @return AddressInterface
     *
     * @throws AddressFinderMappingException
     * @throws AddressFinderParsingException
     */
    public function mapFetchResponse($responseBody, $responseType)
    {
        $body = null;
        try {
            $body = json_decode($responseBody, true);

            if ($body === null) {
                $exception = new AddressFinderParsingException();
                $exception->setResponseObject($responseBody);
                $exception->setMessage('The response contains invalid json');
                throw $exception;
            }

            $addressModel = new Address();
            $addressModel->setType($responseType);

            if (array_key_exists('property', $body) && null !== $body['property']) {
                foreach ($body['property'] as $key => $val) {
                    $this->map($addressModel, $key, $val);
                }
            } elseif (array_key_exists('street', $body) && null !== $body['street']) {
                foreach ($body['street'] as $key => $val) {
                    $this->map($addressModel, $key, $val);
                }
            } else {
                $exception = new AddressFinderParsingException();
                $exception->setMessage(
                    'There is no root level key with the name "property" or "street" in the json response'
                );
                throw $exception;
            }

            return $addressModel;
        } catch (AddressFinderParsingException $e) {
            throw $e;
        } catch (AddressFinderMappingException $e) {
            $e->setInvalidObject($body);
            throw $e;
        } catch (Exception $e) {
            $exception = new AddressFinderMappingException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }

    /**
     * @param array $addressArray
     * @param string $responseType
     *
     * @return AddressInterface[]
     *
     * @throws AddressFinderMappingException
     */
    private function mapSearchResponseArray($addressArray, $responseType)
    {
        $results = [];
        if (0 === \count($addressArray)) {
            return [];
        }

        foreach ($addressArray as $address) {
            $addressModel = new Address();
            $addressModel->setType($responseType);

            foreach ($address as $key => $val) {
                $this->map($addressModel, $key, $val);
            }
            array_push($results, $addressModel);
        }

        return $results;
    }

    /**
     * @param Address $address
     * @param string $property
     * @param string $value
     *
     * @throws AddressFinderMappingException
     */
    private function map(Address $address, $property, $value)
    {
        try {
            switch ($property) {
                case 'identifier':
                    $address->setExternalReference($value);
                    break;
                case 'paon':
                    $address->setPaon($value);
                    break;
                case 'saon':
                    $address->setSaon($value);
                    break;
                case 'street_name':
                    $address->setStreet($value);
                    break;
                case 'locality':
                    $address->setLocality($value);
                    break;
                case 'town':
                    $address->setTown($value);
                    break;
                case 'post_town':
                    $address->setPostTown($value);
                    break;
                case 'post_code':
                    $address->setPostCode($value);
                    break;
                case 'easting':
                    $address->setEasting($value);
                    break;
                case 'northing':
                    $address->setNorthing($value);
                    break;
                case 'uprn':
                    $address->setUprn($value);
                    break;
                case 'usrn':
                    $address->setUsrn($value);
                    break;
                case 'logical_status':
                    $address->setLogicalStatus($value);
                    break;
            }
        } catch (Exception $e) {
            $exception = new AddressFinderMappingException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }
}
