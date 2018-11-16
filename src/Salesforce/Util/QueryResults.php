<?php

namespace Salesforce\Util;

/**
 * Retrieving the Remaining SOQL Query results.
 */
class QueryResults {
  /**
   * @var array*/
  protected $results;
  protected $totalSize;
  protected $isDone;

  /**
   *
   */
  public function __construct(array $results, $totalSize, $isDone) {
    $this->results = array_values($results);
    $this->totalSize = $totalSize;
    $this->isDone = $isDone;
  }

  /**
   * The Query API output, converted from JSON to an associative array.
   *
   * @return array
   */
  public function getResults() {
    return $this->results;
  }

  /**
   * Returns the total number of records that the query matched.
   *
   * @return int
   */
  public function getTotalSize() {
    return $this->totalSize;
  }

  /**
   * Returns whether or not there are more query results.
   *
   * @return bool
   */
  public function isDone() {
    return $this->isDone;
  }

}
