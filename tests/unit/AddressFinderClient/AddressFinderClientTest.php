<?php

namespace Jadu\Tests\AddressFinderClient;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jadu\AddressFinderClient\AddressFinderClient;
use Jadu\AddressFinderClient\Exception\AddressFinderHttpResponseException;
use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;
use Jadu\AddressFinderClient\Model\ArrayOfProperty;
use Jadu\AddressFinderClient\Model\Property;
use PHPUnit_Framework_TestCase;

class AddressFinderClientTest extends PHPUnit_Framework_TestCase
{
    private $validPostcode = 'LE19+1RJ';

    protected function setUp()
    {
        parent::setUp();
    }

    public function testRequestExpectingValidResponse()
    {
        //Arrange
        $expectedResult = [$this->createTestPropertyOne(), $this->createTestPropertyTwo()];
        $responseResult = new ArrayOfProperty();
        $responseResult->properties = $expectedResult;

        $client = $this->createClient(200, $responseResult);

        $addressFinderClient = new AddressFinderClient($this->createConfiguration(), $client);

        //Act
        $results = $addressFinderClient->findPropertiesByPostCode($this->validPostcode);

        //Assert
        $this->assertEquals($results, $expectedResult);
    }

    public function testRequestExpectingEmptyResponse()
    {
        //Arrange
        $expectedResult = [];
        $responseResult = new ArrayOfProperty();
        $responseResult->properties = [];

        $client = $this->createClient(200, $responseResult);

        $addressFinderClient = new AddressFinderClient($this->createConfiguration(), $client);

        //Act
        $results = $addressFinderClient->findPropertiesByPostCode($this->validPostcode);

        //Assert
        $this->assertEquals($results, $expectedResult);
    }

    public function testRequestExpecting401Exception()
    {
        //Arrange
        $expectedStatusCode = 401;
        $responseResult = new ArrayOfProperty();
        $responseResult->properties = [];

        $client = $this->createClient($expectedStatusCode, $responseResult);

        $addressFinderClient = new AddressFinderClient($this->createConfiguration(), $client);

        $exception = null;

        //Act
        try {
            $results = $addressFinderClient->findPropertiesByPostCode($this->validPostcode);
        } catch (AddressFinderHttpResponseException $ex) {
            $exception = $ex;
        }

        //Assert
        $this->assertEquals($exception->getStatusCode(), $expectedStatusCode);
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

    private function createClient($statusCode, $expectedResults)
    {
        $response = new Response($statusCode, [], json_encode($expectedResults));
        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return $client;
    }

    private function createConfiguration()
    {
        $configuration = new AddressFinderClientConfigurationModel();

        $configuration->baseUri = 'http://localhost:8000';
        $configuration->apiKey = 'Xc31982x53LP98Fsce';
        $configuration->statusPath = '/status';
        $configuration->propertyLookupSearchPath = '/property/search/{postcode}';
        $configuration->propertyLookupFetchPath = '/property/fetch/{identifier}';
        $configuration->streetLookupSearchPath = '/street/search/{term}';
        $configuration->streetLookupFetchPath = '/street/fetch/{identifier}';

        return $configuration;
    }
}
