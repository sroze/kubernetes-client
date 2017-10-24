<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Adapter\Http\HttpAdapter;
use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Adapter\React\PodOutputStream;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\PodNotFound;
use Kubernetes\Client\Factory\PodStatusProviderFactory;
use Kubernetes\Client\Model\Pod;
use Kubernetes\Client\Model\PodStatusProvider;
use Kubernetes\Client\Model\PodList;
use Kubernetes\Client\Model\ReplicationController;
use Kubernetes\Client\Repository\PodRepository;
use React\EventLoop\LoopInterface;
use React\Stream\ReadableStreamInterface;

class HttpPodRepository implements PodRepository
{
    /**
     * Interval of the attach loop in case of using the "legacy" implementation.
     *
     * @var int
     */
    const ATTACH_LOOP_INTERVAL = 500;

    /**
     * @var HttpConnector
     */
    private $connector;

    /**
     * @var HttpNamespaceClient
     */
    private $namespaceClient;

    /**
     * @var PodStatusProvider
     */
    private $statusProvider;

    /**
     * @param HttpConnector $connector
     * @param HttpNamespaceClient $namespaceClient
     * @param PodStatusProviderFactory $statusProviderFactory
     */
    public function __construct(
        HttpConnector $connector,
        HttpNamespaceClient $namespaceClient,
        PodStatusProviderFactory $statusProviderFactory
    ) {
        $this->connector = $connector;
        $this->namespaceClient = $namespaceClient;
        $this->statusProvider = $statusProviderFactory->createFromRepository($this);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return $this->connector->get($this->namespaceClient->prefixPath('/pods'), [
            'class' => PodList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function asyncFindAll() : PromiseInterface
    {
        return $this->connector->asyncGet($this->namespaceClient->prefixPath('/pods'), [
            'class' => PodList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findByLabels(array $labels)
    {
        $labelSelector = HttpAdapter::createLabelSelector($labels);
        $path = $this->namespaceClient->prefixPath('/pods?labelSelector='.$labelSelector);

        return $this->connector->get($path, [
            'class' => PodList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(Pod $pod)
    {
        return $this->connector->post($this->namespaceClient->prefixPath('/pods'), $pod, [
            'class' => Pod::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Pod $pod)
    {
        $path = sprintf('/pods/%s', $pod->getMetadata()->getName());

        return $this->connector->patch($this->namespaceClient->prefixPath($path), $pod, [
            'class' => Pod::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        try {
            return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/pods/%s', $name)), [
                'class' => Pod::class,
                'groups' => ['Default', 'show'],
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new PodNotFound();
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Pod $pod)
    {
        try {
            $path = $this->namespaceClient->prefixPath(sprintf('/pods/%s', $pod->getMetadata()->getName()));

            return $this->connector->delete($path, null, [
                'class' => Pod::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new PodNotFound();
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findByReplicationController(ReplicationController $replicationController)
    {
        $selector = $replicationController->getSpecification()->getSelector();
        $labelSelector = HttpAdapter::createLabelSelector($selector);

        $path = '/pods?labelSelector='.urlencode($labelSelector);

        return $this->connector->get($this->namespaceClient->prefixPath($path), [
            'class' => PodList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        try {
            $this->findOneByName($name);
        } catch (PodNotFound $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attach(Pod $pod, callable $callable)
    {
        while ($this->statusProvider->isPending($pod)) {
            usleep(self::ATTACH_LOOP_INTERVAL * 1000);
        }

        $logCursor = 0;
        while (!$this->statusProvider->isTerminated($pod)) {
            $logCursor = $this->streamLogs($pod, $callable, $logCursor);

            usleep(self::ATTACH_LOOP_INTERVAL * 1000);
        }

        $this->streamLogs($pod, $callable, $logCursor);

        $pod = $this->findOneByName($pod->getMetadata()->getName());

        return $pod;
    }

    /**
     * {@inheritdoc}
     */
    public function streamOutput(Pod $pod, LoopInterface $loop): ReadableStreamInterface
    {
        return new PodOutputStream(
            $pod,
            $loop,
            $this->connector,
            $this->namespaceClient,
            $this->statusProvider,
            self::ATTACH_LOOP_INTERVAL / 1000
        );
    }

    /**
     * @param Pod      $pod
     * @param callable $callable
     * @param int      $cursor
     *
     * @return int
     */
    private function streamLogs(Pod $pod, callable $callable, $cursor)
    {
        $logs = $this->getLogs($pod);
        $incrementalLogs = substr($logs, $cursor);

        if (!empty($incrementalLogs)) {
            $cursor += strlen($incrementalLogs);

            $callable($incrementalLogs);
        }

        return $cursor;
    }

    /**
     * Get pod's logs.
     *
     * @param Pod $pod
     *
     * @return string
     */
    private function getLogs(Pod $pod)
    {
        $name = $pod->getMetadata()->getName();

        return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/pods/%s/log', $name)));
    }
}
