<?php

namespace Salesforce\Authentication;

/**
 *
 */
interface AuthenticationInterface {

  /**
   *
   */
  public function getAccessToken();

  /**
   *
   */
  public function getInstanceUrl();

}
