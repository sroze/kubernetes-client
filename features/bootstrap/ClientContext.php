<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use GuzzleHttp\Client as GuzzleClient;
use JMS\Serializer\SerializerBuilder;
use Kubernetes\Client\Adapter\Http\AuthenticationMiddleware;
use Kubernetes\Client\Adapter\Http\FileHttpClient;
use Kubernetes\Client\Adapter\Http\GuzzleHttpClient;
use Kubernetes\Client\Adapter\Http\HttpAdapter;
use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Client;
use Kubernetes\Client\Adapter\Http\FileResolver;
use Kubernetes\Client\Adapter\Http\HttpRecorderClientDecorator;
use Kubernetes\Client\Serializer\JmsSerializerAdapter;

class ClientContext implements Context, SnippetAcceptingContext
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var bool
     */
    private $integration;

    /**
     * @param string $baseUrl
     * @param string $version
     * @param string $usernameOrToken
     * @param string $password
     */
    public function __construct($baseUrl, $version, $usernameOrToken = null, $password = null, $integration = false, $record = false)
    {
        $serializer = SerializerBuilder::create()
            ->addMetadataDir(__DIR__.'/../../src/Resources/serializer', 'Kubernetes\Client')
            ->build();

        if ($integration) {
            $httpClient = new GuzzleHttpClient(new GuzzleClient([
                'verify' => false,
            ]), $baseUrl, $version);
        } else {
            $httpClient = new FileHttpClient(new FileResolver());
        }

        if ($record) {
            $httpClient = new HttpRecorderClientDecorator(
                $httpClient,
                new FileResolver()
            );
        }

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
        $this->integration = $integration;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return boolean
     */
    public function isIntegration()
    {
        return $this->integration;
    }
}
