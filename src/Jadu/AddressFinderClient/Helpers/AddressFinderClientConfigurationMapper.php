<?php

namespace Jadu\AddressFinderClient\Helpers;

use Jadu\AddressFinderClient\Exception\AddressFinderMappingException;
use Jadu\AddressFinderClient\Exception\AddressFinderParsingException;
use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;

/**
 * AddressFinderClientConfigurationMapper.
 *
 * @author Jadu Ltd.
 */
class AddressFinderClientConfigurationMapper
{
    /**
     * @param string $responseBody
     * @param string $responseType
     *
     * @return Address
     */
    public function mapFetchAddressFinderClientConfigurationResponse($responseBody)
    {
        try {
            $body = json_decode($responseBody, true);

            if (is_null($body)) {
                $exception = new AddressFinderParsingException();
                $exception->setMessage('There was an error when trying to parse the $responseBody to json.');
                throw $exception;
            }

            $addressFinderClientConfigurationModel = new AddressFinderClientConfigurationModel();

            foreach ($body as $key => $val) {
                $this->map($addressFinderClientConfigurationModel, $key, $val);
            }

            return $addressFinderClientConfigurationModel;
        } catch (AddressFinderParsingException $e) {
            $e->setResponseObject($body);
            throw $e;
        } catch (AddressFinderMappingException $e) {
            $e->setInvalidObject($body);
            throw $e;
        } catch (\Exception $e) {
            $exception = new AddressFinderMappingException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }

    /**
     * @param AddressFinderClientConfigurationModel $address
     * @param string $property
     * @param string $value
     *
     * @return AddressFinderClientConfigurationModel
     */
    private function map(AddressFinderClientConfigurationModel $address, $property, $value)
    {
        try {
            switch ($property) {
                case 'base_uri':
                    $address->setBaseUri($value);
                    break;
                case 'status_path':
                    $address->setStatusPath($value);
                    break;
                case 'property_lookup':
                    $searchPath = $value['search_path'];
                    $address->setPropertyLookupSearchPath($searchPath);

                    $fetchPath = $value['fetch_path'];
                    $address->setPropertyLookupFetchPath($fetchPath);
                    break;
                case 'street_lookup':
                    $searchPath = $value['search_path'];
                    $address->setStreetLookupSearchPath($searchPath);

                    $fetchPath = $value['fetch_path'];
                    $address->setStreetLookupFetchPath($fetchPath);
                    break;
            }
        } catch (\Exception $e) {
            $exception = new AddressFinderMappingException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }
}
