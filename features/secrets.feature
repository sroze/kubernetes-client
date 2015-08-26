Feature:
  In order to use private resources
  I want to manage secrets

  Background:
    Given I have a namespace

  Scenario:
    When I create a secret
    Then the secret should exists
