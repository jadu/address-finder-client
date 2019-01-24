<?php

namespace Jadu\Tests\AddressFinderClient;

use Jadu\AddressFinderClient;
use Jadu\AddressFinderClient\Model\AddressFinderClientConfigurationModel;
use Http\Mock\Client;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class AddressFinderClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * PostCode.
     */
    const TEST_POSTCODE = 'LE19 1RJ';

    /**
     * @var AddressFinderClientConfigurationModel
     */
    private $configuration;

    /**
     * @var AddressFinderClient
     */
    private $addressFinderClient;

    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new AddressFinderClientConfigurationModel();

        $this->configuration->baseUri = 'http://localhost:8000';
        $this->configuration->apiKey = 'testkey';
        $this->configuration->statusPath = '/status';
        $this->configuration->propertyLookupSearchPath = '/property/search/{postcode}';
        $this->configuration->propertyLookupFetchPath = '/property/fetch/{identifier}';
        $this->configuration->streetLookupSearchPath = '/street/search/{term}';
        $this->configuration->streetLookupFetchPath = '/street/fetch/{identifier}';

        $this->addressFinderClient = new AddressFinderClient(configuration);
    }

    public function testRequests()
    {
        $response = $this->createMock('Psr\Http\Message\ResponseInterface');

        $results = $this->addressFinderClient->findPropertiesByPostCode(TEST_POSTCODE);
    }
}
