<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Kubernetes\Client\Model\ObjectMetadata;
use Kubernetes\Client\Model\Secret;

class SecretContext implements Context
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
     * @var Secret
     */
    private $secret;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->clientContext = $scope->getEnvironment()->getContext('ClientContext');
        $this->namespaceContext = $scope->getEnvironment()->getContext('NamespaceContext');
    }

    /**
     * @When I create a secret
     */
    public function iCreateASecret()
    {
        $this->secret = new Secret(
            new ObjectMetadata(uniqid()),
            'a-given-data'
        );

        $this->getRepository()->create($this->secret);
    }

    /**
     * @Then the secret should exists
     */
    public function theSecretShouldExists()
    {
        $secretName = $this->secret->getMetadata()->getName();

        if (!$this->getRepository()->exists($secretName)) {
            throw new \RuntimeException(sprintf(
                'The secret "%s" do not exists',
                $secretName
            ));
        }
    }

    /**
     * @return \Kubernetes\Client\Repository\SecretRepository
     */
    private function getRepository()
    {
        $client = $this->clientContext->getClient()->getNamespaceClient(
            $this->namespaceContext->getNamespace()
        );

        return $client->getSecretRepository();
    }
}