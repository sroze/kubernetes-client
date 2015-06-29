<?php

namespace Kubernetes\Client\Model;

class ReplicationControllerList implements \IteratorAggregate
{
    /**
     * @var ReplicationController[]
     */
    private $items = [];

    /**
     * @param ReplicationController[] $replicationControllers
     *
     * @return ReplicationControllerList
     */
    public static function fromReplicationControllers(array $replicationControllers)
    {
        $list = new self();
        $list->items = $replicationControllers;

        return $list;
    }

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

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }
}
