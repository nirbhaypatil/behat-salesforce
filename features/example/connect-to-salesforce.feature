@salesforce
Feature: Connect to Salesforce
  In order to verify data from application to salesforce
  As a anonymous user
  I need to connect to salesforce and see orders

  @order-verification
  Scenario: Verify personal user reaches to salesforce
    Given I connect to salesforce
    Then order 1234 should be available in salesforce
