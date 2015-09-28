<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Kubernetes\Client\Model\Container;
use Kubernetes\Client\Model\EnvironmentVariable;
use Kubernetes\Client\Model\ObjectMetadata;
use Kubernetes\Client\Model\Pod;
use Kubernetes\Client\Model\PodSpecification;

class PodContext implements Context
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
     * @var Pod
     */
    private $pod;

    /**
     * @var float
     */
    private $creationMicroTime;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->clientContext = $scope->getEnvironment()->getContext('ClientContext');
        $this->namespaceContext = $scope->getEnvironment()->getContext('NamespaceContext');
    }

    /**
     * @AfterScenario @deletePod
     */
    public function deletePod()
    {
        if (null !== $this->pod) {
            $this->getRepository()->delete($this->pod);
        }
    }

    /**
     * @When I create a pod
     */
    public function iCreateAPod()
    {
        $specification = new PodSpecification([
            new Container('foo', 'hello-world'),
        ]);

        $this->pod = new Pod(new ObjectMetadata('my-pod'), $specification);
        $this->getRepository()->create($this->pod);
    }

    /**
     * @When I create a pod with the following environment variables:
     */
    public function iCreateAPodWithTheFollowingEnvironmentVariables(TableNode $table)
    {
        $variables = [];
        foreach ($table->getHash() as $row) {
            $variables[] = new EnvironmentVariable($row['name'], $row['value']);
        }

        $specification = new PodSpecification([
            new Container('env-test', 'hello-world', $variables),
        ]);

        $this->pod = new Pod(new ObjectMetadata('my-pod'), $specification);
        $this->getRepository()->create($this->pod);
    }

    /**
     * @When I create a pod with command :command
     */
    public function iCreateAPodWithCommand($command)
    {
        $specification = new PodSpecification(
            [
                new Container('foo', 'busybox', [], [], [], Container::PULL_POLICY_IF_NOT_PRESENT, ['sh', '-c', $command]),
            ],
            [],
            PodSpecification::RESTART_POLICY_NEVER
        );

        $this->creationMicroTime = microtime(true);
        $this->pod = new Pod(new ObjectMetadata('my-pod'), $specification);
        $this->getRepository()->create($this->pod);
    }

    /**
     * @When I attach to the created pod
     */
    public function iAttachToTheCreatedPod()
    {
        $this->getRepository()->attach($this->pod, function($output) {
            echo $output;
        });
    }

    /**
     * @Then it should wait at least :seconds seconds after creation
     */
    public function itShouldWaitAtLeastSeconds($seconds)
    {
        $createdSinceSeconds = microtime(true) - $this->creationMicroTime;

        if ($createdSinceSeconds < $seconds) {
            throw new \RuntimeException(sprintf(
                'Expected to be at least %d seconds after creation but actually %lf',
                $seconds,
                $createdSinceSeconds
            ));
        }
    }

    /**
     * @Then the pod should exists
     */
    public function thePodShouldExists()
    {
        $podName = $this->pod->getMetadata()->getName();

        if (!$this->getRepository()->exists($podName)) {
            throw new \RuntimeException(sprintf(
                'The pod "%s" do not exists',
                $podName
            ));
        }
    }

    /**
     * @Then the pod should have the following environment variables:
     */
    public function thePodShouldHaveTheFollowingEnvironmentVariables(TableNode $table)
    {
        $pod = $this->getRepository()->findOneByName($this->pod->getMetadata()->getName());
        $containers = $pod->getSpecification()->getContainers();

        foreach ($containers as $container) {
            $foundVariables = [];
            foreach ($container->getEnvironmentVariables() as $variable) {
                $foundVariables[$variable->getName()] = $variable->getValue();
            }

            foreach ($table->getHash() as $expectedVariable) {
                $variableName = $expectedVariable['name'];
                if (!array_key_exists($variableName, $foundVariables)) {
                    throw new \RuntimeException(sprintf(
                        'Variable "%s" not found',
                        $expectedVariable['name']
                    ));
                }

                $foundValue = $foundVariables[$variableName];
                if ($foundValue != $expectedVariable['value']) {
                    throw new \RuntimeException(sprintf(
                        'Found value "%s" in environment variable "%s" but expecting "%s"',
                        $foundValue,
                        $variableName,
                        $expectedVariable['value']
                    ));
                }
            }
        }
    }

    /**
     * @return \Kubernetes\Client\Repository\PodRepository
     */
    private function getRepository()
    {
        $client = $this->clientContext->getClient()->getNamespaceClient(
            $this->namespaceContext->getNamespace()
        );

        return $client->getPodRepository();
    }
}
