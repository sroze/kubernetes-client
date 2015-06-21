<?php

namespace Kubernetes\Client\Model;

class ReplicationControllerList implements \IteratorAggregate
{
    /**
     * @var ReplicationController[]
     */
    private $items = [];

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return ReplicationController[]
     */
    public function getReplicationControllers()
    {
        return $this->items;
    }

    public function count()
    {
        return count($this->items);
    }
}
