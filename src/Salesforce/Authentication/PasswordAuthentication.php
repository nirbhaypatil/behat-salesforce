<?php

namespace Salesforce\Authentication;

use Salesforce\Exception\SalesforceAuthentication;

use GuzzleHttp\Client;

/**
 *
 */
class PasswordAuthentication {
  protected $client;
  protected $endPoint;
  protected $options;
  protected $access_token;
  protected $instance_url;

  /**
   * Initializes endpoint,options for QA and QT.
   */
  public function __construct($endpoint, $options) {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $this->setEndpoint($endpoint);
    $this->options = $options;
  }

  /**
   * Authenticate and store response in session.
   */
  public function authenticate() {
    $client = new Client();
    // https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/intro_understanding_authentication.htm
    $request = $client->request('post', $this->endPoint . 'services/oauth2/token', ['form_params' => $this->options]);
    $response = json_decode($request->getBody(), TRUE);
    if ($response) {
      $this->access_token = $response['access_token'];
      $this->instance_url = $response['instance_url'];
      $_SESSION['salesforce'] = $response;
    }
    else {
      throw new SalesforceAuthentication($request->getBody());
    }
  }

  /**
   * Sets endpoint.
   *
   * @param string $endpoint
   *   Salesforce url like https://cs1.salesforce.com/.
   */
  public function setEndpoint($endPoint) {
    $this->endPoint = $endPoint;
  }

  /**
   * Once authenticated, every request needs access_token value in header.
   *
   * @return string
   *   Access token required for salesforce connection.
   */
  public function getAccessToken() {
    return $this->access_token;
  }

  /**
   * Get instance url.
   *
   * @return string
   *   e.g. http://cs1.salesforce.com/
   */
  public function getInstanceUrl() {
    return $this->instance_url;
  }

}
