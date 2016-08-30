<?php

namespace Kubernetes\Client\Model\Deployment;

class DeploymentRollback
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var RollbackConfiguration
     */
    private $rollbackTo;

    /**
     * @var array<string,string>
     */
    private $updatedAnnotations;

    /**
     * @param string                $name
     * @param RollbackConfiguration $rollbackTo
     * @param array                 $updatedAnnotations
     */
    public function __construct($name, RollbackConfiguration $rollbackTo, array $updatedAnnotations = null)
    {
        $this->name = $name;
        $this->rollbackTo = $rollbackTo;
        $this->updatedAnnotations = $updatedAnnotations;
    }

    /**
     * @return string
     */
    public function getKind()
    {
        return 'DeploymentRollback';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return RollbackConfiguration
     */
    public function getRollbackTo()
    {
        return $this->rollbackTo;
    }

    /**
     * @return array
     */
    public function getUpdatedAnnotations()
    {
        return $this->updatedAnnotations;
    }
}
