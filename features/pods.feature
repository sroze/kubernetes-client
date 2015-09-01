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

  @cleanNamespace
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
