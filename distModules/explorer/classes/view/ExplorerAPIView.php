<?php

Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class ExplorerAPIView extends View
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    return( true );
  }


  public function explorerJson() {
    header('Content-type: application/json');
    ?>
    {
      "resourcePath": "/explorer.json",
      "basePath": "/api",
      "apis": [
        {
          "operations": [
            {
              "errorResponses": [
                {
                  "reason": "The explorer",
                  "code": 200
                },
                {
                  "reason": "Explorer not found",
                  "code": 404
                }
              ],
              "httpMethod": "POST",
              "nickname": "explorer",
              "parameters": [
                {
                  "name": "explorer",
                  "description": "explorer name",
                  "dataType": "string",
                  "paramType": "path",
                  "defaultValue": "default",
                  "required": false
                },
                {
                  "name": "request",
                  "description": "( minimal | partial )",
                  "dataType": "string",
                  "paramType": "path",
                  "defaultValue": "minimal",
                  "required": true
                },
                {
                  "name": "updatedfrom",
                  "description": "updated from (UTC timestamp)",
                  "dataType": "int",
                  "paramType": "form",
                  "defaultValue": "false",
                  "required": false
                },
                {
                  "name": "ids",
                  "description": "ids",
                  "type": "array",
                  "items": {
                    "type": "integer"
                  },
                  "paramType": "form",
                  "required": false
                }
              ],
              "summary": "Fetches explorer data"
            }
          ],
          "path": "/explorer/explorer/{explorer}/request/{request}",
          "description": ""
        }
      ]
    }
    <?php
  }

  public function explorerListJson() {
    header('Content-type: application/json');
    ?>
    {
      "resourcePath": "/explorerList.json",
      "basePath": "/api",
      "apis": [
        {
          "operations": [
            {
              "errorResponses": [
                {
                  "reason": "The explorer list",
                  "code": 200
                },
                {
                  "reason": "Explorer list not found",
                  "code": 404
                }
              ],
              "httpMethod": "GET",
              "nickname": "explorerlist",
              "parameters": [
              ],
              "summary": "Fetches explorer list"
            }
          ],
          "path": "/explorerList",
          "description": ""
        }
      ]
    }
    <?php
  }



  // explorer
  public function explorer( $urlParams ) {
    // error_log( __METHOD__ .'urlParams '. print_r( $urlParams, true ) );

    require_once APP_BASE_PATH."/conf/inc/geozzyExplorers.php"; // Load $GEOZZY_EXPLORERS
    global $GEOZZY_EXPLORERS;

    // Quitamos parametros GET de $urlParams
    $urlGetPos = strpos( $urlParams[1], '?' );
    if( $urlGetPos ) {
      $urlParams[1] = substr( $urlParams[1], 0, $urlGetPos );
    }

    $validation = array(
      'explorer'=> '#(.*)#',
      'request'=> '#(.*)#'
    );
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );

    // Añadimos parametros GET a $urlParamsList
    // error_log( __METHOD__ . print_r( $_GET, true ) );
    if( !empty( $_GET ) ) {
      foreach( $_GET as $key => $value ) {
        $urlParamsList[ $key ] = $value;
      }
    }

    // error_log( __METHOD__ . print_r( $urlParamsList, true ) );
    $urlExplorer = isset( $urlParamsList['explorer'] ) ? $urlParamsList['explorer'] : false;
    $urlRequest = isset( $urlParamsList['request'] ) ? $urlParamsList['request'] : false;

    if( $urlRequest && $urlExplorer && isset( $GEOZZY_EXPLORERS[ $urlExplorer ] ) ) {
      $explorerConf = $GEOZZY_EXPLORERS[ $urlExplorer ];
      $explorerModule = $explorerConf['module'];

      // Include controller
      $explorerModule::load( $explorerConf['controllerFile'] );
      // eval( $explorerConf['module'].'::load("'.$explorerConf['controllerFile'].'");' );

      // constructor;
      $explorerCtrl = new $explorerConf['controllerName']();

      if( $urlRequest == 'minimal' ) {
        // if( isset($urlParamsList['updatedfrom']) ) {
        //   header('Content-type: application/json');
        //   $explorerCtrl->serveMinimal( $urlParamsList['updatedfrom'] );
        // }
        // else {
          header('Content-type: application/json');

          // Añadimos $urlParamsList como parametro
          $explorerCtrl->serveMinimal( false, $urlParamsList );
        // }
      }
      elseif( $urlRequest == 'partial' ) {
        header('Content-type: application/json');
        // Añadimos $urlParamsList como parametro
        $explorerCtrl->servePartial( $urlParamsList );
      }
      else {
        header("HTTP/1.0 404 Not Found");
      }
    }
    else {
      header("HTTP/1.0 404 Not Found");
    }
  }

  // explorer list
  public function explorerList() {
    require_once APP_BASE_PATH."/conf/inc/geozzyExplorers.php"; // Load $GEOZZY_EXPLORERS
    global $GEOZZY_EXPLORERS;

    if( count( $GEOZZY_EXPLORERS ) > 0 ) {
      $explListInfo = array();
      foreach( $GEOZZY_EXPLORERS as $explId => $explInfo ) {
        $explListInfo[ $explId ] = array(
          'name' => $explInfo[ 'name' ],
          'mapBounds' => isset( $explInfo[ 'mapBounds' ] ) ? $explInfo[ 'mapBounds' ] : false,
          'filters' => isset( $explInfo[ 'filters' ] ) ? $explInfo[ 'filters' ] : false
        );
      }

      header('Content-type: application/json');
      echo json_encode( $explListInfo );
    }
    else {
      header("HTTP/1.0 404 Not Found");
    }
  }
}
