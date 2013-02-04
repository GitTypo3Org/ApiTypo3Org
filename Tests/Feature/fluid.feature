Feature: Hello World Feature
  As a User
  I want to make sure I can see the Fluid API

  @base
  Scenario: API for Fluid current is live
    Given I am on "fluid/current/"
    Then the response status code should be 200

  @base
  Scenario: API for Fluid master is live
    Given I am on "fluid/master/"
    Then the response status code should be 200

  @base
  Scenario: API for Fluid 4.7 is live
    Given I am on "fluid/47/"
    Then the response status code should be 200

  @base
  Scenario: API for Fluid 6.0 is live
    Given I am on "fluid/60/"
    Then the response status code should be 200