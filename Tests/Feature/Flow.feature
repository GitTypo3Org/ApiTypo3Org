Feature: Hello World Feature
  In order to ensure the basic feature of the application
  As a User
  I want to make sure I can see exported data

#  @todo: redirection status code does not work
#  @base
#  Scenario: Flow is redirecting
#    Given I am on "flow/"
#    Then the response status code should be 301

  @base
  Scenario: API for Flow current is live
    Given I am on "flow/current/"
    Then the response status code should be 200

  @base
  Scenario: API for Flow master is live
    Given I am on "flow/master/"
    Then the response status code should be 200

  @base
  Scenario: API for Flow 1.0 is live
    Given I am on "flow/10/"
    Then the response status code should be 200
