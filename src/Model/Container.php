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
     * @var array
     */
    private $args;

    /**
     * @var SecurityContext
     */
    private $securityContext;

    /**
     * @var ResourceRequirements
     */
    private $resources;

    /**
     * @var Probe
     */
    private $livenessProbe;

    /**
     * @var Probe
     */
    private $readinessProbe;

    /**
     * @param string                $name
     * @param string                $image
     * @param EnvironmentVariable[] $environmentVariables
     * @param ContainerPort[]       $ports
     * @param VolumeMount[]         $volumeMounts
     * @param string                $pullPolicy
     * @param array                 $command
     * @param array                 $args
     * @param SecurityContext       $securityContext
     * @param ResourceRequirements  $resources
     * @param Probe                 $livenessProbe
     * @param Probe                 $readinessProbe
     */
    public function __construct($name, $image, array $environmentVariables = [], array $ports = [], array $volumeMounts = [], $pullPolicy = self::PULL_POLICY_ALWAYS, array $command = null, array $args = null, SecurityContext $securityContext = null, ResourceRequirements $resources = null, Probe $livenessProbe = null, Probe $readinessProbe = null)
    {
        $this->name = $name;
        $this->image = $image;
        $this->environmentVariables = $environmentVariables;
        $this->ports = $ports;
        $this->volumeMounts = $volumeMounts;
        $this->pullPolicy = $pullPolicy;
        $this->command = $command;
        $this->args = $args;
        $this->securityContext = $securityContext;
        $this->resources = $resources;
        $this->livenessProbe = $livenessProbe;
        $this->readinessProbe = $readinessProbe;
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
        return $this->environmentVariables ?: [];
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

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @return SecurityContext
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * @return ResourceRequirements
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @return Probe
     */
    public function getLivenessProbe()
    {
        return $this->livenessProbe;
    }

    /**
     * @return Probe
     */
    public function getReadinessProbe()
    {
        return $this->readinessProbe;
    }
}
