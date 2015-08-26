Feature:
  In order to create isolated environments
  I want to manage the Kubernetes namespaces

  Scenario:
    When I send a request creation for a namespace
    Then the namespace should exists

  Scenario:
    Given I have a namespace
    When I get the list of namespaces
    Then the namespace should be in the list

  Scenario:
    Given I have a namespace
    When I delete the namespace
    Then the namespace should not exists
