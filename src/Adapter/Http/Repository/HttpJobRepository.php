<?php

namespace Kubernetes\Client\Adapter\Http\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Adapter\Http\HttpAdapter;
use Kubernetes\Client\Adapter\Http\HttpConnector;
use Kubernetes\Client\Adapter\Http\HttpNamespaceClient;
use Kubernetes\Client\Exception\ClientError;
use Kubernetes\Client\Exception\JobNotFound;
use Kubernetes\Client\Model\ContainerStatus;
use Kubernetes\Client\Model\Job;
use Kubernetes\Client\Model\JobList;
use Kubernetes\Client\Model\JobStatus;
use Kubernetes\Client\Model\ReplicationController;
use Kubernetes\Client\Repository\JobRepository;

/**
 * Class HttpJobRepository
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class HttpJobRepository implements JobRepository
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
        return $this->connector->get($this->namespaceClient->prefixPath('/jobs'), [
            'class' => JobList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function asyncFindAll() : PromiseInterface
    {
        return $this->connector->asyncGet($this->namespaceClient->prefixPath('/jobs'), [
            'class' => JobList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findByLabels(array $labels)
    {
        $labelSelector = HttpAdapter::createLabelSelector($labels);
        $path = $this->namespaceClient->prefixPath('/jobs?labelSelector='.$labelSelector);

        return $this->connector->get($path, [
            'class' => JobList::class,
            'groups' => ['Default', 'show'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(Job $job)
    {
        return $this->connector->post($this->namespaceClient->prefixPath('/jobs'), $job, [
            'class' => Job::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Job $job)
    {
        $path = sprintf('/jobs/%s', $job->getMetadata()->getName());

        return $this->connector->patch($this->namespaceClient->prefixPath($path), $job, [
            'class' => Job::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName($name)
    {
        try {
            return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/jobs/%s', $name)), [
                'class' => Job::class,
                'groups' => ['Default', 'show'],
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new JobNotFound();
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Job $job)
    {
        try {
            $path = $this->namespaceClient->prefixPath(sprintf('/jobs/%s', $job->getMetadata()->getName()));

            return $this->connector->delete($path, null, [
                'class' => Job::class,
            ]);
        } catch (ClientError $e) {
            if ($e->getStatus()->getCode() === 404) {
                throw new JobNotFound();
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

        $path = '/jobs?labelSelector='.urlencode($labelSelector);

        return $this->connector->get($this->namespaceClient->prefixPath($path), [
            'class' => JobList::class,
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
        } catch (JobNotFound $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attach(Job $job, callable $callable)
    {
        while ($this->isPending($job)) {
            usleep(self::ATTACH_LOOP_INTERVAL * 1000);
        }

        $logCursor = 0;
        while (!$this->isTerminated($job)) {
            $logCursor = $this->streamLogs($job, $callable, $logCursor);

            usleep(self::ATTACH_LOOP_INTERVAL * 1000);
        }

        $this->streamLogs($job, $callable, $logCursor);

        $job = $this->findOneByName($job->getMetadata()->getName());

        return $job;
    }

    /**
     * @param Job      $job
     * @param callable $callable
     * @param int      $cursor
     *
     * @return int
     */
    private function streamLogs(Job $job, callable $callable, $cursor)
    {
        $logs = $this->getLogs($job);
        $incrementalLogs = substr($logs, $cursor);

        if (!empty($incrementalLogs)) {
            $cursor += strlen($incrementalLogs);

            $callable($incrementalLogs);
        }

        return $cursor;
    }

    /**
     * Get job's logs.
     *
     * @param Job $job
     *
     * @return string
     */
    private function getLogs(Job $job)
    {
        $name = $job->getMetadata()->getName();

        return $this->connector->get($this->namespaceClient->prefixPath(sprintf('/jobs/%s/log', $name)));
    }

    /**
     * Returns true if a job is terminated.
     *
     * @param Job $job
     *
     * @return bool
     */
    private function isTerminated(Job $job)
    {
        $job = $this->findOneByName($job->getMetadata()->getName());

        return $job->getStatus()->getCompletionTime() !== null;
    }

    /**
     * @param Job $job
     *
     * @return bool
     *
     * @throws ClientError
     * @throws JobNotFound
     */
    private function isPending(Job $job)
    {
        $job = $this->findOneByName($job->getMetadata()->getName());

        $jobStatus = $job->getStatus();

        return $jobStatus->getCompletionTime() === null && $jobStatus->getStartTime() === null;
    }
}
