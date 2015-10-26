Feature:
  In order to request persistent storage
  I need to be able to manage persistent volumes and volume claims

  Background:
    Given I have a namespace

  Scenario: I can create a volume claim
    When I created a persistent volume claim
    Then the persistent volume claim should exists

  @cleanNamespace
  Scenario: I can mount a persistent volume from its claim
    Given I have a persistent volume claim "foo"
    When I create a pod with the volume claim "foo" mounted at "/foo"
    Then the pod should exists
