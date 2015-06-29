<?php

namespace Kubernetes\Client\Model;

class PodList implements \IteratorAggregate
{
    /**
     * @var Pod[]
     */
    private $items = [];

    /**
     * @param Pod[] $pods
     *
     * @return PodList
     */
    public static function fromPods(array $pods)
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
        return new \ArrayIterator($this->items);
    }

    /**
     * @return Pod[]
     */
    public function getPods()
    {
        return $this->items;
    }
}
