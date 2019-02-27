<?php

namespace Jadu\AddressFinderClient\Helpers;

use Exception;
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
     *
     * @return AddressFinderClientConfigurationModel
     *
     * @throws AddressFinderMappingException
     * @throws AddressFinderParsingException
     */
    public function mapFetchAddressFinderClientConfigurationResponse($responseBody)
    {
        $body = null;
        try {
            $body = json_decode($responseBody, true);

            if (is_null($body)) {
                $exception = new AddressFinderParsingException();
                $exception->setResponseObject($responseBody);
                $exception->setMessage('The response contains invalid json');
                throw $exception;
            }

            $addressFinderClientConfigurationModel = new AddressFinderClientConfigurationModel();

            foreach ($body as $key => $val) {
                $this->map($addressFinderClientConfigurationModel, $key, $val);
            }

            return $addressFinderClientConfigurationModel;
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
     * @param AddressFinderClientConfigurationModel $config
     * @param string $property
     * @param string $value
     *
     * @throws AddressFinderMappingException
     */
    private function map(AddressFinderClientConfigurationModel $config, $property, $value)
    {
        try {
            switch ($property) {
                case 'base_uri':
                    $config->setBaseUri($value);
                    break;
                case 'status_path':
                    $config->setStatusPath($value);
                    break;
                case 'property_lookup':
                    $searchPath = $value['search_path'];
                    $config->setPropertyLookupSearchPath($searchPath);

                    $fetchPath = $value['fetch_path'];
                    $config->setPropertyLookupFetchPath($fetchPath);
                    break;
                case 'street_lookup':
                    $searchPath = $value['search_path'];
                    $config->setStreetLookupSearchPath($searchPath);

                    $fetchPath = $value['fetch_path'];
                    $config->setStreetLookupFetchPath($fetchPath);
                    break;
            }
        } catch (\Exception $e) {
            $exception = new AddressFinderMappingException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }
}
