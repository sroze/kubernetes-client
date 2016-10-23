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
     * @var string
     */
    private $reason;

    /**
     * @var string
     */
    private $message;

    /**
     * @var EventSource
     */
    private $source;

    /**
     * @var string
     */
    private $firstTimestamp;

    /**
     * @var string
     */
    private $lastTimestamp;

    /**
     * @var integer
     */
    private $count;

    /**
     * @param ObjectMetadata $metadata
     * @param ObjectReference $involvedObject
     * @param string $reason
     * @param string $message
     * @param EventSource $source
     * @param string $firstTimestamp
     * @param string $lastTimestamp
     * @param int $count
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
}
