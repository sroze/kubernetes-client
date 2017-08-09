<?php

namespace Kubernetes\Client\Model;

class Event implements KubernetesObject
{
    /**
     * @var ObjectMetadata
     */
    private $metadata;

    /**
     * @var ObjectReference
     */
    private $involvedObject;

    /**
     * @var string|null
     */
    private $reason;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @var EventSource|null
     */
    private $source;

    /**
     * @var string|null
     */
    private $firstTimestamp;

    /**
     * @var string|null
     */
    private $lastTimestamp;

    /**
     * @var int|null
     */
    private $count;

    /**
     * @param ObjectMetadata  $metadata
     * @param ObjectReference $involvedObject
     * @param string          $reason
     * @param string          $message
     * @param EventSource     $source
     * @param string          $firstTimestamp
     * @param string          $lastTimestamp
     * @param int             $count
     */
    public function __construct(ObjectMetadata $metadata, ObjectReference $involvedObject, $reason = null, $message = null, EventSource $source = null, $firstTimestamp = null, $lastTimestamp = null, $count = null)
    {
        $this->metadata = $metadata;
        $this->involvedObject = $involvedObject;
        $this->reason = $reason;
        $this->message = $message;
        $this->source = $source;
        $this->firstTimestamp = $firstTimestamp;
        $this->lastTimestamp = $lastTimestamp;
        $this->count = $count;
    }

    /**
     * {@inheritdoc}
     */
    public function getKind()
    {
        return 'Event';
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return ObjectReference
     */
    public function getInvolvedObject(): ObjectReference
    {
        return $this->involvedObject;
    }

    /**
     * @return null|string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return EventSource|null
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return null|string
     */
    public function getFirstTimestamp()
    {
        return $this->firstTimestamp;
    }

    /**
     * @return null|string
     */
    public function getLastTimestamp()
    {
        return $this->lastTimestamp;
    }

    /**
     * @return int|null
     */
    public function getCount()
    {
        return $this->count;
    }
}
