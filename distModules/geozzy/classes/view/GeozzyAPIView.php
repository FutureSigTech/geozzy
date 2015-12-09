<?php

require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class geozzyAPIView extends View
{

  function __construct($baseDir){
    parent::__construct($baseDir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {
    if( GEOZZY_API_ACTIVE ){
     return true;
    }
  }


  function biJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/bi.json",
              "basePath": "/api",
              "apis": [
                  {
                      "operations": [
                          {
                              "errorResponses": [
                                  {
                                      "reason": "Utils  for BI dashboard",
                                      "code": 200
                                  }
                              ],

                              "httpMethod": "GET",
                              "nickname": "resource",
                              "parameters": [
                              ],
                              "summary": ""
                          }
                      ],
                      "path": "/core/bi",
                      "description": ""
                  }
              ]

          }

        <?php
  }



  function resourcesJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/resources.json",
              "basePath": "/api",
              "apis": [
                  {
                      "operations": [
                          {
                              "errorResponses": [
                                  {
                                      "reason": "The resource",
                                      "code": 200
                                  },
                                  {
                                      "reason": "Resource not found",
                                      "code": 404
                                  }
                              ],

                              "httpMethod": "POST",
                              "nickname": "resource",
                              "parameters": [
                                {
                                  "name": "ids",
                                  "description": "fields (separed by comma)",
                                  "type": "array",
                                  "items": {
                                    "type": "integer"
                                  },
                                  "paramType": "form",
                                  "required": false
                                },
                                {
                                  "name": "fields",
                                  "description": "fields (separed by comma)",
                                  "dataType": "string",
                                  "paramType": "path",
                                  "defaultValue": "false",
                                  "required": false
                                },
                                {
                                  "name": "filters",
                                  "description": "filters (separed by comma)",
                                  "dataType": "string",
                                  "paramType": "path",
                                  "defaultValue": "false",
                                  "required": false
                                },
                                {
                                  "name": "filtervalues",
                                  "description": "filtervalues (separed by comma)",
                                  "dataType": "string",
                                  "paramType": "path",
                                  "defaultValue": "false",
                                  "required": false
                                },
                                {
                                  "name": "rtype",
                                  "description": "Resource Type",
                                  "dataType": "string",
                                  "paramType": "path",
                                  "defaultValue": "false",
                                  "required": false
                                },
                                {
                                  "name": "rextmodels",
                                  "description": "extension Models",
                                  "dataType": "string",
                                  "paramType": "path",
                                  "defaultValue": "false",
                                  "required": false
                                }

                              ],
                              "summary": "Fetches resource list"
                          }
                      ],
                      "path": "/core/resourcelist/fields/{fields}/filters/{filters}/rtype/{rtype}/rextmodels/{rextmodels}",
                      "description": ""
                  }
              ]

          }

        <?php
  }



    function resourceIndexJson() {
      header('Content-type: application/json');


      ?>
            {
                "resourcePath": "/resources.json",
                "basePath": "/api",
                "apis": [
                    {
                        "operations": [
                            {
                                "errorResponses": [
                                    {
                                        "reason": "The resource index",
                                        "code": 200
                                    }
                                ],

                                "httpMethod": "GET",
                                "nickname": "resourceIndex",
                                "parameters": [


                                    {
                                      "name": "taxonomyTerms",
                                      "description": "ids (separed by comma)",
                                      "dataType": "string",
                                      "paramType": "path",
                                      "defaultValue": "false",
                                      "required": false
                                    },
                                    {
                                      "name": "types",
                                      "description": "ids (separed by comma)",
                                      "dataType": "string",
                                      "paramType": "path",
                                      "defaultValue": "false",
                                      "required": false
                                    },
                                    {
                                      "name": "topics",
                                      "description": "ids (separed by comma)",
                                      "dataType": "string",
                                      "paramType": "path",
                                      "defaultValue": "false",
                                      "required": false
                                    },
                                    {
                                      "name": "bounds",
                                      "description": "lat,lng",
                                      "dataType": "string",
                                      "paramType": "path",
                                      "defaultValue": "false",
                                      "required": false
                                    }


                                ],
                                "summary": "Fetches resource list"
                            }
                        ],
                        "path": "/core/resourceIndex/taxonomyTerms/{taxonomyTerms}/types/{types}/topics/{topics}/bounds/{bounds}",
                        "description": ""
                    }
                ]

            }

          <?php
    }




  function resourceTypesJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/resourceTypes.json",
              "basePath": "/api",
              "apis": [
                  {
                      "operations": [
                          {
                              "errorResponses": [
                                  {
                                      "reason": "Not found",
                                      "code": 404
                                  }
                              ],
                              "httpMethod": "GET",
                              "nickname": "resource",
                              "parameters": [

                              ],
                              "summary": "Fetches resource type list"
                          }
                      ],
                      "path": "/core/resourcetypes",
                      "description": ""
                  }
              ]


          }

        <?php
  }



  function starredJson() {
    header('Content-type: application/json');
    ?>
    {
      "resourcePath": "/starred.json",
      "basePath": "/api",
      "apis": [
        {
          "operations": [
            {
              "errorResponses": [
                {
                  "reason": "Permission denied",
                  "code": 401
                },
                {
                  "reason": "Starred term list",
                  "code": 200
                },
                {
                  "reason": "Starred not found",
                  "code": 404
                }
              ],

              "httpMethod": "GET",
              "nickname": "group",
              "parameters": [],
              "summary": "Get Starred terms"
            }
          ],
          "path": "/core/starred",
          "description": ""
        }
      ]
    }
    <?php
  }




  function categoryListJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/categoryList.json",
              "basePath": "/api",
              "apis": [
                  {
                      "operations": [
                          {
                              "errorResponses": [
                                  {
                                      "reason": "Not found",
                                      "code": 404
                                  }
                              ],
                              "httpMethod": "GET",
                              "nickname": "resource",
                              "parameters": [
                              ],
                              "summary": "Fetches all category groups"
                          }
                      ],
                      "path": "/core/categorylist",
                      "description": ""
                  }
              ]


          }

        <?php
  }

  function categoryTermsJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/categoryTerms.json",
              "basePath": "/api",
              "apis": [
                  {
                      "operations": [
                          {
                              "errorResponses": [
                                  {
                                      "reason": "Not found",
                                      "code": 404
                                  }
                              ],
                              "httpMethod": "GET",
                              "nickname": "resource",
                              "parameters": [
                                {
                                  "required": false,
                                  "dataType": "int",
                                  "name": "id",
                                  "paramType": "path",
                                  "allowMultiple": false,
                                  "defaultValue": "false",
                                  "description": "group id"
                                },
                                {
                                  "required": false,
                                  "dataType": "string",
                                  "name": "name",
                                  "paramType": "path",
                                  "allowMultiple": false,
                                  "defaultValue": "false",
                                  "description": "group idName"
                                }
                              ],
                              "summary": "Fetches category terms"
                          }
                      ],
                      "path": "/core/categoryterms/id/{id}/idname/{name}",
                      "description": ""
                  }
              ]


          }

        <?php
  }



  function topicListJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/topicList.json",
              "basePath": "/api",
              "apis": [
                  {
                      "operations": [
                          {
                              "errorResponses": [
                                  {
                                      "reason": "Not found",
                                      "code": 404
                                  }
                              ],
                              "httpMethod": "GET",
                              "nickname": "resource",
                              "parameters": [

                              ],
                              "summary": "Fetches topics"
                          }
                      ],
                      "path": "/core/topiclist",
                      "description": ""
                  }
              ]


          }

        <?php
  }
/*

  function uiEventListJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/uieventList.json",
              "basePath": "/api",
              "apis": [
                  {
                      "operations": [
                          {
                              "errorResponses": [
                                  {
                                      "reason": "Not found",
                                      "code": 404
                                  }
                              ],
                              "httpMethod": "GET",
                              "nickname": "resource",
                              "parameters": [

                              ],
                              "summary": "Event type list"
                          }
                      ],
                      "path": "/core/uieventlist",
                      "description": ""
                  }
              ]


          }

        <?php
  }
*/


    // resources

    function bi(  ) {
      require_once APP_BASE_PATH."/conf/geozzyBI.php";
      header('Content-type: application/json');
      global $LANG_AVAILABLE, $BI_SITE_SECTIONS, $BI_DEVICES, $BI_METRICS_EXPLORER, $BI_METRICS_RESOURCE, $BI_GEOZZY_UI_EVENTS;

      $langs = array(
        'default'=> LANG_DEFAULT,
        'available'=> $LANG_AVAILABLE
      );

      echo json_encode(
        array(
          'languages' => $langs,
          'devices' => $BI_DEVICES,
          'sections' => $BI_SITE_SECTIONS,
          'ui_events' => $BI_GEOZZY_UI_EVENTS,
          'metrics' => array(
            'explorer' => $BI_METRICS_EXPLORER,
            'resource' => $BI_METRICS_RESOURCE
          )
        )
      );

    }



  // resources

  function resourceList( $param ) {

    Cogumelo::load('coreModel/DBUtils.php');
    geozzy::load('model/ResourceModel.php');
    geozzy::load('controller/apiFiltersController.php');


    $validation = array(
      'rextmodels'=> '#(.*)#',
      'loc'=> '#(.*)#',
      'type'=> '#(.*)#',
      'filters'=> '#(.*)#',
      'fields' => '#(.*)#',
      'rtype' => '#(.*)#'

    );

    $extraParams = RequestController::processUrlParams($param, $validation);

    $queryParameters = apiFiltersController::resourceListOptions($param);

    if( isset($_POST['ids']) ) {
      if( is_array($_POST['ids']) ) {
        $queryParameters['filters']['ids'] = array_map( 'intval',$_POST['ids']);
      }
      else if( intval( $_POST['ids'] ) ) {
          $queryParameters['filters']['ids'] = $_POST['ids'];

      }

    }


    $resourceModel = new ResourceModel();
    $resourceList = $resourceModel->listItems( $queryParameters  );


    header('Content-type: application/json');
    echo '[';
    $c = '';
    while ($valueobject = $resourceList->fetch() )
    {
      $allData = $valueobject->getAllData('onlydata');


      if( $extraParams['rextmodels'] == 'true') {
        // Remove all REXT related models

        $relatedModels = $valueobject->getRextModels();


        foreach( $relatedModels as $relModelIdName => $relModel ) {

          $rexData = array();
          $rexData['MODELNAME'] = $relModelIdName;

          $rexData = array_merge($rexData, $relModel->getAllData('onlydata') );
          $allData['rextmodels'][] = $rexData;
        }
      }



/*

      // Remove all REXT related models
      $relatedModels = $this->getRextModels();

      foreach( $relatedModels as $relModelIdName => $relModel ) {
        if($relModel) {
          $relModel->delete();
        }
      }
*/

      if( isset($allData['loc']) ) {
        $loc = DBUtils::decodeGeometry( $allData['loc'] );
        $allData['loc'] = array( 'lat' => floatval( $loc['data'][0] ) , 'lng' => floatval( $loc['data'][1] ) );
      }
      echo $c.json_encode( $allData );


      if($c === ''){$c=',';}
    }
    echo ']';





  }



  function resourceIndex( $urlParams ) {
    geozzyAPI::load('model/ResourceIndexModel.php');
    $resourceIndexModel = new ResourceIndexModel();


    $validation = array(
      'taxonomyterms'=> '#(.*)#',
      'types'=> '#(.*)#',
      'topics'=> '#(.*)#',
      'bounds'=> '#(.*)#'
    );

    $queryFilters = RequestController::processUrlParams($urlParams, $validation);





    // taxonomy terms
    if( isset($queryFilters['taxonomyterms']) ) {
      $queryFilters['taxonomyterms'] = implode(',', array_map('intval', explode(',', $queryFilters['taxonomyterms'] ) ) );
    }

    // types
    if(  isset($queryFilters['types']) ) {
      $queryFilters['types'] = array_map('intval', explode(',',$queryFilters['types']) );
    }

    // topics
    if(  isset($queryFilters['topics']) ) {
      $queryFilters['topics'] = array_map('intval', explode(',', $queryFilters['topics'] ) );
    }




    if(
      isset($queryFilters['bounds']) &&
      preg_match(
        '#(.*)\ (.*)\,(.*)\ (.*)#',
        urldecode( $queryFilters['bounds'] ),
        $bounds
      )
    ) {

        if( is_numeric($bounds[1]) && is_numeric($bounds[2]) &&
            is_numeric($bounds[3]) && is_numeric($bounds[4])
        ) {

          $queryFilters['bounds']=  $bounds[1].' '.$bounds[2].','.
                                    $bounds[1].' '.$bounds[4].','.
                                    $bounds[3].' '.$bounds[4].','.
                                    $bounds[3].' '.$bounds[2].','.
                                    $bounds[1].' '.$bounds[2];
        }

    }




    $queryFilters['published'] = 1;


    $resourceList = $resourceIndexModel->listItems( array('filters' => $queryFilters, 'groupBy'=>'id') );
    header('Content-type: application/json');
    echo '[';
    $c = '';
    while ($valueobject = $resourceList->fetch() )
    {
      echo $c.$valueobject->getter('id');
      if($c === ''){$c=',';}
    }
    echo ']';



  }

  function resourceTypes() {
    geozzy::load('model/ResourcetypeModel.php');
    $resourcetypeModel = new ResourcetypeModel( );
    $resourcetypeList = $resourcetypeModel->listItems( ) ;
    $this->syncModelList( $resourcetypeList );
  }

  // Starred

  function starred() {
    $taxtermModel = new TaxonomytermModel();
    $starredList = $taxtermModel->listItems(array( 'filters' => array( 'TaxonomygroupModel.idName' => 'starred' ), 'affectsDependences' => array('TaxonomygroupModel'), 'joinType' => 'RIGHT' ));

    geozzy::load('model/StarredResourcesModel.php');
    header('Content-type: application/json');

    echo '[';

    $c = '';
    while ($starred = $starredList->fetch() )
    {
      $starData = $starred->getAllData('onlydata');

      $starredResources = (new StarredResourcesModel)->listItems( array('filters'=>array('taxonomyterm'=>$starData['id']), 'order'=>array('weight'=>1)) );

      while( $starredResource = $starredResources->fetch() ){
        $starData['resources'][] = $starredResource->getAllData('onlydata');
      }

      echo $c.json_encode( $starData );
      if($c === ''){$c=',';}
    }
    echo ']';

  }


  // Categories

  function categoryList() {
    geozzy::load('model/TaxonomygroupModel.php');
    $taxgroupModel = new TaxonomygroupModel();
    $taxGroupList = $taxgroupModel->listItems(array( 'filters' => array( 'editable'=>1 ) ));
    $this->syncModelList( $taxGroupList );

  }

  function categoryTerms( $urlParams ) {


    $validation = array('id'=> '#\d+$#', 'idname'=>'#(.*)#');
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);

    if( isset( $urlParamsList['id'] ) && is_numeric( $urlParamsList['id'] ) ) {
      geozzy::load('model/TaxonomytermModel.php');
      $taxtermModel = new TaxonomytermModel();
      $taxtermList = $taxtermModel->listItems(  array( 'filters' => array( 'taxgroup'=>$urlParamsList['id'] ) ) );
      $this->syncModelList( $taxtermList );
    }
    else
    if( isset( $urlParamsList['idname'] ) && $urlParamsList['idname'] != 'false' ) {

      geozzy::load('model/TaxonomytermModel.php');
      $taxtermModel = new TaxonomytermModel();
      $taxtermList = $taxtermModel->listItems( array( 'filters' => array( 'TaxonomygroupModel.idName' => $urlParamsList['idname']  ),'affectsDependences' => array( 'TaxonomygroupModel' ), 'joinType' => 'RIGHT' ) );

      $this->syncModelList( $taxtermList );
    }
    else {
      header("HTTP/1.0 404 Not Found");
      header('Content-type: application/json');
      echo '{}';
    }
  }

  // Topics
  function topicList() {
    geozzy::load('model/TopicModel.php');
    $topicModel = new TopicModel();
    $topicList = $topicModel->listItems( );
    $this->syncModelList( $topicList );
  }

/*
  // UI events
  function uiEventList() {
    require_once APP_BASE_PATH."/conf/geozzyUIEvents.php";
    global  $GEOZZY_UI_EVENTS;

    header('Content-type: application/json');
    echo json_encode( $GEOZZY_UI_EVENTS );
  }
*/

  function syncModelList( $result ) {

    header('Content-type: application/json');
    echo '[';
    $c = '';
    while ($valueobject = $result->fetch() )
    {
      $allData = $valueobject->getAllData('onlydata');
      echo $c.json_encode( $allData);
      if($c === ''){$c=',';}
    }
    echo ']';
  }


  function syncModel( $model ) {
    header('Content-type: application/json');
    $data = $model->getAllData('onlydata');
    echo json_encode( $data );


  }




}
