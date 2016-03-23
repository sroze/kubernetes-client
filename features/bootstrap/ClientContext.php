<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use GuzzleHttp\Client as GuzzleClient;
use JMS\Serializer\SerializerBuilder;
use Kubernetes\Client\Adapter\Http\AuthenticationMiddleware;
use Kubernetes\Client\Adapter\Http\GuzzleHttpClient;
use Kubernetes\Client\Adapter\Http\HttpAdapter;
use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Client;
use Kubernetes\Client\Serializer\JmsSerializerAdapter;

class ClientContext implements Context, SnippetAcceptingContext
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param string $baseUrl
     * @param string $version
     * @param string $usernameOrToken
     * @param string $password
     */
    public function __construct($baseUrl, $version, $usernameOrToken = null, $password = null)
    {
        $serializer = SerializerBuilder::create()
            ->addMetadataDir(__DIR__.'/../../src/Resources/serializer', 'Kubernetes\Client')
            ->build();

        $httpClient = new GuzzleHttpClient(new GuzzleClient([
            'defaults' => [
                'verify' => false,
            ],
        ]), $baseUrl, $version);

        if ($usernameOrToken !== null) {
            $httpClient = new AuthenticationMiddleware($httpClient, $usernameOrToken, $password);
        }

        $connector = new HttpConnector(
            $httpClient,
            new JmsSerializerAdapter($serializer)
        );

        $this->client = new Client(
            new HttpAdapter(
                $connector
            )
        );
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
