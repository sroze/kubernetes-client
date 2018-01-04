Feature:
  In order to have running containers
  As a developer
  I want to be able to manage jobs

  Background:
    Given I have a namespace "pods-heaven"
    And the namespace "pods-heaven" is ready

  @deletePod
  Scenario: I can create a job
    When I create a job "my-job"
    Then the job "my-job" should exists

  @deletePod
  Scenario: I can create a job with environment variables
    When I create a job "env-test" with the following environment variables:
      | name | value |
      | foo  | bar   |
      | baz  | foo   |
    Then the job "env-test" should exists
    And the job "env-test" should have the following environment variables:
      | name | value |
      | foo  | bar   |
      | baz  | foo   |

  @cleanNamespace
  Scenario: I can create a job with a list of commands
    When I create a job "commanded" with the command:
      | command |
      | foo     |
      | --b     |
      | bar     |
    Then the job "commanded" should exists
    And the job "commanded" should have the following command:
      | command |
      | foo     |
      | --b     |
      | bar     |

  @cleanNamespace
  Scenario: I can create a job with a list of arguments
    When I create a job "args" with the arguments:
      | arg |
      | --f |
      | foo |
      | --b |
      | bar |
    Then the job "args" should exists
    And the job "args" should have the following arguments:
      | arg |
      | --f |
      | foo |
      | --b |
      | bar |
