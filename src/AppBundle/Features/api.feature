Feature: API calling
  In order to show my API works
  As a developer
  I need to be able to automatically test it

  Scenario: Retrieve a token
    Given I am a user with username "admin" and password "reallysafepassword"
    And I add "username" header equal to "admin"
    And I add "password" header equal to "reallysafepassword"
    When I send a "POST" request to "/api/token"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "token" should exist

  Scenario: Get a useful error on authentication failure
    Given I am a user with username "admin" and password "reallysafepassword"
    And I add "username" header equal to "admin"
    And I add "password" header equal to "waybetterpassword"
    When I send a "POST" request to "/api/token"
    Then the response status code should be 401
    And the response should be in JSON
    And the JSON node "error[0].code" should be equal to 401
    And the JSON node "error[0].message" should be equal to "Password invalid"

  Scenario: Add a car
    Given I am a user with username "admin" and password "reallysafepassword"
    And I have a valid token
    And I add that token to my request as Authorization header
    When I send a "POST" request to "/car" with body:
    """
    {
      "brand": "Ford",
      "name": "Mustang",
      "year": 1972
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "brand" should be equal to "Ford"
    And the JSON node "name" should be equal to "Mustang"
    And the JSON node "year" should be equal to 1972

  Scenario: Update a car
    Given I have a car with brand "Ford" and name "Mustang" and year 1972
    Given I am a user with username "admin" and password "reallysafepassword"
    And I have a valid token
    And I add that token to my request as Authorization header
    When I send a "PATCH" request to "/car/1" with body:
    """
    {
      "name": "Sierra"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "brand" should be equal to "Ford"
    And the JSON node "name" should be equal to "Sierra"
    And the JSON node "year" should be equal to 1972

  Scenario: Retrieve all cars
    Given I have a car with brand "Ford" and name "Mustang" and year 1972
    And I have a car with brand "Toyota" and name "Corolla" and year 1983
    Given I am a user with username "admin" and password "reallysafepassword"
    And I have a valid token
    And I add that token to my request as Authorization header
    When I send a "GET" request to "/car"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "root[0].brand" should be equal to "Ford"
    And the JSON node "root[0].name" should be equal to "Mustang"
    And the JSON node "root[0].year" should be equal to 1972
    And the JSON node "root[1].brand" should be equal to "Toyota"
    And the JSON node "root[1].name" should be equal to "Corolla"
    And the JSON node "root[1].year" should be equal to 1983

  Scenario: Retrieve one car
    Given I have a car with brand "Ford" and name "Mustang" and year 1972
    Given I am a user with username "admin" and password "reallysafepassword"
    And I have a valid token
    And I add that token to my request as Authorization header
    When I send a "GET" request to "/car/1"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "brand" should be equal to "Ford"
    And the JSON node "name" should be equal to "Mustang"
    And the JSON node "year" should be equal to 1972

  Scenario: Delete a car
    Given I have a car with brand "Ford" and name "Mustang" and year 1972
    Given I am a user with username "admin" and password "reallysafepassword"
    And I have a valid token
    And I add that token to my request as Authorization header
    When I send a "DELETE" request to "/car/1"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "message" should be equal to "Car deleted"

  Scenario: See a useful message when sending invalid data
    Given I am a user with username "admin" and password "reallysafepassword"
    And I have a valid token
    And I add that token to my request as Authorization header
    Given I send a "GET" request to "/car/asdf"
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON node "error[0].code" should be equal to 400
    And the JSON node "error[0].message" should be equal to "ID should be an integer"

    When I send a "POST" request to "/car" with body:
    """

    """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON node "error[0].code" should be equal to 400
    And the JSON node "error[0].message" should be equal to "No body given"
