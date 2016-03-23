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
     * @When I create a secret :name
     */
    public function iCreateASecret($name)
    {
        $this->secret = new Secret(
            new ObjectMetadata($name),
            [
                'foo' => base64_encode('bar'),
            ]
        );

        $this->getRepository()->create($this->secret);
    }

    /**
     * @Then the secret :name should exists
     */
    public function theSecretShouldExists($name)
    {
        if (!$this->getRepository()->exists($name)) {
            throw new \RuntimeException(sprintf(
                'The secret "%s" do not exists',
                $name
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
