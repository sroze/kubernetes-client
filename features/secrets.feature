Feature:
  In order to use private resources
  I want to manage secrets

  Background:
    Given I have a namespace "foo"

  @cleanNamespace
  Scenario:
    When I create a secret "dockercfg"
    Then the secret "dockercfg" should exists
