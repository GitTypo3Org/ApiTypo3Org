Feature: Hello World Feature
  As a User
  I want to make sure I can see the TYPO3 CMS API

#  @todo: redirection status code does not work
#  @base
#  Scenario: redirection to main page
#    Given I am on "http://typo3.org/api/typo3/"
#    Then the response status code should be 301

  @base
  Scenario: API for TYPO3 CMS 4.5 is live
    Given I am on "typo3cms/45/html/"
    Then the response status code should be 200

  @base
  Scenario: API for TYPO3 CMS 4.6 is live
    Given I am on "typo3cms/46/html/"
    Then the response status code should be 200

  @base
  Scenario: API for TYPO3 CMS 4.7 is live
    Given I am on "typo3cms/47/html/"
    Then the response status code should be 200

  @base
  Scenario: API for TYPO3 CMS 6.0 is live
    Given I am on "typo3cms/60/html/"
    Then the response status code should be 200

  @base
  Scenario: API for TYPO3 CMS current is live
    Given I am on "typo3cms/current/html/"
    Then the response status code should be 200

  @base
  Scenario: API for Extbase current is live
    Given I am on "extbase/current/"
    Then the response status code should be 200

  @base
  Scenario: API for Flow current is live
    Given I am on "extbase/current/"
    Then the response status code should be 200
