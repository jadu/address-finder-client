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
use Mockery;
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

    public function testFetchAddressFinderClientConfiguration()
    {
        //Arrange
        $addressFinderClient = new AddressFinderClient($this->createClient(200, $this->createMockAddressFinderClientConfigurationModel()), $this->createEmptyCache());
        $expectedResponse = $this->createConfiguration();
        //Act
        $result = $addressFinderClient->fetchAddressFinderClientConfiguration('http://localhost:8000/getaddressconfiguration');

        //Assert
        $this->assertSame($result->getBaseUri(), $expectedResponse->getBaseUri());
        $this->assertSame($result->getStatusPath(), $expectedResponse->getStatusPath());
        $this->assertSame($result->getPropertyLookupSearchPath(), $expectedResponse->getPropertyLookupSearchPath());
        $this->assertSame($result->getPropertyLookupFetchPath(), $expectedResponse->getPropertyLookupFetchPath());
        $this->assertSame($result->getStreetLookupSearchPath(), $expectedResponse->getStreetLookupSearchPath());
        $this->assertSame($result->getStreetLookupFetchPath(), $expectedResponse->getStreetLookupFetchPath());
    }

    public function testFetchStatusByPostCodeExpectingValidResponse()
    {
        //Arrange
        $addressFinderClient = new AddressFinderClient($this->createClient(200, null), $this->createEmptyCache());

        //Act
        $result = $addressFinderClient->fetchStatus($this->createConfiguration());

        //Assert
        $this->assertSame($result, true);
    }

    public function testFetchStatusExpectingExceptionToBeThrown()
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, null), $this->createEmptyCache());

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->fetchStatus($this->createConfiguration());
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            $this->assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    public function testSearchPropertiesByPostCodeExpectingValidResponse()
    {
        //Arrange
        $expectedResult = [$this->createExpectedPropertyResponseOne(), $this->createExpectedPropertyResponseTwo()];

        $mockResponseData = $this->createMockFindPropertiesByPostCodeResponse();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData), $this->createEmptyCache());

        //Act
        $results = $addressFinderClient->searchPropertiesByPostCode($this->createConfiguration(), $this->validPostcode);

        //Assert
        $this->assertSame(count($results), count($expectedResult));

        for ($index = 0; $index < (count($results)); ++$index) {
            $this->assertModelsAreSame($results[$index], $expectedResult[$index]);
        }
    }

    public function testSearchPropertiesByPostCodeExpectingEmptyResponse()
    {
        //Arrange
        $expectedResult = [];

        $mockResponseData = $this->createMockFindPropertiesByPostCodeEmptyResponse();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData), $this->createEmptyCache());

        //Act
        $results = $addressFinderClient->searchPropertiesByPostCode($this->createConfiguration(), $this->validPostcode);

        //Assert
        $this->assertSame($results, $expectedResult);
    }

    public function testSearchPropertiesByPostCodeExpectingExceptionToBeThrown()
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];
        $mockResponseData = $this->createMockFindPropertiesByPostCodeResponse();

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $mockResponseData), $this->createEmptyCache());

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->searchPropertiesByPostCode($this->createConfiguration(), $this->validPostcode);
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            $this->assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    public function testFetchPropertyByIdentifierExpectingValidResponse()
    {
        //Arrange
        $expectedResult = $this->createExpectedPropertyResponseOne();

        $mockResponseData = $this->createMockGetPropertyByIdentifierResponse();
        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData), $this->createEmptyCache());

        //Act
        $result = $addressFinderClient->fetchPropertyByIdentifier($this->createConfiguration(), $this->validIdentifier);

        //Assert
        $this->assertModelsAreSame($result, $expectedResult);
    }

    public function testFetchPropertyByIdentifierPostCodeExpectingExceptionToBeThrown()
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];
        $mockResponseData = $this->createMockGetPropertyByIdentifierResponse();

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $mockResponseData), $this->createEmptyCache());

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->fetchPropertyByIdentifier($this->createConfiguration(), 'Invalid ID');
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            $this->assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    public function testSearchStreetsByTermExpectingValidResponse()
    {
        //Arrange
        $expectedResult = [$this->createExpectedStreetResponseOne(), $this->createExpectedStreetResponseTwo()];

        $mockResponseData = $this->createMockFindStreetsByTermResponse();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData), $this->createEmptyCache());

        //Act
        $result = $addressFinderClient->searchStreetsByTerm($this->createConfiguration(), $this->validTerm);

        //Assert
        $this->assertSame(count($result), count($expectedResult));
        for ($index = 0; $index < count($result); ++$index) {
            $this->assertModelsAreSame($result[$index], $expectedResult[$index]);
        }
    }

    public function testSearchStreetsByTermExpectingEmptyResponse()
    {
        //Arrange
        $expectedResult = [];
        $mockResponseData = $this->createMockFindStreetsByTermEmptyResponse();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData), $this->createEmptyCache());

        //Act
        $results = $addressFinderClient->searchStreetsByTerm($this->createConfiguration(), $this->validPostcode);

        //Assert
        $this->assertSame($results, $expectedResult);
    }

    public function testSearchStreetsByTermExpectingExpectingExceptionToBeThrown()
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];
        $mockResponseData = $this->createMockFindStreetsByTermResponse();

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $mockResponseData), $this->createEmptyCache());

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->searchStreetsByTerm($this->createConfiguration(), 'Invalid ID');
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            $this->assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    public function testFetchStreetByIdentifierExpectingValidResponse()
    {
        //Arrange
        $expectedResult = $this->createExpectedStreetResponseOne();

        $mockResponseData = $this->createMockGetStreetByIdentifierResponse();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData), $this->createEmptyCache());

        //Act
        $result = $addressFinderClient->fetchStreetByIdentifier($this->createConfiguration(), $this->validIdentifier);

        //Assert
        $this->assertModelsAreSame($result, $expectedResult);
    }

    public function testFetchStreetByIdentifierExpectingExceptionToBeThrown()
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];
        $mockResponseData = $this->createMockGetStreetByIdentifierResponse();

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $mockResponseData), $this->createEmptyCache());

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->fetchStreetByIdentifier($this->createConfiguration(), 'Invalid ID');
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            $this->assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    private function createMockFindPropertiesByPostCodeResponse()
    {
        return '{"properties":[{"identifier":"10001228376","paon":"1 UNIVERSE HOUSE","saon":null,"street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","post_town":null,"post_code":"LE19 1RJ","easting":"454801","northing":"302081","uprn":"10001228376","usrn":"2802454","logical_status":"1"},{"identifier":"45671258378","paon":"2 UNIVERSE HOUSE","saon":null,"street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","post_town":null,"post_code":"LE19 1RJ","easting":"454801","northing":"302081","uprn":"45671258378","usrn":"2935454","logical_status":"2"}]}';
    }

    private function createMockGetPropertyByIdentifierResponse()
    {
        return '{"property":{"identifier":"10001228376","paon":"1 UNIVERSE HOUSE","saon":null,"street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","post_town":null,"post_code":"LE19 1RJ","easting":"454801","northing":"302081","uprn":"10001228376","usrn":"2802454","logical_status":"1"}}';
    }

    private function createMockFindStreetsByTermResponse()
    {
        return '{"streets":[{"identifier":"10001228376","street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","usrn":"2802454"},{"identifier":"45671258378","street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","usrn":"3937452"}]}';
    }

    private function createMockGetStreetByIdentifierResponse()
    {
        return '{"street":{"identifier":"10001228376","street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","usrn":"2802454"}}';
    }

    private function createMockFindPropertiesByPostCodeEmptyResponse()
    {
        return '{"properties":[]}';
    }

    private function createMockFindStreetsByTermEmptyResponse()
    {
        return '{"streets":[]}';
    }

    private function createMockAddressFinderClientConfigurationModel()
    {
        return '{"base_uri":"http://localhost:8000","status_path":"/status","property_lookup":{"search_path":"/property/search/{postcode}","fetch_path":"/property/fetch/{identifier}"},"street_lookup":{"search_path":"/street/search/{term}","fetch_path":"/street/fetch/{identifier}"}}';
    }

    private function createExpectedPropertyResponseOne()
    {
        $address = new AddressModel();

        $address->setExternalReference('10001228376');
        $address->setUprn('10001228376');
        $address->setUsrn('2802454');
        $address->setPaon('1 UNIVERSE HOUSE');
        $address->setStreet('MERUS COURT');
        $address->setLocality('MERIDIAN BUSINESS PARK');
        $address->setTown('BRAUNSTONE TOWN');
        $address->setPostCode('LE19 1RJ');
        $address->setEasting('454801');
        $address->setNorthing('302081');
        $address->setLogicalStatus('1');
        $address->setType($address::TYPE_PROPERTY);

        return $address;
    }

    private function createExpectedPropertyResponseTwo()
    {
        $address = new AddressModel();

        $address->setExternalReference('45671258378');
        $address->setUprn('45671258378');
        $address->setUsrn('2935454');
        $address->setPaon('2 UNIVERSE HOUSE');
        $address->setStreet('MERUS COURT');
        $address->setLocality('MERIDIAN BUSINESS PARK');
        $address->setTown('BRAUNSTONE TOWN');
        $address->setPostCode('LE19 1RJ');
        $address->setEasting('454801');
        $address->setNorthing('302081');
        $address->setLogicalStatus('2');
        $address->setType($address::TYPE_PROPERTY);

        return $address;
    }

    private function createExpectedStreetResponseOne()
    {
        $address = new AddressModel();

        $address->setExternalReference('10001228376');
        $address->setUsrn('2802454');
        $address->setStreet('MERUS COURT');
        $address->setTown('BRAUNSTONE TOWN');
        $address->setLocality('MERIDIAN BUSINESS PARK');
        $address->setType($address::TYPE_STREET);

        return $address;
    }

    private function createExpectedStreetResponseTwo()
    {
        $address = new AddressModel();

        $address->setExternalReference('45671258378');
        $address->setUsrn('3937452');
        $address->setStreet('MERUS COURT');
        $address->setTown('BRAUNSTONE TOWN');
        $address->setLocality('MERIDIAN BUSINESS PARK');
        $address->setType($address::TYPE_STREET);

        return $address;
    }

    private function createClient($statusCode, $testRequestResult)
    {
        $response = new Response($statusCode, [], $testRequestResult);
        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return $client;
    }

    private function createConfiguration()
    {
        $configuration = new AddressFinderClientConfigurationModel();

        $configuration->setBaseUri('http://localhost:8000');
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
        $this->assertSame($result->getExternalReference(), $expectedResult->getExternalReference());
        $this->assertSame($result->getLogicalStatus(), $expectedResult->getLogicalStatus());
        $this->assertSame($result->getType(), $expectedResult->getType());
    }

    private function createEmptyCache()
    {
        $emptyCacheItem = Mockery::mock('Stash\Item');
        $emptyCacheItem->shouldReceive('get')->andReturn(false);
        $emptyCacheItem->shouldReceive('isMiss')->andReturn(true);
        $emptyCacheItem->shouldReceive('set');

        $mockStash = Mockery::mock('Stash\Pool');
        $mockStash->shouldReceive('getItem')->andReturn($emptyCacheItem);

        return $mockStash;
    }
}
