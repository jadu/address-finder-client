<?php

namespace Jadu\AddressFinderClient\Helpers;

use Jadu\AddressFinderClient\Exception\AddressFinderMappingException;
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
        } catch (\Exception $e) {
            throw new AddressFinderMappingException();
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
            $propertiesModel = new PropertyModel();

            foreach ($body as $key => $val) {
                $this->map($propertiesModel, $key, $val);
            }

            return $propertiesModel;
        } catch (\Exception $e) {
            throw new AddressFinderMappingException();
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
            throw new AddressFinderMappingException();
        }
    }
}
