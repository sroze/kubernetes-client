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

    /**
     * @param string $name
     * @param string $mountPath
     * @param bool   $readOnly
     */
    public function __construct($name, $mountPath, $readOnly = false)
    {
        $this->name = $name;
        $this->mountPath = $mountPath;
        $this->readOnly = $readOnly;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMountPath()
    {
        return $this->mountPath;
    }
}
