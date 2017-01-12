<?php
namespace Kubernetes\Client\IntegrationTests;

use GuzzleHttp\Client;
use Kubernetes\Client\Adapter\Http\GuzzleHttpClient;
use PHPUnit\Framework\TestCase;

class GuzzleHttpClientTest extends TestCase
{
    public function testGetRequest()
    {
        // ARRANGE
        $rawGuzzleClient = new Client();
        $guzzleClient = new GuzzleHttpClient($rawGuzzleClient, 'localhost:8089', '1');

        // ACT
        $response = $guzzleClient->request('get', '/path/', '{"x": "1"}', ['headers' => ['Content-Type' => 'application/json']]);

        // ASSERT

        $expectedResults = [
            'uri'          => 'http://localhost:8089/api/1/path/',
            'body'         => '{"x": "1"}',
            'content-type' => 'application/json',
        ];

        $this->assertEquals($expectedResults, json_decode($response, true));
    }
}
