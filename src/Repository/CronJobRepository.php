<?php

namespace Kubernetes\Client\Repository;

use GuzzleHttp\Promise\PromiseInterface;
use Kubernetes\Client\Exception\CronJobNotFound;
use Kubernetes\Client\Model\CronJob;
use Kubernetes\Client\Model\CronJobList;
use Kubernetes\Client\Model\ReplicationController;

/**
 * Interface CronJobRepository
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
interface CronJobRepository
{
    /**
     * @return CronJobList
     */
    public function findAll();

    /**
     * Find all the cronJobs. The promise will return a `CronJobList` object.
     *
     * @return PromiseInterface
     */
    public function asyncFindAll() : PromiseInterface;

    /**
     * @param array $labels
     *
     * @return CronJobList
     */
    public function findByLabels(array $labels);

    /**
     * @param CronJob $cronJob
     *
     * @return CronJob
     */
    public function create(CronJob $cronJob);

    /**
     * @param CronJob $cronJob
     *
     * @return CronJob
     */
    public function update(CronJob $cronJob);

    /**
     * @param string $name
     *
     * @throws CronJobNotFound
     *
     * @return CronJob
     */
    public function findOneByName($name);

    /**
     * Check if the cronJob with the given name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name);

    /**
     * @param CronJob $cronJob
     *
     * @throws CronJobNotFound
     */
    public function delete(CronJob $cronJob);

    /**
     * @param ReplicationController $replicationController
     *
     * @return CronJobList
     */
    public function findByReplicationController(ReplicationController $replicationController);
}
