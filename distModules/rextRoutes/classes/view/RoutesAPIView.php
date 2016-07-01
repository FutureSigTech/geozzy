<?php

require_once APP_BASE_PATH."/conf/inc/geozzyAPI.php";
Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class RoutesAPIView extends View
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    if( GEOZZY_API_ACTIVE ){
      return( true );
    }
  }


  public function routesJson() {
    header('Content-type: application/json');
    ?>
    {
      "resourcePath": "/routes.json",
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
                  "name": "id",
                  "description": "id",
                  "type": "array",
                  "items": {
                    "type": "integer"
                  },
                  "paramType": "path",
                  "required": false
                }
              ],
              "summary": "Fetches explorer data"
            }
          ],
          "path": "/routes/id/{id}",
          "description": ""
        }
      ]
    }
    <?php
  }



  public function routes( $urlParams  ) {


    $validation = array( 'id'=> '#^\d+$#');
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );


    rextRoutes::autoIncludes();
    rextRoutes::load('controller/RoutesController.php');
    $routesControl = new RoutesController();


    header('Content-type: application/json');

    if( isset($urlParamsList['id']) ) {
      echo json_encode(  $routesControl->getRoute( $urlParamsList['id'] ) );
    }
    else {
      echo '[]';
    }


  }


  public function adminRoutes( $urlParams  ) {


    $validation = array( 'idForm'=> '#(.*)#');
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );


    rextRoutes::autoIncludes();
    rextRoutes::load('controller/RoutesController.php');
    $routesControl = new RoutesController();


    header('Content-type: application/json');

    if( isset($urlParamsList['idForm']) ) {
      echo json_encode(  $routesControl->getRouteInForm( $urlParamsList['idForm'] ) );
    }
    else {
      echo '[]';
    }


  }


}
