<?php

namespace Kubernetes\Client\Model\Volume;

class NfsVolumeSource
{
    /**
     * @var string
     */
    private $server;

    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $readOnly;

    /**
     * @param string $server
     * @param string $path
     * @param bool   $readOnly
     */
    public function __construct($server, $path, $readOnly)
    {
        $this->server = $server;
        $this->path = $path;
        $this->readOnly = $readOnly;
    }
}
