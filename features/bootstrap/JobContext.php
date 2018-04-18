<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Kubernetes\Client\Model\Container;
use Kubernetes\Client\Model\EnvironmentVariable;
use Kubernetes\Client\Model\ObjectMetadata;
use Kubernetes\Client\Model\Job;
use Kubernetes\Client\Model\JobSpecification;
use Kubernetes\Client\Model\PodTemplateSpecification;
use Kubernetes\Client\Model\PodSpecification;

class JobContext implements Context
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
     * @var Job
     */
    private $job;

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
     * @AfterScenario @deleteJob
     */
    public function deleteJob()
    {
        if (null !== $this->job) {
            $repository = $this->getRepository();
            $repository->delete($this->job);

            do {
                $exists = $repository->exists($this->job->getMetadata()->getName());
            } while ($this->clientContext->isIntegration() && $exists && sleep(1) == 0);
        }
    }

    /**
     * @When I create a job :name
     */
    public function iCreateAJobNamed($name)
    {
        $specification = new JobSpecification(new PodTemplateSpecification(new ObjectMetadata($name), new PodSpecification([
            new Container($name, 'hello-world'),
        ])));

        $this->job = new Job(new ObjectMetadata($name), $specification);
        $this->getRepository()->create($this->job);
    }

    /**
     * @When I create a job :name with the following environment variables:
     */
    public function iCreateAJobWithTheFollowingEnvironmentVariables($name, TableNode $table)
    {
        $variables = [];
        foreach ($table->getHash() as $row) {
            $variables[] = new EnvironmentVariable($row['name'], $row['value']);
        }

        $specification = new JobSpecification(new PodTemplateSpecification(new ObjectMetadata($name), new PodSpecification([
            new Container($name, 'hello-world', $variables),
        ])));

        $this->job = new Job(new ObjectMetadata($name), $specification);
        $this->getRepository()->create($this->job);
    }

    /**
     * @When I create a job :name with the command :command
     */
    public function iCreateAJobWithCommand($name, $command)
    {
        $specification = new JobSpecification(new PodTemplateSpecification(new ObjectMetadata($name), new PodSpecification([
                new Container($name, 'busybox', [], [], [], Container::PULL_POLICY_IF_NOT_PRESENT, ['sh', '-c', $command]),
            ],
            [],
            PodSpecification::RESTART_POLICY_NEVER)));

        $this->creationMicroTime = microtime(true);
        $this->job = new Job(new ObjectMetadata($name), $specification);
        $this->getRepository()->create($this->job);
    }

    /**
     * @When I create a job :name with the command:
     */
    public function iCreateAJobWithCommands($name, TableNode $table)
    {
        $command = [];
        foreach ($table->getHash() as $row) {
            $command[] = $row['command'];
        }

        $specification = new JobSpecification(new PodTemplateSpecification(new ObjectMetadata($name), new PodSpecification([
            new Container($name, 'busybox', [], [], [], Container::PULL_POLICY_IF_NOT_PRESENT, array_merge(['sh', '-c'], $command)),
        ],
            [],
            PodSpecification::RESTART_POLICY_NEVER)));

        $this->creationMicroTime = microtime(true);
        $this->job = new Job(new ObjectMetadata($name), $specification);
        $this->getRepository()->create($this->job);
    }

    /**
     * @When I create a job :name with the arguments:
     */
    public function iCreateAJobWithArgs($name, TableNode $table)
    {
        $args = [];
        foreach ($table->getHash() as $row) {
            $args[] = $row['arg'];
        }

        $specification = new JobSpecification(new PodTemplateSpecification(new ObjectMetadata($name), new PodSpecification([
            new Container($name, 'busybox', [], [], [], Container::PULL_POLICY_IF_NOT_PRESENT, null, $args),
        ],
            [],
            PodSpecification::RESTART_POLICY_NEVER)));

        $this->creationMicroTime = microtime(true);
        $this->job = new Job(new ObjectMetadata($name), $specification);
        $this->getRepository()->create($this->job);
    }

    /**
     * @Then the job :name should exists
     */
    public function theJobShouldExists($name)
    {
        if (!$this->getRepository()->exists($name)) {
            throw new \RuntimeException(sprintf(
                'The job "%s" do not exists',
                $name
            ));
        }
    }

    /**
     * @Then the job :name should have the following environment variables:
     */
    public function theJobShouldHaveTheFollowingEnvironmentVariables($name, TableNode $table)
    {
        $job = $this->getRepository()->findOneByName($name);
        $containers = $job->getSpecification()->getTemplate()->getPodSpecification()->getContainers();

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
     * @Then the job :name should have the following command:
     */
    public function theJobShouldHaveTheFollowingCommand($name, TableNode $table)
    {
        $job = $this->getRepository()->findOneByName($name);
        $containers = $job->getSpecification()->getTemplate()->getPodSpecification()->getContainers();

        foreach ($containers as $container) {
            $foundCommand = [];
            foreach ($container->getCommand() as $command) {
                $foundCommand[] = $command;
            }

            foreach ($table->getHash() as $expectedCommand) {
                if (!in_array($expectedCommand['command'], $foundCommand)) {
                    throw new \RuntimeException(sprintf(
                        'Command "%s" not found',
                        $expectedCommand['command']
                    ));
                }
            }
        }
    }

    /**
     * @Then the job :name should have the following arguments:
     */
    public function theJobShouldHaveTheFollowingArguments($name, TableNode $table)
    {
        $job = $this->getRepository()->findOneByName($name);
        $containers = $job->getSpecification()->getTemplate()->getPodSpecification()->getContainers();

        foreach ($containers as $container) {
            $foundArgs = [];
            foreach ($container->getArgs() as $arg) {
                $foundArgs[] = $arg;
            }

            foreach ($table->getHash() as $expectedArg) {
                if (!in_array($expectedArg['arg'], $foundArgs)) {
                    throw new \RuntimeException(sprintf(
                        'Argument "%s" not found',
                        $expectedArg['arg']
                    ));
                }
            }
        }
    }

    /**
     * @return \Kubernetes\Client\Repository\JobRepository
     */
    private function getRepository()
    {
        $client = $this->clientContext->getClient()->getNamespaceClient(
            $this->namespaceContext->getNamespace()
        );

        return $client->getJobRepository();
    }
}
