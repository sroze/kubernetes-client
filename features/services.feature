Feature:
  In order to have components accessible in and outside the cluster
  I want to be able to create services

  Background:
    Given I have a namespace "foo"

  @deleteService
  Scenario:
    When I create a service "plop"
    Then the service "plop" should exists

  @deleteService
  @cleanNamespace
  Scenario:
    When I create a service "plop" with some non-string values in selector
    Then the service "plop" should exists
