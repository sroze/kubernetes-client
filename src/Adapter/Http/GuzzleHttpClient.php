<?php

namespace Kubernetes\Client\Adapter\Http;

use GuzzleHttp\Client;

class GuzzleHttpClient implements HttpClient
{
    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $version;

    /**
     * @param Client $guzzleClient
     * @param string $baseUrl
     * @param string $version
     */
    public function __construct(Client $guzzleClient, $baseUrl, $version)
    {
        $this->guzzleClient = $guzzleClient;
        $this->baseUrl = $baseUrl;
        $this->version = $version;
    }

    /**
     * @param string $method
     * @param string $path
     * @param string $body
     * @param array  $options
     *
     * @return string
     */
    public function request($method, $path, $body = null, array $options = [])
    {
        $url = $this->getUrlForPath($path);
        $response = $this->guzzleClient->$method($url, array_merge_recursive([
            'body' => $body,
        ], $options));

        $body = $response->getBody();
        if ($body->isSeekable()) {
            $body->seek(0);
        }

        return $body->getContents();
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function getUrlForPath($path)
    {
        return sprintf('%s/api/%s%s', $this->baseUrl, $this->version, $path);
    }
}
