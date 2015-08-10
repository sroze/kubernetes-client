<?php

namespace Kubernetes\Client\Adapter\Http;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\ServerError;
use Kubernetes\Client\Model\Status;
use Symfony\Component\Serializer\SerializerInterface;

class HttpConnector
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @param HttpClient          $httpClient
     * @param SerializerInterface $serializer
     */
    public function __construct(HttpClient $httpClient, SerializerInterface $serializer)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
    }

    /**
     * @param string $path
     * @param array  $options
     *
     * @return mixed
     */
    public function get($path, array $options = [])
    {
        return $this->request('get', $path, null, $options);
    }

    /**
     * @param string $path
     * @param object $body
     * @param array  $options
     *
     * @return object
     *
     * @throws ServerError
     */
    public function post($path, $body, array $options = [])
    {
        return $this->request('post', $path, $body, $options);
    }

    /**
     * @param string $path
     * @param object $body
     * @param array  $options
     *
     * @return object
     *
     * @throws ServerError
     */
    public function delete($path, $body, array $options = [])
    {
        return $this->request('delete', $path, $body, $options);
    }

    /**
     * @param string $path
     * @param object $body
     * @param array  $options
     *
     * @return object
     *
     * @throws ServerError
     */
    public function patch($path, $body, array $options = [])
    {
        $options = array_merge_recursive([
            'headers' => [
                'Content-Type' => 'application/strategic-merge-patch+json',
            ],
        ], $options);

        return $this->request('patch', $path, $body, $options);
    }

    /**
     * @param string $path
     * @param object $body
     * @param array  $options
     *
     * @return object
     *
     * @throws ServerError
     */
    public function put($path, $body, array $options = [])
    {
        return $this->request('put', $path, $body, $options);
    }

    /**
     * @param string $method
     * @param string $path
     * @param object $body
     * @param array  $options
     *
     * @return object
     *
     * @throws ServerError
     */
    private function request($method, $path, $body, array $options)
    {
        $body = $this->serializeBody($body, $options);

        try {
            $responseContents = $this->httpClient->request($method, $path, $body, $options);
            $response = $this->getResponse($responseContents, $options);
        } catch (ConnectException $e) {
            throw new ServerError(new Status(Status::FAILURE, $e->getMessage()));
        } catch (RequestException $e) {
            if ($response = $e->getResponse()) {
                throw $this->createRequestException($e);
            }

            throw new ServerError(new Status(Status::UNKNOWN, 'No response from server'));
        }

        return $response;
    }

    /**
     * @param RequestException $e
     *
     * @return ClientError|ServerError
     */
    private function createRequestException(RequestException $e)
    {
        $response = $e->getResponse();
        $responseBody = $response->getBody()->getContents();

        try {
            $status = $this->serializer->deserialize($responseBody, Status::class, 'json');
        } catch (\RuntimeException $serializerException) {
            $status = new Status(Status::FAILURE, $responseBody);
        }

        $exceptionClass = $e instanceof ClientException ? ClientError::class : ServerError::class;

        return new $exceptionClass($status, $e->getRequest());
    }

    /**
     * @param string $response
     * @param array  $options
     *
     * @return object
     */
    private function getResponse($response, array $options)
    {
        if (is_string($response) && array_key_exists('class', $options)) {
            $response = $this->serializer->deserialize($response, $options['class'], 'json');
        }

        return $response;
    }

    /**
     * @param mixed $body
     *
     * @return string
     */
    public function serializeBody($body, array $options)
    {
        $context = [];
        if (array_key_exists('groups', $options)) {
            $context['groups'] = $options['groups'];
        }

        return $this->serializer->serialize($body, 'json', $context);
    }
}
