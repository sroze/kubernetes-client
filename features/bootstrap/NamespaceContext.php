<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\Model\NamespaceList;
use Kubernetes\Client\Model\ObjectMetadata;

class NamespaceContext implements Context
{
    /**
     * @var ClientContext
     */
    private $clientContext;

    /**
     * @var KubernetesNamespace
     */
    private $namespace;

    /**
     * @var NamespaceList
     */
    private $namespaceList;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->clientContext = $scope->getEnvironment()->getContext('ClientContext');
    }

    /**
     * @When I send a request creation for a namespace
     */
    public function iSendARequestCreationForANamespace()
    {
        $this->namespace = new KubernetesNamespace(new ObjectMetadata(uniqid()));
        $this->getRepository()->create(
            $this->namespace
        );
    }

    /**
     * @Then the namespace should exists
     */
    public function theNamespaceShouldExists()
    {
        if (!$this->getRepository()->exists($this->getNamespaceName())) {
            throw new \RuntimeException(sprintf(
                'The namespace "%s" do not exists',
                $this->getNamespaceName()
            ));
        }
    }

    /**
     * @Given I have a namespace
     */
    public function iHaveANamespace()
    {
        $this->iSendARequestCreationForANamespace();
    }

    /**
     * @When I get the list of namespaces
     */
    public function iGetTheListOfNamespaces()
    {
        $this->namespaceList = $this->getRepository()->findAll();
    }

    /**
     * @Then the namespace should be in the list
     */
    public function theNamespaceShouldBeInTheList()
    {
        $matchingNamespaces = array_filter($this->namespaceList->getNamespaces(), function(KubernetesNamespace $namespace) {
            return $namespace->getMetadata()->getName() == $this->getNamespaceName();
        });

        if (0 == count($matchingNamespaces)) {
            throw new \RuntimeException(sprintf(
                'Namespace "%s" not found in list',
                $this->getNamespaceName()
            ));
        }
    }

    /**
     * @When I delete the namespace
     */
    public function iDeleteTheNamespace()
    {
        $this->getRepository()->delete($this->namespace);
    }

    /**
     * @Then the namespace should not exists
     */
    public function theNamespaceShouldNotExists()
    {
        if ($this->getRepository()->exists($this->getNamespaceName())) {
            throw new \RuntimeException(sprintf(
                'The namespace "%s" exists',
                $this->getNamespaceName()
            ));
        }
    }

    /**
     * @return string
     */
    private function getNamespaceName()
    {
        return $this->namespace->getMetadata()->getName();
    }

    /**
     * @return \Kubernetes\Client\Repository\NamespaceRepository
     */
    private function getRepository()
    {
        return $this->clientContext->getClient()->getNamespaceRepository();
    }

    /**
     * @return KubernetesNamespace
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
}
