<?php

namespace Kubernetes\Client\Adapter\Http;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\ServerError;
use Kubernetes\Client\Model\Status;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param HttpClient          $httpClient
     * @param SerializerInterface $serializer
     * @param ?LoggerInterface    $logger
     */
    public function __construct(HttpClient $httpClient, SerializerInterface $serializer, LoggerInterface $logger = null)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->logger = $logger ?: new NullLogger();
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
     * @param array  $options
     *
     * @return PromiseInterface
     */
    public function asyncGet($path, array $options = [])
    {
        return $this->asyncRequest('get', $path, null, $options);
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
                'Content-Type' => 'application/merge-patch+json',
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
        } catch (ServerException $e) {
            throw new ServerError(new Status(Status::FAILURE, $e->getMessage()));
        } catch (RequestException $e) {
            if ($response = $e->getResponse()) {
                throw $this->createRequestException($e);
            }
            $this->logger->warning('Problem communicating with a Kubernetes cluster', ['exception' => $e]);
            throw new ServerError(new Status(Status::UNKNOWN, 'No response from server'));
        }

        return $response;
    }

    /**
     * @param string $method
     * @param string $path
     * @param string $body
     * @param array $options
     *
     * @return PromiseInterface
     *
     * @throws ServerError
     */
    private function asyncRequest($method, $path, $body, array $options)
    {
        $self = $this;
        $body = $this->serializeBody($body, $options);

        return $this->httpClient->asyncRequest($method, $path, $body, $options)->then(
            function ($responseContents) use ($self, $options) {
                return $self->getResponse($responseContents, $options);
            },
            function (\Exception $e) use ($self) {
                if ($e instanceof ServerException) {
                    throw new ServerError(new Status(Status::FAILURE, $e->getMessage()));
                }
                if ($e instanceof RequestException) {
                    if ($response = $e->getResponse()) {
                        throw $self->createRequestException($e);
                    }
                    $self->logger->warning('Problem communicating with a Kubernetes cluster', ['exception' => $e]);
                    throw new ServerError(new Status(Status::UNKNOWN, 'No response from server'));
                }
            }
        );
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
