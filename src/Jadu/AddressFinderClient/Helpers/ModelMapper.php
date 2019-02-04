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
    public function mapFindResponse($responseBody)
    {
        try {
            $body = json_decode($responseBody, true);

            if (is_null($body)) {
                $exception = new AddressFinderParsingException();
                $exception->setMessage('There was an error when trying to parse the $responseBody to json.');
                throw $exception;
            }

            $results = [];

            foreach ($body as $property) {
    
                $address = new Address();

                foreach ($property as $key => $val) {
                    $this->map($address, $key, $val);
                }

                array_push($results, $address);
            }

            return $results;
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
    public function mapGetResponse($responseBody)
    {
        try {
            $body = json_decode($responseBody, true);

            if (is_null($body)) {
                $exception = new AddressFinderParsingException();
                $exception->setMessage('There was an error when trying to parse the $responseBody to json.');
                throw $exception;
            }

            $address = new Address();

            foreach ($body as $key => $val) {
                $this->map($address, $key, $val);
            }

            return $address;
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

    private function map($address, $property, $value) {
        try {
            switch ($property) {
                case 'identifier': 
                    $address->setExternalReference($value);
                    break;
                case 'uprn':   
                    $address->setUprn($value);
                    break;
                case 'usrn':
                    $address->setUsrn($value);
                     break;
                case 'paon': 
                    $address->setPaon($value);
                    break;
                case 'street':  
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
                case 'logical_status':   
                    $address->setLogicalStatus($value); 
                    break;
                case 'type': 
                    $address->setType($value);
                    break;
            }
        } catch (\Exception $e) {
                    $exception = new AddressFinderMappingException();
                    $exception->setMessage($e->getMessage());
                    throw $exception;
        }
        
    }
}
