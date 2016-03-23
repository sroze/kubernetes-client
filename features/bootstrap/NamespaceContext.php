<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Kubernetes\Client\Exception\NamespaceNotFound;
use Kubernetes\Client\Model\KeyValueObjectList;
use Kubernetes\Client\Model\KubernetesNamespace;
use Kubernetes\Client\Model\Label;
use Kubernetes\Client\Model\NamespaceList;
use Kubernetes\Client\Model\NamespaceStatus;
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
     * @Transform :labels
     */
    public function castLabelsToKeyValueObjectList($string)
    {
        $labels = new KeyValueObjectList();

        foreach (explode(',', $string) as $label) {
            list($key, $value) = explode('=', $label);

            $labels->add(new Label($key, $value));
        }

        return $labels;
    }

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
        if (self::$namespace !== null) {
            $this->iDeleteTheNamespace(self::$namespace->getMetadata()->getName());
        }
    }

    /**
     * @When I send a request creation for the namespace :name
     */
    public function iSendARequestCreationForTheNamespace($name)
    {
        $namespace = new KubernetesNamespace(new ObjectMetadata($name));

        $this->createNamespace($namespace);
    }

    /**
     * @Then the namespace :name should exists
     */
    public function theNamespaceShouldExists($name)
    {
        if (!$this->getRepository()->exists($name)) {
            throw new \RuntimeException(sprintf(
                'The namespace "%s" do not exists',
                $name
            ));
        }
    }

    /**
     * @Given I have a namespace
     */
    public function iHaveANamespace()
    {
        $this->iHaveANamedNamespace(uniqid());
    }

    /**
     * @Given the namespace :name is ready
     */
    public function theNamespaceIsReady($name)
    {
        do {
            $phase = $this->getRepository()->findOneByName($name)->getStatus()->getPhase();
        } while ($this->clientContext->isIntegration() && $phase !== NamespaceStatus::PHASE_ACTIVE && sleep(1) == 0);
    }

    /**
     * @Given I have a namespace :name
     */
    public function iHaveANamedNamespace($name)
    {
        if (null == self::$namespace || self::$isDeleted) {
            $this->iSendARequestCreationForTheNamespace($name);
        }
    }

    /**
     * @Given I have a namespace :name with the labels :labels
     */
    public function iHaveANamespaceWithTheLabels($name, KeyValueObjectList $labels)
    {
        $namespace = new KubernetesNamespace(new ObjectMetadata($name, $labels));

        $this->createNamespace($namespace);
    }

    /**
     * @When I get the list of namespaces
     */
    public function iGetTheListOfNamespaces()
    {
        $this->namespaceList = $this->getRepository()->findAll();
    }

    /**
     * @When I get the list of namespaces matching the labels :labels
     */
    public function iGetTheListOfNamespacesMatchingTheLabels(KeyValueObjectList $labels)
    {
        $this->namespaceList = $this->getRepository()->findByLabels($labels);
    }

    /**
     * @Then the namespace :name should be in the list
     */
    public function theNamespaceShouldBeInTheList($name)
    {
        $matchingNamespaces = array_filter($this->namespaceList->getNamespaces(), function (KubernetesNamespace $namespace) use ($name) {
            return $namespace->getMetadata()->getName() == $name;
        });

        if (0 == count($matchingNamespaces)) {
            throw new \RuntimeException(sprintf(
                'Namespace "%s" not found in list',
                $name
            ));
        }
    }

    /**
     * @Then the namespace :name should not be in the list
     */
    public function theNamespaceShouldNotBeInTheList($name)
    {
        $matchingNamespaces = array_filter($this->namespaceList->getNamespaces(), function (KubernetesNamespace $namespace) use ($name) {
            return $namespace->getMetadata()->getName() == $name;
        });

        if (0 != count($matchingNamespaces)) {
            throw new \RuntimeException(sprintf(
                'Namespace "%s" found in list',
                $name
            ));
        }
    }

    /**
     * @When I delete the namespace :name
     */
    public function iDeleteTheNamespace($name)
    {
        $this->getRepository()->delete(new KubernetesNamespace(new ObjectMetadata($name)));

        self::$isDeleted = true;
    }

    /**
     * @Then the namespace :name should not exists
     */
    public function theNamespaceShouldNotExists($name)
    {
        if ($this->getRepository()->exists($name)) {
            throw new \RuntimeException(sprintf(
                'The namespace "%s" exists',
                $name
            ));
        }
    }

    /**
     * @Then the namespace :name should not exists or be terminating
     */
    public function theNamespaceShouldNotExistsOrBeTerminating($name)
    {
        try {
            $namespace = $this->getRepository()->findOneByName($name);
            $statusPhase = $namespace->getStatus()->getPhase();

            if (strtolower($statusPhase) !== 'terminating') {
                throw new \RuntimeException(sprintf(
                    'Found namespace "%s" is status "%s" instead of terminating',
                    $name,
                    $statusPhase
                ));
            }
        } catch (NamespaceNotFound $e) {
        }
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

    /**
     * @param $namespace
     */
    private function createNamespace($namespace)
    {
        self::$namespace = $namespace;
        self::$isDeleted = false;

        $this->getRepository()->create(
            self::$namespace
        );
    }
}
