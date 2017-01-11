<?php

namespace Kubernetes\Client\Adapter\Http;

use Kubernetes\Client\Exception\ServerError;
use Symfony\Component\Serializer\SerializerInterface;

interface HttpConnectorInterface
{
    /**
     * @param HttpClient          $httpClient
     * @param SerializerInterface $serializer
     */
    public function __construct(HttpClient $httpClient, SerializerInterface $serializer);

    /**
     * @param string $path
     * @param array  $options
     *
     * @return mixed
     */
    public function get($path, array $options = []);

    /**
     * @param string $path
     * @param object $body
     * @param array  $options
     *
     * @return object
     *
     * @throws ServerError
     */
    public function post($path, $body, array $options = []);

    /**
     * @param string $path
     * @param object $body
     * @param array  $options
     *
     * @return object
     *
     * @throws ServerError
     */
    public function delete($path, $body, array $options = []);

    /**
     * @param string $path
     * @param object $body
     * @param array  $options
     *
     * @return object
     *
     * @throws ServerError
     */
    public function patch($path, $body, array $options = []);

    /**
     * @param string $path
     * @param object $body
     * @param array  $options
     *
     * @return object
     *
     * @throws ServerError
     */
    public function put($path, $body, array $options = []);

    /**
     * @param mixed $body
     *
     * @return string
     */
    public function serializeBody($body, array $options);
}
