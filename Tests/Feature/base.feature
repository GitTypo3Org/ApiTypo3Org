Feature: Hello World Feature
  As a User
  I want to make sure I can see the necessary things

  @base
  Scenario: API landing page is live
    Given I am on "archives"
    Then the response status code should be 200
