<?php

namespace Jadu\AddressFinderClient\Helpers;

use Jadu\AddressFinderClient\Exception\AddressFinderMappingException;
use Jadu\AddressFinderClient\Exception\AddressFinderParsingException;
use Jadu\AddressFinderClient\Model\Address\Model\Address as PropertyModel;

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

            foreach ($body as $properties) {
                foreach ($properties as $property) {
                    $propertiesModel = new PropertyModel();

                    foreach ($property as $key => $val) {
                        $this->map($propertiesModel, $key, $val);
                    }

                    array_push($results, $propertiesModel);
                }
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

            $propertiesModel = new PropertyModel();

            foreach ($body as $key => $val) {
                $this->map($propertiesModel, $key, $val);
            }

            return $propertiesModel;
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

    private function map($class, $property, $value)
    {
        try {
            $method = ‘set’ . ucfirst($property); //camelCase() method name

            if (method_exists($class, $method)) {
                $reflection = new \ReflectionMethod($class, $method);
                if (!$reflection->isPublic()) {
                    throw new RuntimeException();
                }
            }

            if (property_exists($class, $property)) {
                $reflectedProperty = new \ReflectionProperty($class, $property);
                $reflectedProperty->setAccessible(true);

                return $reflectedProperty->setValue($class, $value);
            }
        } catch (\Exception $e) {
            $exception = new AddressFinderMappingException();
            $exception->setMessage($e->getMessage());
            throw $exception;
        }
    }
}
