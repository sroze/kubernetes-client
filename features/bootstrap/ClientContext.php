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
     * @param bool $integration
     * @param bool $record
     * @param string|null $caCertificate
     */
    public function __construct($baseUrl, $version, $usernameOrToken = null, $password = null, $integration = false, $record = false, $caCertificate = null)
    {
        $serializer = SerializerBuilder::create()
            ->addMetadataDir(__DIR__.'/../../src/Resources/serializer', 'Kubernetes\Client')
            ->build();

        if ($integration) {
            $httpClient = new GuzzleHttpClient(
                new GuzzleClient([
                    'verify' => false,
                ]),
                $baseUrl,
                $version,
                $caCertificate
            );
        } else {
            $httpClient = new FileHttpClient(new FileResolver());
        }

        if ($record) {
            $httpClient = new HttpRecorderClientDecorator(
                $httpClient,
                new FileResolver()
            );
        }

        if ($password !== null) {
            if ($usernameOrToken == 'cert') {
                $httpClient = new AuthenticationMiddleware($httpClient, AuthenticationMiddleware::CERTIFICATE, $password);
            } else {
                $httpClient = new AuthenticationMiddleware($httpClient, AuthenticationMiddleware::USERNAME_PASSWORD, $usernameOrToken . ':' . $password);
            }
        } elseif ($usernameOrToken !== null) {
            $httpClient = new AuthenticationMiddleware($httpClient, AuthenticationMiddleware::TOKEN, $usernameOrToken);
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
