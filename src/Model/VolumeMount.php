<?php

namespace Kubernetes\Client\Model;

class VolumeMount
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $mountPath;

    /**
     * @var bool
     */
    private $readOnly;

    public function __construct($name, $mountPath, $readOnly = false)
    {
        $this->name = $name;
        $this->mountPath = $mountPath;
        $this->readOnly = $readOnly;
    }
}
