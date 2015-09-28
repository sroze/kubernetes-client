<?php

namespace Kubernetes\Client\Model;

class Container
{
    const PULL_POLICY_ALWAYS = 'Always';
    const PULL_POLICY_IF_NOT_PRESENT = 'IfNotPresent';
    const PULL_POLICY_NEVER = 'Never';

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $image;
    /**
     * @var string
     */
    private $pullPolicy;
    /**
     * @var EnvironmentVariable[]
     */
    private $environmentVariables;
    /**
     * @var ContainerPort[]
     */
    private $ports;
    /**
     * @var VolumeMount[]
     */
    private $volumeMounts;
    /**
     * @var array
     */
    private $command;

    /**
     * @param string                $name
     * @param string                $image
     * @param EnvironmentVariable[] $environmentVariables
     * @param ContainerPort[]       $ports
     * @param VolumeMount[]         $volumeMounts
     * @param string                $pullPolicy
     * @param array                 $command
     */
    public function __construct($name, $image, array $environmentVariables = [], array $ports = [], array $volumeMounts = [], $pullPolicy = self::PULL_POLICY_ALWAYS, array $command = null)
    {
        $this->name = $name;
        $this->image = $image;
        $this->environmentVariables = $environmentVariables;
        $this->ports = $ports;
        $this->volumeMounts = $volumeMounts;
        $this->pullPolicy = $pullPolicy;
        $this->command = $command;
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
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getPullPolicy()
    {
        return $this->pullPolicy;
    }

    /**
     * @return EnvironmentVariable[]
     */
    public function getEnvironmentVariables()
    {
        return $this->environmentVariables;
    }

    /**
     * @return ContainerPort[]
     */
    public function getPorts()
    {
        return $this->ports;
    }

    /**
     * @return VolumeMount[]
     */
    public function getVolumeMounts()
    {
        return $this->volumeMounts;
    }

    /**
     * @return array|null
     */
    public function getCommand()
    {
        return $this->command;
    }
}
