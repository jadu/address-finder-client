<?php

namespace Jadu\Tests\AddressFinderClient;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jadu\AddressFinderClient\AddressFinderClient;
use Jadu\AddressFinderClient\Exception\AddressFinderHttpResponseException;
use Jadu\AddressFinderClient\Model\Address\Model\Address as AddressModel;
use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;
use Jadu\AddressFinderClient\Model\Test\ArrayOfTestAddress;
use Jadu\AddressFinderClient\Model\Test\TestAddress as TestAddressModel;
use PHPUnit_Framework_TestCase;

class AddressFinderClientTest extends PHPUnit_Framework_TestCase
{
    private $validPostcode = 'LE19+1RJ';
    private $validIdentifier = '10001228376';
    private $validTerm = 'meruscourt';

    protected function setUp()
    {
        parent::setUp();
    }

    public function testFindPropertiesByPostCodeExpectingValidResponse()
    {
        //Arrange
        $expectedResult = [$this->createExpectedPropertyResponseOne(), $this->createExpectedPropertyResponseTwo()];

        $responseData = new ArrayOfTestAddress();
        $responseData->properties = [$this->createTestPropertyOne(), $this->createTestPropertyTwo()];

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $responseData));

        //Act
        $results = $addressFinderClient->findPropertiesByPostCode($this->createConfiguration(), $this->validPostcode);

        //Assert
        $this->assertSame(count($results), count($expectedResult));

        for ($index = 0; $index < (count($results)); ++$index) {
            $this->assertModelsAreSame($results[$index], $expectedResult[$index]);
        }
    }

    public function testFindPropertiesByPostCodeExpectingEmptyResponse()
    {
        //Arrange
        $expectedResult = [];

        $responseData = new ArrayOfTestAddress();
        $responseData->properties = [];

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $responseData));

        //Act
        $results = $addressFinderClient->findPropertiesByPostCode($this->createConfiguration(), $this->validPostcode);

        //Assert
        $this->assertSame($results, $expectedResult);
    }

    public function testFindPropertiesByPostCodeExpectingExceptionToBeThrown()
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $responseData = new ArrayOfTestAddress();
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $responseData));

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->findPropertiesByPostCode($this->createConfiguration(), $this->validPostcode);
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            $this->assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    public function testGetPropertyByIdentifierExpectingValidResponse()
    {
        //Arrange
        $expectedResult = $this->createExpectedPropertyResponseOne();

        $responseData = $this->createTestPropertyOne();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $responseData));

        //Act
        $result = $addressFinderClient->getPropertyByIdentifier($this->createConfiguration(), $this->validIdentifier);

        //Assert
        $this->assertModelsAreSame($result, $expectedResult);
    }

    public function testGetPropertyByIdentifierPostCodeExpectingExceptionToBeThrown()
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $responseResult = new ArrayOfTestAddress();
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $responseResult));

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->getPropertyByIdentifier($this->createConfiguration(), 'Invalid ID');
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            $this->assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    public function testFindStreetsByTermExpectingValidResponse()
    {
        //Arrange
        $expectedResult = [$this->createExpectedStreetResponseOne(), $this->createExpectedStreetResponseTwo()];

        $responseData = new ArrayOfTestAddress();
        $responseData->properties = [$this->createTestStreetOne(), $this->createTestStreetTwo()];

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $responseData));

        //Act
        $result = $addressFinderClient->findStreetsByTerm($this->createConfiguration(), $this->validTerm);

        //Assert
        $this->assertSame(count($result), count($expectedResult));
        for ($index = 0; $index < count($result); ++$index) {
            $this->assertModelsAreSame($result[$index], $expectedResult[$index]);
        }
    }

    public function testFindStreetsByTermExpectingEmptyResponse()
    {
        //Arrange
        $expectedResult = [];

        $responseData = new ArrayOfTestAddress();
        $responseData->properties = [];

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $responseData));

        //Act
        $results = $addressFinderClient->findStreetsByTerm($this->createConfiguration(), $this->validPostcode);

        //Assert
        $this->assertSame($results, $expectedResult);
    }

    public function testFindStreetsByTermExpectingExpectingExceptionToBeThrown()
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $responseResult = new ArrayOfTestAddress();
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $responseResult));

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->findStreetsByTerm($this->createConfiguration(), 'Invalid ID');
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            $this->assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    public function testGetStreetByIdentifierExpectingValidResponse()
    {
        //Arrange
        $expectedResult = $this->createExpectedStreetResponseOne();

        $responseData = $this->createTestStreetOne();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $responseData));

        //Act
        $result = $addressFinderClient->getStreetByIdentifier($this->createConfiguration(), $this->validIdentifier);

        //Assert
        $this->assertModelsAreSame($result, $expectedResult);
    }

    public function testGetStreetByIdentifierExpectingExceptionToBeThrown()
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $responseResult = new ArrayOfTestAddress();
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $responseResult));

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->findStreetsByTerm($this->createConfiguration(), 'Invalid ID');
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            $this->assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    private function createTestPropertyOne()
    {
        $testAddress = new TestAddressModel();
        $testAddress->uprn = '10001228376';
        $testAddress->usrn = '2802454';
        $testAddress->paon = '1 UNIVERSE HOUSE';
        $testAddress->street = 'MERUS COURT';
        $testAddress->locality = 'MERIDIAN BUSINESS PARK';
        $testAddress->town = 'BRAUNSTONE TOWN';
        $testAddress->postCode = 'LE19 1RJ';
        $testAddress->easting = '454801';
        $testAddress->northing = '302081';

        return $testAddress;
    }

    private function createTestPropertyTwo()
    {
        $testAddress = new TestAddressModel();
        $testAddress->uprn = '45671258378';
        $testAddress->usrn = '2935454';
        $testAddress->paon = '2 UNIVERSE HOUSE';
        $testAddress->street = 'MERUS COURT';
        $testAddress->locality = 'MERIDIAN BUSINESS PARK';
        $testAddress->town = 'BRAUNSTONE TOWN';
        $testAddress->postCode = 'LE19 1RJ';
        $testAddress->easting = '454801';
        $testAddress->northing = '302081';

        return $testAddress;
    }

    private function createTestStreetOne()
    {
        $testAddress = new TestAddressModel();
        $testAddress->usrn = '2802454';
        $testAddress->street = 'MERUS COURT';
        $testAddress->locality = 'MERIDIAN BUSINESS PARK';
        $testAddress->town = 'BRAUNSTONE TOWN';

        return $testAddress;
    }

    private function createTestStreetTwo()
    {
        $testAddress = new TestAddressModel();
        $testAddress->usrn = '4536431';
        $testAddress->street = 'MERUS COURT';
        $testAddress->locality = 'MERIDIAN BUSINESS PARK';
        $testAddress->town = 'BRAUNSTONE TOWN';

        return $testAddress;
    }

    private function createExpectedPropertyResponseOne()
    {
        $address = new AddressModel();
        $address->setUprn('10001228376');
        $address->setUsrn('2802454');
        $address->setPaon('1 UNIVERSE HOUSE');
        $address->setStreet('MERUS COURT');
        $address->setLocality('MERIDIAN BUSINESS PARK');
        $address->setTown('BRAUNSTONE TOWN');
        $address->setPostCode('LE19 1RJ');
        $address->setEasting('454801');
        $address->setNorthing('302081');

        return $address;
    }

    private function createExpectedPropertyResponseTwo()
    {
        $address = new AddressModel();

        $address->setUprn('45671258378');
        $address->setUsrn('2935454');
        $address->setPaon('2 UNIVERSE HOUSE');
        $address->setStreet('MERUS COURT');
        $address->setLocality('MERIDIAN BUSINESS PARK');
        $address->setTown('BRAUNSTONE TOWN');
        $address->setPostCode('LE19 1RJ');
        $address->setEasting('454801');
        $address->setNorthing('302081');

        return $address;
    }

    private function createExpectedStreetResponseOne()
    {
        $address = new AddressModel();

        $address->setUsrn('2802454');
        $address->setStreet('MERUS COURT');
        $address->setLocality('MERIDIAN BUSINESS PARK');
        $address->setTown('BRAUNSTONE TOWN');

        return $address;
    }

    private function createExpectedStreetResponseTwo()
    {
        $address = new AddressModel();

        $address->setUsrn('4536431');
        $address->setStreet('MERUS COURT');
        $address->setLocality('MERIDIAN BUSINESS PARK');
        $address->setTown('BRAUNSTONE TOWN');

        return $address;
    }

    private function createClient($statusCode, $testRequestResult)
    {
        $response = new Response($statusCode, [], json_encode($testRequestResult));
        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return $client;
    }

    private function createConfiguration()
    {
        $configuration = new AddressFinderClientConfigurationModel();

        $configuration->setBaseUri('http://localhost:8000');
        $configuration->setApiKey('Xc31982x53LP98Fsce');
        $configuration->setStatusPath('/status');
        $configuration->setPropertyLookupSearchPath('/property/search/{postcode}');
        $configuration->setPropertyLookupFetchPath('/property/fetch/{identifier}');
        $configuration->setStreetLookupSearchPath('/street/search/{term}');
        $configuration->setStreetLookupFetchPath('/street/fetch/{identifier}');

        return $configuration;
    }

    private function assertModelsAreSame($result, $expectedResult)
    {
        $this->assertSame($result->getUprn(), $expectedResult->getUprn());
        $this->assertSame($result->getUsrn(), $expectedResult->getUsrn());
        $this->assertSame($result->getPaon(), $expectedResult->getPaon());
        $this->assertSame($result->getStreet(), $expectedResult->getStreet());
        $this->assertSame($result->getLocality(), $expectedResult->getLocality());
        $this->assertSame($result->getTown(), $expectedResult->getTown());
        $this->assertSame($result->getPostCode(), $expectedResult->getPostCode());
        $this->assertSame($result->getEasting(), $expectedResult->getEasting());
        $this->assertSame($result->getNorthing(), $expectedResult->getNorthing());
    }
}
