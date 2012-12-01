Feature: Hello World Feature
  As a User
  I want to make sure I can see the Extbase API

  @base
  Scenario: API for Extbase current is live
    Given I am on "extbase/current/"
    Then the response status code should be 200

  @base
  Scenario: API for Extbase master is live
    Given I am on "extbase/master/"
    Then the response status code should be 200

  @base
  Scenario: API for Extbase 4.7 is live
    Given I am on "extbase/47/"
    Then the response status code should be 200

  @base
  Scenario: API for Extbase 6.0 is live
    Given I am on "extbase/60/"
    Then the response status code should be 200