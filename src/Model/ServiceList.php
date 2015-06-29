<?php

namespace Kubernetes\Client\Model;

class ServiceList implements \IteratorAggregate
{
    /**
     * @var Service[]
     */
    private $items = [];

    /**
     * @param Service[] $services
     *
     * @return ServiceList
     */
    public static function fromServices(array $services)
    {
        $list = new self();
        $list->items = $services;

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
     * @return Service[]
     */
    public function getServices()
    {
        return $this->items;
    }
}
