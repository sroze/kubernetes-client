<?php

namespace Kubernetes\Client\Model;

class EventSource
{
    /**
     * @var string
     */
    private $component;

    /**
     * @var string
     */
    private $host;

    /**
     * @param string $component
     * @param string $host
     */
    public function __construct($component = null, $host = null)
    {
        $this->component = $component;
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }
}
