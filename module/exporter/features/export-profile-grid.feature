Feature: Export profile module - grid

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get profile grid
    When I send a GET request to "/api/v1/en/export-profile"
    Then the response status code should be 200
