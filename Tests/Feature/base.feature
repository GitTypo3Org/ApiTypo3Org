Feature: Hello World Feature
  As a User
  I want to make sure I can see the necessary things

  @base
  Scenario: API for Extbase current is live
    Given I am on "archives"
    Then the response status code should be 200
