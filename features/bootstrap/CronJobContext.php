<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Kubernetes\Client\Model\Container;
use Kubernetes\Client\Model\ObjectMetadata;
use Kubernetes\Client\Model\CronJob;
use Kubernetes\Client\Model\JobSpecification;
use Kubernetes\Client\Model\PodTemplateSpecification;
use Kubernetes\Client\Model\PodSpecification;
use Kubernetes\Client\Model\CronJobSpecification;
use Kubernetes\Client\Model\JobTemplateSpecification;

class CronJobContext implements Context
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
     * @var CronJob
     */
    private $cronJob;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->clientContext = $scope->getEnvironment()->getContext('ClientContext');
        $this->namespaceContext = $scope->getEnvironment()->getContext('NamespaceContext');
    }

    /**
     * @AfterScenario @deleteCronJob
     */
    public function deleteCronJob()
    {
        if (null !== $this->cronJob) {
            $repository = $this->getRepository();
            $repository->delete($this->cronJob);

            do {
                $exists = $repository->exists($this->cronJob->getMetadata()->getName());
            } while ($this->clientContext->isIntegration() && $exists && sleep(1) == 0);
        }
    }

    /**
     * @When I create a cron job :name with schedule :schedule
     */
    public function iCreateACronJobNamed($name, $schedule)
    {
        $specification = new JobSpecification(new PodTemplateSpecification(new ObjectMetadata($name), new PodSpecification([
            new Container($name, 'hello-world'),
        ])));

        $jobTemplateSpec = new JobTemplateSpecification(new ObjectMetadata($name), $specification);
        $specification = new CronJobSpecification($schedule, $jobTemplateSpec);

        $this->cronJob = new CronJob(new ObjectMetadata($name), $specification);
        $this->getRepository()->create($this->cronJob);
    }

    /**
     * @Then the cron job :name should exists
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
     * @Then the cron job :name should be scheduled :schedule
     */
    public function theJobShouldBeScheduled($name, $schedule)
    {
        $this->cronJob = $this->getRepository()->findOneByName($name);
        if ($this->cronJob->getSpecification()->getSchedule() !== $schedule) {
            throw new \RuntimeException(sprintf(
                'The cron job "%s" should be scheduled with: "%s" got "%s"',
                $name,
                $schedule,
                $this->cronJob->getSpecification()->getSchedule()
            ));
        }
    }

    /**
     * @return \Kubernetes\Client\Repository\CronJobRepository
     */
    private function getRepository()
    {
        $client = $this->clientContext->getClient()->getNamespaceClient(
            $this->namespaceContext->getNamespace()
        );

        return $client->getCronJobRepository();
    }
}
