<?php

namespace Kubernetes\Client\Model;

use Kubernetes\Client\Model\Volume\EmptyDirVolumeSource;
use Kubernetes\Client\Model\Volume\NfsVolumeSource;

class Volume
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var EmptyDirVolumeSource
     */
    private $emptyDir;

    /**
     * @var NfsVolumeSource
     */
    private $nfs;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param EmptyDirVolumeSource $emptyDir
     */
    public function setEmptyDir($emptyDir)
    {
        $this->emptyDir = $emptyDir;
    }

    /**
     * @param NfsVolumeSource $nfs
     */
    public function setNfs($nfs)
    {
        $this->nfs = $nfs;
    }
}
