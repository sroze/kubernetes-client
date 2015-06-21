<?php

namespace Kubernetes\Client\Model;

class ServiceList implements \IteratorAggregate
{
    /**
     * @var Service[]
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
     * @return Service[]
     */
    public function getServices()
    {
        return $this->items;
    }
}
