<?php

namespace Jadu\AddressFinderClient\Helpers;

use Jadu\AddressFinderClient\Exception\AddressFinderMappingException;
use Jadu\AddressFinderClient\Exception\AddressFinderParsingException;
use Jadu\AddressFinderClient\Model\Address\Model\Address;

/**
 * AddressFinderClient.
 *
 * @author Jadu Ltd.
 */
class ModelMapper
{
    /**
     * @param string $responseBody
     *
     * @return PropertyModel[]
     */
    public function mapSearchResponse($responseBody, $responseType)
    {
        try {
            $body = json_decode($responseBody, true);

            if (is_null($body)) {
                $exception = new AddressFinderParsingException();
                $exception->setMessage('There was an error when trying to parse the $responseBody to json.');
                throw $exception;
            }

            if (null !== $body['properties']) {
                $properties = $body['properties'];

                return $this->mapSearchResponseArray($properties, $responseType);
            } elseif (null !== $body['streets']) {
                $streets = $body['streets'];

                return $this->mapSearchResponseArray($streets, $responseType);
            } else {
                $exception = new AddressFinderParsingException();
                $exception->setMessage("There is no root level key with the name 'properties' or 'streets' in response json.");
                throw $exception;
            }
        } catch (AddressFinderParsingException $e) {
            throw $e;
        } catch (AddressFinderMappingException $e) {
            throw $e;
        } catch (\Exception $e) {
            $exception = new AddressFinderMappingException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }

    /**
     * @param string $responseBody
     *
     * @return PropertyModel
     */
    public function mapFetchResponse($responseBody, $responseType)
    {
        try {
            $body = json_decode($responseBody, true);

            if (is_null($body)) {
                $exception = new AddressFinderParsingException();
                $exception->setMessage('There was an error when trying to parse the $responseBody to json.');
                throw $exception;
            }

            $addressModel = new Address();
            $addressModel->setType($responseType);

            if (null !== $body['property']) {
                foreach ($body['property'] as $key => $val) {
                    $this->map($addressModel, $key, $val);
                }
            } elseif (null !== $body['street']) {
                foreach ($body['street'] as $key => $val) {
                    $this->map($addressModel, $key, $val);
                }
            } else {
                $exception = new AddressFinderParsingException();
                $exception->setMessage("There is no root level key with the name 'property' or 'street' in response json.");
                throw $exception;
            }

            return $addressModel;
        } catch (AddressFinderParsingException $e) {
            throw $e;
        } catch (AddressFinderMappingException $e) {
            throw $e;
        } catch (\Exception $e) {
            $exception = new AddressFinderMappingException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }

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

    private function map($address, $property, $value)
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
        } catch (\Exception $e) {
            $exception = new AddressFinderMappingException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }
}
