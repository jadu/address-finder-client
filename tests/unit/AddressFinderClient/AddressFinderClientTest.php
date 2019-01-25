<?php

namespace Jadu\Tests\AddressFinderClient;

use Jadu\AddressFinderClient\AddressFinderClient;
use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;
use Jadu\AddressFinderClient\Model\Property;
use PHPUnit_Framework_TestCase;

class AddressFinderClientTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testRequestExpectingValidResponse()
    {
        //Arrange
        $expectedResults = [$this->createTestPropertyOne(), $this->createTestPropertyTwo()];
        $validPostcode = 'LE19+1RJ';
        $validApiKey = 'Xc31982x53LP98Fsce';
        $configuration = $this->createConfiguration($validApiKey);

        $addressFinderClient = new AddressFinderClient($configuration);

        //Act
        $results = $addressFinderClient->findPropertiesByPostCode($validPostcode);

        //Assert
        $this->assertEquals($results, $expectedResults);
    }

    public function testRequestExpectingEmptyResponse()
    {
        //Arrange
        $expectedResults = [];
        $invalidPostcode = 'LE19+1PJ';
        $validApiKey = 'Xc31982x53LP98Fsce';
        $configuration = $this->createConfiguration($validApiKey);

        $addressFinderClient = new AddressFinderClient($configuration);

        //Act
        $results = $addressFinderClient->findPropertiesByPostCode($invalidPostcode);

        //Assert
        $this->assertEquals($results, $expectedResults);
    }

    public function testRequestExpecting401Error()
    {
        //Arrange
        $expectedResult = 'Error';
        $validPostcode = 'LE19+1RJ';
        $invalidApiKey = 'InvalidApiKey';

        $configuration = $this->createConfiguration($invalidApiKey);

        $addressFinderClient = new AddressFinderClient($configuration);

        //Act
        $result = $addressFinderClient->findPropertiesByPostCode($validPostcode);

        //Assert
        $this->assertEquals($result, $expectedResult);
    }

    private function createTestPropertyOne()
    {
        $property = new Property();

        $property->identifier = '10001228376';
        $property->uprn = '10001228376';
        $property->usrn = '2802454';
        $property->paon = '1 UNIVERSE HOUSE';
        $property->street_name = 'MERUS COURT';
        $property->locality = 'MERIDIAN BUSINESS PARK';
        $property->town = 'BRAUNSTONE TOWN';
        $property->post_code = 'LE19 1RJ';
        $property->easting = '454801';
        $property->northing = '302081';
        $property->logical_status = '1';

        return $property;
    }

    private function createTestPropertyTwo()
    {
        $property = new Property();

        $property->identifier = '45671258378';
        $property->uprn = '45671258378';
        $property->usrn = '2935454';
        $property->paon = '2 UNIVERSE HOUSE';
        $property->street_name = 'MERUS COURT';
        $property->locality = 'MERIDIAN BUSINESS PARK';
        $property->town = 'BRAUNSTONE TOWN';
        $property->post_code = 'LE19 1RJ';
        $property->easting = '454801';
        $property->northing = '302081';
        $property->logical_status = '2';

        return $property;
    }

    /**
     * @param string $apiKey
     *
     * @return AddressFinderClientConfigurationModel
     */
    private function createConfiguration($apiKey)
    {
        $configuration = new AddressFinderClientConfigurationModel();

        $configuration->baseUri = 'http://localhost:8000';
        $configuration->apiKey = $apiKey;
        $configuration->statusPath = '/status';
        $configuration->propertyLookupSearchPath = '/property/search/{postcode}';
        $configuration->propertyLookupFetchPath = '/property/fetch/{identifier}';
        $configuration->streetLookupSearchPath = '/street/search/{term}';
        $configuration->streetLookupFetchPath = '/street/fetch/{identifier}';

        return $configuration;
    }
}
