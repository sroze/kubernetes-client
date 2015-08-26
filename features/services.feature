Feature:
  In order to have components accessible in and outside the cluster
  I want to be able to create services

  Background:
    Given I have a namespace

  @cleanNamespace
  Scenario:
    When I create a service
    Then the service should exists
