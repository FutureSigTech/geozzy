<?php

abstract class ExplorerController {

  public $cacheQuery = true; // false, true or time in seconds

  abstract public function serveMinimal( );

  abstract public function servePartial( );

  public function __construct() {
    // error_log( 'ExplorerController::__construct' );

    $cache = Cogumelo::getSetupValue('cache:ExplorerController:default');
    if( $cache !== null ) {
      $this->cacheQuery = $cache;
    }
  }

  public function getParamExtended( $paramName, $urlParamsList ) {
    $resId = null;

    if( isset( $urlParamsList[ $paramName ] ) ) {
      $resId = (int) $urlParamsList[ $paramName ];
    }
    elseif( isset( $_GET[ $paramName ] ) ) {
      $resId = (int) $_GET[ $paramName ];
    }
    elseif( isset( $_POST[ $paramName ] ) ) {
      $resId = (int) $_POST[ $paramName ];
    }

    return $resId;
  }
}
