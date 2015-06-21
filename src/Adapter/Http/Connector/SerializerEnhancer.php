<?php

namespace Kubernetes\Client\Adapter\Http\Connector;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Stream\Stream;
use Kubernetes\Client\Adapter\Http\Connector;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\ServerError;
use Kubernetes\Client\Model\Status;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerEnhancer implements Connector
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @param Connector           $connector
     * @param SerializerInterface $serializer
     */
    public function __construct(Connector $connector, SerializerInterface $serializer)
    {
        $this->connector = $connector;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function get($path, array $options = [])
    {
        return $this->delegateRequest('get', $path, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function post($path, $body, array $options = [])
    {
        return $this->delegateRequestWithBody('post', $path, $body, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function put($path, $body, array $options = [])
    {
        return $this->delegateRequestWithBody('put', $path, $body, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function patch($path, $body, array $options = [])
    {
        return $this->delegateRequestWithBody('patch', $path, $body, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path, $body, array $options = [])
    {
        return $this->delegateRequestWithBody('delete', $path, $body, $options);
    }

    /**
     * @param $method
     * @param $path
     * @param $options
     *
     * @return object
     *
     * @throws ServerError
     */
    private function delegateRequest($method, $path, $options)
    {
        return $this->delegate($method, [$path, $options], $options);
    }

    /**
     * @param string $method
     * @param string $path
     * @param mixed  $body
     * @param array  $options
     *
     * @throws ServerError
     * @throws ClientError
     *
     * @return object
     */
    private function delegateRequestWithBody($method, $path, $body, array $options)
    {
        if (is_object($body)) {
            $body = $this->serializeBody($body, $options);
        }

        return $this->delegate($method, [$path, $body, $options], $options);
    }

    /**
     * @param $method
     * @param array $arguments
     * @param array $options
     *
     * @throws ServerError
     * @throws ClientError
     *
     * @return object
     */
    private function delegate($method, array $arguments, array $options)
    {
        try {
            $response = call_user_func_array([$this->connector, $method], $arguments);

            return $this->getResponse($response, $options);
        } catch (ConnectException $e) {
            throw new ServerError(new Status(Status::FAILURE, $e->getMessage()));
        } catch (BadResponseException $e) {
            if ($response = $e->getResponse()) {
                $responseBody = $response->getBody()->getContents();

                $requestBody = null;

                if ($request = $e->getRequest()) {
                    $requestBody = $request->getBody() ? $request->getBody()->getContents() : null;

                    if (!$requestBody && count($arguments) === 3 && is_string($arguments[1])) {
                        $request->setBody(Stream::factory($arguments[1]));
                    }
                }

                $status = $this->serializer->deserialize($responseBody, Status::class, 'json');
                $exceptionClass = $e instanceof ClientException ? ClientError::class : ServerError::class;

                throw new $exceptionClass($status, $request);
            }

            throw new ServerError(new Status(Status::UNKNOWN, 'No response from server'));
        }
    }

    /**
     * @param mixed $response
     * @param array $options
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
