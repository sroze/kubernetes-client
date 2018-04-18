<?php

namespace Kubernetes\Client\Model;

/**
 * Class CronJobList
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class CronJobList implements \IteratorAggregate
{
    /**
     * @var CronJob[]
     */
    private $items = [];

    /**
     * @param CronJob[] $cronJobs
     *
     * @return CronJobList
     */
    public static function fromCronJobs(array $cronJobs)
    {
        $list = new self();
        $list->items = $cronJobs;

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getCronJobs());
    }

    /**
     * @return CronJob[]
     */
    public function getCronJobs()
    {
        return $this->items ?: [];
    }
}
