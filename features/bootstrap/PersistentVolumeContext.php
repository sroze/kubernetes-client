<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Kubernetes\Client\Model\ObjectMetadata;
use Kubernetes\Client\Model\PersistentVolumeClaim;
use Kubernetes\Client\Model\PersistentVolumeClaimSpecification;
use Kubernetes\Client\Model\ResourceRequirements;
use Kubernetes\Client\Model\ResourceRequirementsRequests;

class PersistentVolumeContext implements Context
{
    /**
     * @var ClientContext
     */
    private $clientContext;

    /**
     * @var NamespaceContext
     */
    private $namespaceContext;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->clientContext = $scope->getEnvironment()->getContext('ClientContext');
        $this->namespaceContext = $scope->getEnvironment()->getContext('NamespaceContext');
    }

    /**
     * @When I created a persistent volume claim
     */
    public function iCreatedAPersistentVolumeClaim()
    {
        $this->createClaimWith('my-claim');
    }

    /**
     * @Then the persistent volume claim should exists
     */
    public function thePersistentVolumeClaimShouldExists()
    {
        $this->getRepository()->findOneByName('my-claim');
    }

    /**
     * @Given I have a persistent volume claim :name
     */
    public function iHaveAPersistentVolumeClaim($name)
    {
        $this->createClaimWith($name);
    }

    /**
     * @param string $name
     *
     * @return PersistentVolumeClaim
     */
    private function createClaimWith($name)
    {
        return $this->getRepository()->create(new PersistentVolumeClaim(
            new ObjectMetadata($name),
            new PersistentVolumeClaimSpecification(['ReadWriteMany'], new ResourceRequirements(new ResourceRequirementsRequests(
                '5Gi'
            )))
        ));
    }

    /**
     * @return \Kubernetes\Client\Repository\PersistentVolumeClaimRepository
     */
    private function getRepository()
    {
        $client = $this->clientContext->getClient()->getNamespaceClient(
            $this->namespaceContext->getNamespace()
        );

        return $client->getPersistentVolumeClaimRepository();
    }
}
