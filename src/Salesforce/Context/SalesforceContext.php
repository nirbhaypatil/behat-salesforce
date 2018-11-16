<?php

namespace Salesforce\Context;

use Salesforce\Util\CRUD;
use Behat\Behat\Context\Context;
use Salesforce\Authentication\PasswordAuthentication;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use bootstrap\Utility\File;
use Behat\Gherkin\Node\TableNode;

/**
 * Provides steps to connect , fetch data from salesforce.
 */
class SalesforceContext implements Context {

  /**
   * Initializes context.
   *
   * @param array $parameters
   *   Context parameters (set them up through behat.yml)
   */
  public function __construct(array $parameters) {
    if (isset($parameters['options'])) {
      $this->options = $parameters['options'];
    }
    if (isset($parameters['end point'])) {
      $this->endpoint = $parameters['end point'];
    }
  }

  /**
   * @BeforeScenario
   */
  public function gatherContexts(BeforeScenarioScope $scope) {
    $environment = $scope->getEnvironment();
  }

  /**
   * @When I connect to salesforce
   */
  public function iConnectToSalesforce() {
    $salesforce = new PasswordAuthentication($this->endpoint, $this->options);
    $salesforce->authenticate();
  }

  /**
   * @Then order :order should be available in salesforce
   */
  public function iCheckOrderIsPresentInSalesforce($expected_order) {
    $crud = new CRUD();
    $query = $crud->buildQuery("SELECT order FROM Orders");
    $response = $crud->query($query);

    $orders = $response->getResults();
    foreach ($orders as $order) {
      if (in_array($expected_order, $order)) {
        return TRUE;
      }
    }
    throw new \Exception($expected_order . " expected order not found in salesforce");
  }

}
