<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Kubernetes\Client\Exception\NamespaceNotFound;
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
    private static $namespace;

    /**
     * @var NamespaceList
     */
    private $namespaceList;

    /**
     * @var bool
     */
    private static $isDeleted = false;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->clientContext = $scope->getEnvironment()->getContext('ClientContext');
    }

    /**
     * @AfterScenario @cleanNamespace
     */
    public function cleanNamespace()
    {
        $this->iDeleteTheNamespace();
    }

    /**
     * @When I send a request creation for a namespace
     */
    public function iSendARequestCreationForANamespace()
    {
        self::$namespace = new KubernetesNamespace(new ObjectMetadata(uniqid()));
        self::$isDeleted = false;

        $this->getRepository()->create(
            self::$namespace
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
        if (null == self::$namespace || self::$isDeleted) {
            $this->iSendARequestCreationForANamespace();
        }
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
        $this->getRepository()->delete($this->getNamespace());

        self::$isDeleted = true;
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
     * @Then the namespace should not exists or be terminating
     */
    public function theNamespaceShouldNotExistsOrBeTerminating()
    {
        try {
            $namespace = $this->getRepository()->findOneByName($this->getNamespaceName());
            $statusPhase = $namespace->getStatus()->getPhase();

            if (strtolower($statusPhase) !== 'terminating') {
                throw new \RuntimeException(sprintf(
                    'Found namespace "%s" is status "%s" instead of terminating',
                    $this->getNamespaceName(),
                    $statusPhase
                ));
            }
        } catch (NamespaceNotFound $e) {
        }
    }

    /**
     * @return string
     */
    private function getNamespaceName()
    {
        return $this->getNamespace()->getMetadata()->getName();
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
        return self::$namespace;
    }
}
