<?php

namespace Kubernetes\Client\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Exception\JobNotFound;
use Kubernetes\Client\Model\Job;
use Kubernetes\Client\Model\JobList;
use Kubernetes\Client\Model\ReplicationController;

/**
 * Interface JobRepository
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
interface JobRepository
{
    /**
     * @return JobList
     */
    public function findAll();

    /**
     * Find all the jobs. The promise will return a `JobList` object.
     *
     * @return PromiseInterface
     */
    public function asyncFindAll() : PromiseInterface;

    /**
     * @param array $labels
     *
     * @return JobList
     */
    public function findByLabels(array $labels);

    /**
     * @param Job $job
     *
     * @return Job
     */
    public function create(Job $job);

    /**
     * @param Job $job
     *
     * @return Job
     */
    public function update(Job $job);

    /**
     * @param string $name
     *
     * @throws JobNotFound
     *
     * @return Job
     */
    public function findOneByName($name);

    /**
     * Check if the job with the given name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);

    /**
     * @param Job $job
     *
     * @throws JobNotFound
     */
    public function delete(Job $job);

    /**
     * @param ReplicationController $replicationController
     *
     * @return JobList
     */
    public function findByReplicationController(ReplicationController $replicationController);
}
