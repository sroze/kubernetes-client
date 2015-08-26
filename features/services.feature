Feature:
  In order to have components accessible in and outside the cluster
  I want to be able to create services

  Background:
    Given I have a namespace

  @deleteService
  Scenario:
    When I create a service
    Then the service should exists

  @deleteService
  @cleanNamespace
  Scenario:
    When I create a service with some non-string values in selector
    Then the service should exists
