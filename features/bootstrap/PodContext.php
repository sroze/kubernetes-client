<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Kubernetes\Client\Model\Container;
use Kubernetes\Client\Model\EnvironmentVariable;
use Kubernetes\Client\Model\ObjectMetadata;
use Kubernetes\Client\Model\Pod;
use Kubernetes\Client\Model\PodSpecification;
use Kubernetes\Client\Model\Volume;
use Kubernetes\Client\Model\VolumeMount;

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
     * @var string
     */
    private $attachResult;

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
            $repository = $this->getRepository();
            $repository->delete($this->pod);

            do {
                $exists = $repository->exists($this->pod->getMetadata()->getName());
            } while ($this->clientContext->isIntegration() && $exists && sleep(1) == 0);
        }
    }

    /**
     * @When I create a pod :name
     */
    public function iCreateAPodNamed($name)
    {
        $specification = new PodSpecification([
            new Container($name, 'hello-world'),
        ]);

        $this->pod = new Pod(new ObjectMetadata($name), $specification);
        $this->getRepository()->create($this->pod);
    }

    /**
     * @When I create a pod :name with the following environment variables:
     */
    public function iCreateAPodWithTheFollowingEnvironmentVariables($name, TableNode $table)
    {
        $variables = [];
        foreach ($table->getHash() as $row) {
            $variables[] = new EnvironmentVariable($row['name'], $row['value']);
        }

        $specification = new PodSpecification([
            new Container($name, 'hello-world', $variables),
        ]);

        $this->pod = new Pod(new ObjectMetadata($name), $specification);
        $this->getRepository()->create($this->pod);
    }

    /**
     * @When I create a pod :name with the command :command
     */
    public function iCreateAPodWithCommand($name, $command)
    {
        $specification = new PodSpecification(
            [
                new Container($name, 'busybox', [], [], [], Container::PULL_POLICY_IF_NOT_PRESENT, ['sh', '-c', $command]),
            ],
            [],
            PodSpecification::RESTART_POLICY_NEVER
        );

        $this->creationMicroTime = microtime(true);
        $this->pod = new Pod(new ObjectMetadata($name), $specification);
        $this->getRepository()->create($this->pod);
    }

    /**
     * @When I attach to the pod :name
     */
    public function iAttachToTheCreatedPod($name)
    {
        $this->getRepository()->attach($this->getRepository()->findOneByName($name), function($output) {
            $this->attachResult = $output;
        });
    }

    /**
     * @Then I should see :text in the output
     */
    public function iShouldSeeInTheOutput($text)
    {
        if (strpos($this->attachResult, $text) === false) {
            echo $this->attachResult;

            throw new \RuntimeException('Text not found');
        }
    }

    /**
     * @When I create a pod :name with the volume claim :claimName mounted at :mountPath
     */
    public function iCreateAPodWithTheVolumeClaimMountedAt($name, $claimName, $mountPath)
    {
        $volume = new Volume($claimName);
        $volume->setPersistentVolumeClaim(new Volume\PersistentVolumeClaimSource($claimName));

        $specification = new PodSpecification(
            [
                new Container($name, 'busybox', [], [], [
                    new VolumeMount($claimName, $mountPath)
                ]),
            ],
            [
                $volume
            ],
            PodSpecification::RESTART_POLICY_NEVER
        );

        $this->creationMicroTime = microtime(true);
        $this->pod = new Pod(new ObjectMetadata($name), $specification);
        $this->getRepository()->create($this->pod);
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
     * @Then the pod :name should exists
     */
    public function thePodShouldExists($name)
    {
        if (!$this->getRepository()->exists($name)) {
            throw new \RuntimeException(sprintf(
                'The pod "%s" do not exists',
                $name
            ));
        }
    }

    /**
     * @Then the pod :name should have the following environment variables:
     */
    public function thePodShouldHaveTheFollowingEnvironmentVariables($name, TableNode $table)
    {
        $pod = $this->getRepository()->findOneByName($name);
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
