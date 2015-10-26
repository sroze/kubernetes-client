<?php

namespace Kubernetes\Client\Model;

use Kubernetes\Client\Model\Volume\EmptyDirVolumeSource;
use Kubernetes\Client\Model\Volume\HostPathVolumeSource;
use Kubernetes\Client\Model\Volume\NfsVolumeSource;
use Kubernetes\Client\Model\Volume\PersistentVolumeClaimSource;

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

    /**
     * @var HostPathVolumeSource
     */
    private $hostPath;

    /**
     * @var PersistentVolumeClaimSource
     */
    private $persistentVolumeClaim;

    /**
     * @param string $name
     */
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

    /**
     * @param HostPathVolumeSource $hostPath
     */
    public function setHostPath($hostPath)
    {
        $this->hostPath = $hostPath;
    }

    /**
     * @param PersistentVolumeClaimSource $persistentVolumeClaim
     */
    public function setPersistentVolumeClaim($persistentVolumeClaim)
    {
        $this->persistentVolumeClaim = $persistentVolumeClaim;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return EmptyDirVolumeSource
     */
    public function getEmptyDir()
    {
        return $this->emptyDir;
    }

    /**
     * @return NfsVolumeSource
     */
    public function getNfs()
    {
        return $this->nfs;
    }

    /**
     * @return HostPathVolumeSource
     */
    public function getHostPath()
    {
        return $this->hostPath;
    }

    /**
     * @return PersistentVolumeClaimSource
     */
    public function getPersistentVolumeClaim()
    {
        return $this->persistentVolumeClaim;
    }
}
