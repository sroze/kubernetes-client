<?php

namespace Kubernetes\Client\Adapter\Http;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpClient implements HttpClient
{
    /**
     * @var ClientInterface
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
     * @param ClientInterface $guzzleClient
     * @param string          $baseUrl
     * @param string          $version
     */
    public function __construct(ClientInterface $guzzleClient, $baseUrl, $version)
    {
        $this->guzzleClient = $guzzleClient;
        $this->baseUrl = $baseUrl;
        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $path, $body = null, array $options = [])
    {
        return $this->returnBodyContents(
            $this->guzzleClient->request(
                $method,
                $this->getUrlForPath($path),
                $this->prepareOptions($body, $options)
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function asyncRequest($method, $path, $body = null, array $options = [])
    {
        return $this->guzzleClient->requestAsync($method, $this->getUrlForPath($path), $this->prepareOptions($body, $options))->then(function (ResponseInterface $response) {
            return $this->returnBodyContents($response);
        });
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function getUrlForPath($path)
    {
        if (substr($path, 0, 4) != '/api') {
            $path = sprintf('/api/%s%s', $this->version, $path);
        }

        return sprintf('%s%s', $this->baseUrl, $path);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return string|null
     */
    private function returnBodyContents(ResponseInterface $response)
    {
        if ($body = $response->getBody()) {
            if ($body->isSeekable()) {
                $body->seek(0);
            }

            return $body->getContents();
        }

        return null;
    }

    /**
     * Prepare Guzzle options.
     *
     * @param string $body
     * @param array  $options
     *
     * @return array
     */
    private function prepareOptions($body, $options)
    {
        $defaults = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        if ($body !== null) {
            $defaults['body'] = $body;
        }

        $options = array_intersect_key($options, [
            'headers' => null, 'body' => null,
        ]);

        return array_replace_recursive($defaults, $options);
    }
}
