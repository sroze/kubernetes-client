Feature:
  In order to have running containers
  As a developer
  I want to be able to manage pods

  Background:
    Given I have a namespace "pods-heaven"
    And the namespace "pods-heaven" is ready

  @deletePod
  Scenario: I can create a pod
    When I create a pod "my-pod"
    Then the pod "my-pod" should exists

  @deletePod
  Scenario: I can create a pod with environment variables
    When I create a pod "env-test" with the following environment variables:
      | name | value |
      | foo  | bar   |
      | baz  | foo   |
    Then the pod "env-test" should exists
    And the pod "env-test" should have the following environment variables:
      | name | value |
      | foo  | bar   |
      | baz  | foo   |

  @cleanNamespace
  Scenario: I can attach to a container
    When I create a pod "commanded" with the command "echo hello; sleep 2; echo step;"
    And I attach to the pod "commanded"
    Then I should see "step" in the output

  @cleanNamespace
  Scenario: I can stream the output of a container
    When I create a pod "commanded" with the command "echo hello; sleep 2; echo step;"
    And I start streaming the output of the pod "commanded"
    Then I should see "step" in the output
