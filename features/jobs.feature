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
  Scenario: I can attach to a container
    When I create a job "commanded" with the command "echo hello; sleep 2; echo step;"
    And I attach to the job "commanded"
    Then I should see "step" in the job output
