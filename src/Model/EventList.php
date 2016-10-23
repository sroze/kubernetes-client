<?php

namespace Kubernetes\Client\Model;

class EventList implements \IteratorAggregate
{
    /**
     * @var Event[]
     */
    private $items = [];

    /**
     * @param Event[] $events
     *
     * @return EventList
     */
    public static function fromEvents(array $events)
    {
        $list = new self();
        $list->items = $events;

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
     * @return Event[]
     */
    public function getEvents()
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
