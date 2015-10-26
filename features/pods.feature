Feature:
  In order to have running containers
  As a developer
  I want to be able to manage pods

  Background:
    Given I have a namespace

  @deletePod
  Scenario: I can create a pod
    When I create a pod
    Then the pod should exists

  @deletePod
  Scenario: I can create a pod with environment variables
    When I create a pod with the following environment variables:
      | name | value |
      | foo  | bar   |
      | baz  | foo   |
    Then the pod should exists
    And the pod should have the following environment variables:
      | name | value |
      | foo  | bar   |
      | baz  | foo   |

  @cleanNamespace
  Scenario: I can attach to a container
    When I create a pod with command "echo hello; sleep 2; echo step; sleep 2"
    And I attach to the created pod
    Then it should wait at least 4 seconds after creation
