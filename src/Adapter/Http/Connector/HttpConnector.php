<?php

namespace Kubernetes\Client\Adapter\Http\Connector;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Message\ResponseInterface;
use Kubernetes\Client\Adapter\Http\Connector;

class HttpConnector implements Connector
{
    /**
     * @var string
     */
    private $baseUrl;
    /**
     * @var string
     */
    private $version;
    /**
     * @var GuzzleClient
     */
    private $client;

    public function __construct($baseUrl, $version)
    {
        $this->baseUrl = $baseUrl;
        $this->version = $version;
    }

    public function get($path, array $options = [])
    {
        return $this->getResponse($this->getGuzzle()->get($this->getUrlForPath($path)));
    }

    public function post($path, $body, array $options = [])
    {
        return $this->request('post', $path, $body, $options);
    }

    public function delete($path, $body, array $options = [])
    {
        return $this->request('delete', $path, $body, $options);
    }

    public function patch($path, $body, array $options = [])
    {
        $options = array_merge_recursive([
            'headers' => [
                'Content-Type' => 'application/strategic-merge-patch+json',
            ],
        ], $options);

        return $this->request('patch', $path, $body, $options);
    }

    public function put($path, $body, array $options = [])
    {
        return $this->request('put', $path, $body, $options);
    }

    private function request($method, $path, $body, array $options)
    {
        $options = $this->resolveOptions($body, $options);
        $path = $this->getUrlForPath($path);
        $guzzleResponse = $this->getGuzzle()->$method($path, $options);
        $response = $this->getResponse($guzzleResponse);

        return $response;
    }

    private function resolveOptions($body, array $options = [])
    {
        $options = [
            'headers' => array_key_exists('headers', $options) ? $options['headers'] : [],
        ];
        if (is_array($body)) {
            $options['json'] = $body;
        } elseif (is_string($body)) {
            $options['body'] = $body;
        } elseif ($body !== null) {
            throw new \RuntimeException('Not supported request body type');
        }

        return $options;
    }

    private function getResponse(ResponseInterface $response)
    {
        if ($body = $response->getBody()) {
            return $body->getContents();
        }

        throw new \RuntimeException('Unable to get response');
    }

    private function getUrlForPath($path)
    {
        return sprintf('%s/api/%s%s', $this->baseUrl, $this->version, $path);
    }

    private function getGuzzle()
    {
        if (null === $this->client) {
            $this->client = new GuzzleClient([
                'defaults' => [
                    'verify' => false,
                    'timeout' => 5,
                    'connect_timeout' => 1.5,
                ],
            ]);
        }

        return $this->client;
    }
}
