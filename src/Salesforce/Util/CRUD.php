<?php

namespace Salesforce\Util;

use GuzzleHttp\Client;

/**
 *
 */
class CRUD {

  protected $instance_url;
  protected $access_token;

  /**
   * Initializes token and instance url.
   */
  public function __construct() {
    if (!isset($_SESSION) and !isset($_SESSION['salesforce'])) {
      throw new \Exception('Access Denied', 403);
    }
    $this->instance_url = $_SESSION['salesforce']['instance_url'];
    $this->access_token = $_SESSION['salesforce']['access_token'];
  }

  /**
   * Query Salesforce object using URI.
   */
  public function query($query) {
    $url = "$this->instance_url/services/data/v42.0/query";

    $client = new Client();
    $response = $client->request('GET', $url, [
      'headers' => [
        'Authorization' => "OAuth $this->access_token",
      ],
      'query' => [
        'q' => $query,
      ],
    ]);

    $response = json_decode($response->getBody(), TRUE);
    if (!isset($response['totalSize']) || empty($response['totalSize'])) {
      $message = 'No results found';
      throw new \Exception($message);
    }
    return new QueryResults($response['records'], $response['totalSize'], $response['done']);
  }

  /**
   *
   */
  public function create($object, array $data) {
    $url = "$this->instance_url/services/data/v42.0/sobjects/$object/";

    $client = new Client();

    $request = $client->request('POST', $url, [
      'headers' => [
        'Authorization' => "OAuth $this->access_token",
        'Content-type' => 'application/json',
      ],
      'json' => $data,
    ]);

    $status = $request->getStatusCode();

    if ($status != 201) {
      die("Error: call to URL $url failed with status $status, response: " . $request->getReasonPhrase());
    }

    $response = json_decode($request->getBody(), TRUE);
    $id = $response["id"];

    return $id;

  }

  /**
   *
   */
  public function update($object, $id, array $data) {
    $url = "$this->instance_url/services/data/v39.0/sobjects/$object/$id";

    $client = new Client();

    $request = $client->request('PATCH', $url, [
      'headers' => [
        'Authorization' => "OAuth $this->access_token",
        'Content-type' => 'application/json',
      ],
      'json' => $data,
    ]);

    $status = $request->getStatusCode();

    if ($status != 204) {
      die("Error: call to URL $url failed with status $status, response: " . $request->getReasonPhrase());
    }

    return $status;
  }

  /**
   *
   */
  public function delete($object, $id) {
    $url = "$this->instance_url/services/data/v39.0/sobjects/$object/$id";

    $client = new Client();
    $request = $client->request('DELETE', $url, [
      'headers' => [
        'Authorization' => "OAuth $this->access_token",
      ],
    ]);

    $status = $request->getStatusCode();

    if ($status != 204) {
      die("Error: call to URL $url failed with status $status, response: " . $request->getReasonPhrase());
    }

    return TRUE;
  }

  /**
   * Build SOQL string using the paramters.
   *
   * @param string $queryToRun
   *   Like select name from account where.
   * @param array $parameters
   *   Where clause value.
   *
   * @return string
   *   Final SQL formed for use.
   */
  public function buildQuery($queryToRun, $parameters = array()) {

    if (!empty($parameters)) {
      $queryToRun = $this->bindParameters($queryToRun, $parameters);
    }
    return "{$queryToRun}";
  }

  /**
   * Binds parameteres in SQL.
   *
   * @param string $queryString
   *   SQL to use fetch record.
   * @param array $parameters
   *   Array with values for Where clause.
   *
   * @return string
   *   SQL with parameter.
   */
  protected function bindParameters($queryString, $parameters) {
    $paramKeys = array_keys($parameters);
    $isNumericIndexes = array_reduce(array_map('is_int', $paramKeys), function ($carry, $item) {
      return $carry && $item;
    }, TRUE);
    if ($isNumericIndexes) {
      $searchArray = array_fill(0, count($paramKeys), '?');
      $replaceArray = array_values($parameters);
    }
    else {
      // NOTE: krsort here will prevent the scenario of a replacement of
      // array('foo' => 1, 'foobar' => 2) on string "Hi :foobar"
      // resulting in "Hi 1bar".
      krsort($parameters);
      $searchArray = array_map(function ($string) {
        return ':' . $string;
      }, array_keys($parameters));
      $replaceArray = array_values($parameters);
    }

    $replaceArray = $this->addQuotesToStringReplacements($replaceArray);
    $replaceArray = $this->replaceBooleansWithStringLiterals($replaceArray);
    return str_replace($searchArray, $replaceArray, $queryString);
  }

  /**
   * Adds single quotes around where clause value.
   */
  public function addQuotesToStringReplacements($replacements) {
    foreach ($replacements as $key => $val) {
      if (is_string($val) && !$this->isSalesforceDateFormat($val)) {
        $val = str_replace("'", "\'", $val);
        $replacements[$key] = "'{$val}'";
      }
    }
    return $replacements;
  }

  /**
   *
   */
  protected function isSalesforceDateFormat($string) {
    return preg_match('/\d+[-]\d+[-]\d+[T]\d+[:]\d+[:]\d+[Z]/', $string) === 1;
  }

  /**
   *
   */
  protected function replaceBooleansWithStringLiterals($replacements) {

    return array_map(function ($val) {
      if (!is_bool($val)) {
        return $val;
      }
      $retval = $val ? 'true' : 'false';
      return $retval;
    }, $replacements);
  }

}
