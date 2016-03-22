Feature:
  In order to create isolated environments
  I want to manage the Kubernetes namespaces

  Scenario: Create a namespace
    When I send a request creation for the namespace "foo"
    Then the namespace "foo" should exists

  Scenario: List a namespace
    Given I have a namespace "foo"
    When I get the list of namespaces
    Then the namespace "foo" should be in the list

  Scenario: Delete a namespace
    Given I have a namespace "foo"
    When I delete the namespace "foo"
    Then the namespace "foo" should not exists or be terminating

  @cleanNamespace
  Scenario: List namespace with matching label
    Given I have a namespace "my-bar-namespace" with the labels "bar=baz"
    When I get the list of namespaces matching the labels "bar=baz"
    Then the namespace "my-bar-namespace" should be in the list

  @cleanNamespace
  Scenario: List namespace with non-matching label
    Given I have a namespace "my-baz-namespace" with the labels "bar=baz"
    When I get the list of namespaces matching the labels "bar=bar"
    Then the namespace "my-baz-namespace" should not be in the list
