<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\PodNotFound;
use Kubernetes\Client\Model\ContainerStatus;
use Kubernetes\Client\Model\Pod;
use Kubernetes\Client\Model\PodList;
use Kubernetes\Client\Model\PodStatus;
use Kubernetes\Client\Model\ReplicationController;
use Kubernetes\Client\Repository\PodRepository;

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
     * @param HttpConnector       $connector
     * @param HttpNamespaceClient $namespaceClient
     */
    public function __construct(HttpConnector $connector, HttpNamespaceClient $namespaceClient)
    {
        $this->connector = $connector;
        $this->namespaceClient = $namespaceClient;
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
    public function findByLabels(array $labels)
    {
        $labelSelector = $this->namespaceClient->createLabelSelector($labels);
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
        $labelSelector = $this->namespaceClient->createLabelSelector($selector);

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
        while ($this->isPending($pod)) {
            usleep(self::ATTACH_LOOP_INTERVAL * 1000);
        }

        $logCursor = 0;
        while (!$this->isTerminated($pod)) {
            $logCursor = $this->streamLogs($pod, $callable, $logCursor);

            usleep(self::ATTACH_LOOP_INTERVAL * 1000);
        }

        $this->streamLogs($pod, $callable, $logCursor);

        $pod = $this->findOneByName($pod->getMetadata()->getName());

        return $pod;
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

    /**
     * Returns true if a pod is terminated.
     *
     * @param Pod $pod
     *
     * @return bool
     */
    private function isTerminated(Pod $pod)
    {
        $pod = $this->findOneByName($pod->getMetadata()->getName());

        $containerStatuses = $pod->getStatus()->getContainerStatuses();
        $terminatedContainers = array_filter($containerStatuses, function (ContainerStatus $containerStatus) {
            return null != $containerStatus->getState()->getTerminated();
        });

        return count($terminatedContainers) == count($containerStatuses);
    }

    /**
     * @param Pod $pod
     *
     * @return bool
     *
     * @throws ClientError
     * @throws PodNotFound
     */
    private function isPending(Pod $pod)
    {
        $pod = $this->findOneByName($pod->getMetadata()->getName());

        $podStatus = $pod->getStatus();

        return $podStatus->getPhase() == PodStatus::PHASE_PENDING;
    }
}
