<?php

namespace Jadu\Tests\AddressFinderClient;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jadu\AddressFinderClient\AddressFinderClient;
use Jadu\AddressFinderClient\Exception\AddressFinderHttpResponseException;
use Jadu\AddressFinderClient\Model\Address as AddressModel;
use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;
use PHPUnit\Framework\TestCase;

/**
 * Class AddressFinderClientTest.
 *
 * @author Jadu Ltd.
 */
class AddressFinderClientTest extends TestCase
{
    private $validPostcode = 'LE19%201RJ';
    private $validIdentifier = '10001228376';
    private $validTerm = 'merus%20court';

    public function testFetchAddressFinderClientConfiguration(): void
    {
        //Arrange
        $addressFinderClient = new AddressFinderClient(
            $this->createClient(200, $this->createMockAddressFinderClientConfigurationModel())
        );
        $expectedResponse = $this->createConfiguration();
        //Act
        $result = $addressFinderClient->fetchAddressFinderClientConfiguration(
            'http://localhost:8000/getaddressconfiguration',
            86400
        );

        //Assert
        static::assertSame($result->getBaseUri(), $expectedResponse->getBaseUri());
        static::assertSame($result->getStatusPath(), $expectedResponse->getStatusPath());
        static::assertSame($result->getPropertyLookupSearchPath(), $expectedResponse->getPropertyLookupSearchPath());
        static::assertSame($result->getPropertyLookupFetchPath(), $expectedResponse->getPropertyLookupFetchPath());
        static::assertSame($result->getStreetLookupSearchPath(), $expectedResponse->getStreetLookupSearchPath());
        static::assertSame($result->getStreetLookupFetchPath(), $expectedResponse->getStreetLookupFetchPath());
    }

    public function testFetchStatusByPostCodeExpectingValidResponse(): void
    {
        //Arrange
        $addressFinderClient = new AddressFinderClient($this->createClient(200, null));

        //Act
        $result = $addressFinderClient->fetchStatus($this->createConfiguration());

        //Assert
        static::assertSame($result, true);
    }

    public function testFetchStatusExpectingExceptionToBeThrown(): void
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, null));

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->fetchStatus($this->createConfiguration());
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            static::assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    public function testSearchPropertiesByPostCodeExpectingValidResponse(): void
    {
        //Arrange
        $expectedResult = [$this->createExpectedPropertyResponseOne(), $this->createExpectedPropertyResponseTwo()];

        $mockResponseData = $this->createMockFindPropertiesByPostCodeResponse();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData));

        //Act
        $results = $addressFinderClient->searchPropertiesByPostCode($this->createConfiguration(), $this->validPostcode);

        //Assert
        static::assertSame(count($results), count($expectedResult));

        for ($index = 0; $index < (count($results)); ++$index) {
            $this->assertModelsAreSame($results[$index], $expectedResult[$index]);
        }
    }

    public function testSearchPropertiesByPostCodeExpectingEmptyResponse(): void
    {
        //Arrange
        $expectedResult = [];

        $mockResponseData = $this->createMockFindPropertiesByPostCodeEmptyResponse();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData));

        //Act
        $results = $addressFinderClient->searchPropertiesByPostCode($this->createConfiguration(), $this->validPostcode);

        //Assert
        static::assertSame($results, $expectedResult);
    }

    public function testSearchPropertiesByPostCodeExpectingExceptionToBeThrown(): void
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];
        $mockResponseData = $this->createMockFindPropertiesByPostCodeResponse();

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $mockResponseData));

            $caughtException = null;

            //Act
            try {
                $addressFinderClient->searchPropertiesByPostCode($this->createConfiguration(), $this->validPostcode);
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            static::assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    public function testFetchPropertyByIdentifierExpectingValidResponse(): void
    {
        //Arrange
        $expectedResult = $this->createExpectedPropertyResponseOne();

        $mockResponseData = $this->createMockGetPropertyByIdentifierResponse();
        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData));

        //Act
        $result = $addressFinderClient->fetchPropertyByIdentifier($this->createConfiguration(), $this->validIdentifier);

        //Assert
        $this->assertModelsAreSame($result, $expectedResult);
    }

    public function testFetchPropertyByIdentifierPostCodeExpectingExceptionToBeThrown(): void
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];
        $mockResponseData = $this->createMockGetPropertyByIdentifierResponse();

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $mockResponseData));

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->fetchPropertyByIdentifier($this->createConfiguration(), 'Invalid ID');
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            static::assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    public function testSearchStreetsByTermExpectingValidResponse(): void
    {
        //Arrange
        $expectedResult = [$this->createExpectedStreetResponseOne(), $this->createExpectedStreetResponseTwo()];

        $mockResponseData = $this->createMockFindStreetsByTermResponse();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData));

        //Act
        $result = $addressFinderClient->searchStreetsByTerm($this->createConfiguration(), $this->validTerm);

        //Assert
        static::assertSame(count($result), count($expectedResult));
        $resultCount = count($result);
        for ($index = 0; $index < $resultCount; ++$index) {
            $this->assertModelsAreSame($result[$index], $expectedResult[$index]);
        }
    }

    public function testSearchStreetsByTermExpectingEmptyResponse(): void
    {
        //Arrange
        $expectedResult = [];
        $mockResponseData = $this->createMockFindStreetsByTermEmptyResponse();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData));

        //Act
        $results = $addressFinderClient->searchStreetsByTerm($this->createConfiguration(), $this->validPostcode);

        //Assert
        static::assertSame($results, $expectedResult);
    }

    public function testSearchStreetsByTermExpectingExpectingExceptionToBeThrown(): void
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];
        $mockResponseData = $this->createMockFindStreetsByTermResponse();

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $mockResponseData));

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->searchStreetsByTerm($this->createConfiguration(), 'Invalid ID');
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            static::assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    public function testFetchStreetByIdentifierExpectingValidResponse(): void
    {
        //Arrange
        $expectedResult = $this->createExpectedStreetResponseOne();

        $mockResponseData = $this->createMockGetStreetByIdentifierResponse();

        $addressFinderClient = new AddressFinderClient($this->createClient(200, $mockResponseData));

        //Act
        $result = $addressFinderClient->fetchStreetByIdentifier($this->createConfiguration(), $this->validIdentifier);

        //Assert
        $this->assertModelsAreSame($result, $expectedResult);
    }

    public function testFetchStreetByIdentifierExpectingExceptionToBeThrown(): void
    {
        //Arrange
        $expectedStatusCodes = [202, 300, 400, 401, 404, 500];
        $mockResponseData = $this->createMockGetStreetByIdentifierResponse();

        foreach ($expectedStatusCodes as $expectedStatusCode) {
            $addressFinderClient = new AddressFinderClient($this->createClient($expectedStatusCode, $mockResponseData));

            $caughtException = null;

            //Act
            try {
                $results = $addressFinderClient->fetchStreetByIdentifier($this->createConfiguration(), 'Invalid ID');
            } catch (AddressFinderHttpResponseException $ex) {
                $caughtException = $ex;
            }

            //Assert
            static::assertSame($caughtException->getStatusCode(), $expectedStatusCode);
        }
    }

    private function createMockFindPropertiesByPostCodeResponse(): string
    {
        return '{"properties":[{"identifier":"10001228376","paon":"1 UNIVERSE HOUSE","saon":null,"street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","post_town":null,"post_code":"LE19 1RJ","easting":"454801","northing":"302081","uprn":"10001228376","usrn":"2802454","logical_status":"1"},{"identifier":"45671258378","paon":"2 UNIVERSE HOUSE","saon":null,"street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","post_town":null,"post_code":"LE19 1RJ","easting":"454801","northing":"302081","uprn":"45671258378","usrn":"2935454","logical_status":"2"}]}';
    }

    private function createMockGetPropertyByIdentifierResponse(): string
    {
        return '{"property":{"identifier":"10001228376","paon":"1 UNIVERSE HOUSE","saon":null,"street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","post_town":null,"post_code":"LE19 1RJ","easting":"454801","northing":"302081","uprn":"10001228376","usrn":"2802454","logical_status":"1"}}';
    }

    private function createMockFindStreetsByTermResponse(): string
    {
        return '{"streets":[{"identifier":"10001228376","street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","usrn":"2802454"},{"identifier":"45671258378","street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","usrn":"3937452"}]}';
    }

    private function createMockGetStreetByIdentifierResponse(): string
    {
        return '{"street":{"identifier":"10001228376","street_name":"MERUS COURT","locality":"MERIDIAN BUSINESS PARK","town":"BRAUNSTONE TOWN","usrn":"2802454"}}';
    }

    private function createMockFindPropertiesByPostCodeEmptyResponse(): string
    {
        return '{"properties":[]}';
    }

    private function createMockFindStreetsByTermEmptyResponse(): string
    {
        return '{"streets":[]}';
    }

    private function createMockAddressFinderClientConfigurationModel(): string
    {
        return '{"base_uri":"http://localhost:8000","status_path":"/status","property_lookup":{"search_path":"/property/search/{postcode}","fetch_path":"/property/fetch/{identifier}"},"street_lookup":{"search_path":"/street/search/{term}","fetch_path":"/street/fetch/{identifier}"}}';
    }

    private function createExpectedPropertyResponseOne(): AddressModel
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

    private function createExpectedPropertyResponseTwo(): AddressModel
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

    private function createExpectedStreetResponseOne(): AddressModel
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

    private function createExpectedStreetResponseTwo(): AddressModel
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

    private function createClient($statusCode, $testRequestResult): Client
    {
        $response = new Response($statusCode, [], $testRequestResult);
        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }

    private function createConfiguration(): AddressFinderClientConfigurationModel
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

    private function assertModelsAreSame($result, $expectedResult): void
    {
        static::assertSame($result->getUprn(), $expectedResult->getUprn());
        static::assertSame($result->getUsrn(), $expectedResult->getUsrn());
        static::assertSame($result->getPaon(), $expectedResult->getPaon());
        static::assertSame($result->getStreet(), $expectedResult->getStreet());
        static::assertSame($result->getLocality(), $expectedResult->getLocality());
        static::assertSame($result->getTown(), $expectedResult->getTown());
        static::assertSame($result->getPostCode(), $expectedResult->getPostCode());
        static::assertSame($result->getEasting(), $expectedResult->getEasting());
        static::assertSame($result->getNorthing(), $expectedResult->getNorthing());
        static::assertSame($result->getExternalReference(), $expectedResult->getExternalReference());
        static::assertSame($result->getLogicalStatus(), $expectedResult->getLogicalStatus());
        static::assertSame($result->getType(), $expectedResult->getType());
    }
}
