Feature:
  In order to have running containers
  As a developer
  I want to be able to manage cron jobs

  Background:
    Given I have a namespace "pods-heaven"
    And the namespace "pods-heaven" is ready

  @deleteCronJob
  Scenario: I can create a cron job
    When I create a cron job "my-cron-job" with schedule "@hourly"
    Then the cron job "my-cron-job" should exists
    And the cron job "my-cron-job" should be scheduled "@hourly"
