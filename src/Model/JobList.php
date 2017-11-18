<?php

namespace Kubernetes\Client\Model;

/**
 * Class JobList
 *
 * @author Samuel Roze <samuel.roze@gmail.com>
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class JobList implements \IteratorAggregate
{
    /**
     * @var Job[]
     */
    private $items = [];

    /**
     * @param Job[] $pods
     *
     * @return JobList
     */
    public static function fromJobs(array $pods)
    {
        $list = new self();
        $list->items = $pods;

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getJobs());
    }

    /**
     * @return Job[]
     */
    public function getJobs()
    {
        return $this->items ?: [];
    }
}
