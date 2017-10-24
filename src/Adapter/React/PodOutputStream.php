<?php

namespace Kubernetes\Client\Adapter\React;

use Evenement\EventEmitter;
use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Model\Pod;
use Kubernetes\Client\Model\PodStatusProvider;
use React\EventLoop\LoopInterface;
use React\EventLoop\Timer\TimerInterface;
use React\Stream\ReadableStreamInterface;
use React\Stream\Util;
use React\Stream\WritableStreamInterface;

/**
 * Provide a stream of raw output for the given pod.
 */
class PodOutputStream extends EventEmitter implements ReadableStreamInterface
{
    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var bool
     */
    private $closed = false;

    /**
     * @var HttpConnector
     */
    private $httpConnector;

    /**
     * @var float
     */
    private $pollInterval;

    /**
     * @var TimerInterface
     */
    private $timer;

    /**
     * @var PromiseInterface
     */
    private $httpPromise;

    /**
     * @var Pod
     */
    private $pod;

    /**
     * @var HttpNamespaceClient
     */
    private $namespaceClient;

    /**
     * @var PodStatusProvider
     */
    private $statusProvider;

    public function __construct(
        Pod $pod,
        LoopInterface $loop,
        HttpConnector $httpConnector,
        HttpNamespaceClient $namespaceClient,
        PodStatusProvider $statusProvider,
        $pollInterval = 0.1
    ) {
        $this->loop = $loop;
        $this->httpConnector = $httpConnector;
        $this->pollInterval = $pollInterval;
        $this->pod = $pod;
        $this->namespaceClient = $namespaceClient;
        $this->statusProvider = $statusProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable()
    {
        return !$this->closed;
    }

    /**
     * {@inheritdoc}
     */
    public function pause()
    {
        $this->timer->cancel();
    }

    /**
     * {@inheritdoc}
     */
    public function resume()
    {
        if (!$this->isReadable()) {
            return;
        }

        $eventEmitter = $this;
        $httpCallback = function () use ($eventEmitter) {
            $name = $eventEmitter->pod->getMetadata()->getName();
            $path = $eventEmitter->namespaceClient->prefixPath(sprintf('/pods/%s/log', $name));
            $eventEmitter->httpPromise = $this->httpConnector->asyncGet($path);
            $eventEmitter->httpPromise->then(function ($response) use ($eventEmitter) {
                $eventEmitter->emit('data', [$response]);

                if ($eventEmitter->statusProvider->isTerminated($eventEmitter->pod)) {
                    $eventEmitter->timer->cancel();
                    $eventEmitter->emit('end');
                    $eventEmitter->close();
                }
            }, function ($reason) use ($eventEmitter) {
                $this->emit('error', [new \RuntimeException('Unable to read pod output: ' . $reason)]);
                $this->close();
            });
            $eventEmitter->httpPromise->wait();
        };

        $this->timer = $this->loop->addPeriodicTimer($this->pollInterval, $httpCallback);
        $httpCallback();
    }

    /**
     * {@inheritdoc}
     */
    public function pipe(WritableStreamInterface $dest, array $options = array())
    {
        return Util::pipe($this, $dest, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->closed = true;
        $this->emit('close');
    }
}
