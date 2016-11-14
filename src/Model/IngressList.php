<?php

namespace Kubernetes\Client\Model;

class IngressList implements \IteratorAggregate
{
    /**
     * @var Ingress[]
     */
    private $items = [];

    /**
     * @param Ingress[] $ingresses
     *
     * @return IngressList
     */
    public static function fromIngresses(array $ingresses)
    {
        $list = new self();
        $list->items = $ingresses;

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
     * @return Ingress[]
     */
    public function getIngresses()
    {
        return $this->items;
    }
}
