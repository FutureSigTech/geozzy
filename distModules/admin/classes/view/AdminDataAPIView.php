<?php
Cogumelo::load('coreView/View.php');
geozzy::autoIncludes();
admin::autoIncludes();
user::autoIncludes();
require_once APP_BASE_PATH."/conf/geozzyAPI.php";

/**
* Clase Master to extend other application methods
*/
class AdminDataAPIView extends View
{

  function __construct($baseDir){
    parent::__construct($baseDir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {

    $useraccesscontrol = new UserAccessController();
    $res = true;

    if( !GEOZZY_API_ACTIVE || !$useraccesscontrol->isLogged() ){
     $res = false;
    }


    if( $res == false ) {
      header("HTTP/1.0 401");
      header('Content-type: application/json');
      echo '[]';
      exit;
    }

    return $res;

  }


  function categoryTerms( $urlParams ) {

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions( array('category:list', 'category:edit', 'category:delete' ), 'admin:full');
    if(!$access){
      header("HTTP/1.0 403 Forbidden");
      header('Content-type: application/json');
      echo '[]';
      exit;
    }


    $validation = array('id'=> '#\d+$#');
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);


    if( isset( $urlParamsList['id'] ) ){
      $id = $urlParamsList['id'];
    }
    else
    if( isset( $_GET['group'] ) ){
      $id = $_GET['group'];
    }
    else {
      $id = false;
    }


    header('Content-type: application/json');

    switch( $_SERVER['REQUEST_METHOD'] ) {
      case 'PUT':



        $putData = json_decode(file_get_contents('php://input'), true);
        $taxtermModel = new TaxonomytermModel();

        if( is_numeric( $id ) ) {  // UPDATE
          $taxTerm = $taxtermModel->listItems(  array( 'filters' => array( 'id'=>$id ) ))->fetch();
        }
        else { // CREATE
          $taxTerm = $taxtermModel;
        }

        if( isset( $putData['name'] ) ) {
          $taxTerm->setter('name', $putData['name'] );
        }

        if( isset( $putData['parent'] ) ) {
          $taxTerm->setter('parent', $putData['parent'] );
        }

        if( isset( $putData['weight'] ) ) {
          $taxTerm->setter('weight', $putData['weight'] );
        }

        if( isset( $putData['taxgroup'] ) ) {
          $taxTerm->setter('taxgroup', $putData['taxgroup'] );
        }

        $taxTerm->save();

        $termData = $taxTerm->getAllData();
        echo json_encode( $termData['data'] );

        break;
      case 'GET':

        if( $id != false ){

          $taxtermModel = new TaxonomytermModel();
          $taxtermList = $taxtermModel->listItems(  array( 'filters' => array( 'taxgroup'=>$id) ) );
          echo '[';
          $c = '';
          while ($taxTerm = $taxtermList->fetch() )
          {
            $termData = $taxTerm->getAllData();
            echo $c.json_encode( $termData['data'] );
            if($c === ''){$c=',';}
          }
          echo ']';

        }
        else{
          header("HTTP/1.0 404 Not Found");
        }

        break;

      case 'DELETE':
        $taxM = new TaxonomytermModel();
        $taxTerm = $taxM->listItems(
                                    array(
                                            'filters' => array('id'=> $id, 'TaxonomygroupModel.editable'=>1),
                                            'affectsDependences' => array('TaxonomygroupModel'),
                                            'joinType' => 'INNER'
                                        )
                                    );
        if( $taxTerm && $t = $taxTerm->fetch() ) {
          $t->delete();
          header('Content-type: application/json');
          echo '[]';
        }
        else {
          header("HTTP/1.0 404 Not Found");
          header('Content-type: application/json');
          echo '[]';
        }

        break;
    }
  }
  function categoryTermsJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/admin/adminCategoryterms.json",
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
                                      "reason": "Category term list",
                                      "code": 200
                                  },
                                  {
                                      "reason": "Category not found",
                                      "code": 404
                                  }
                              ],

                              "httpMethod": "GET",
                              "nickname": "group",
                              "parameters": [

                                  {
                                      "required": true,
                                      "dataType": "int",
                                      "name": "id",
                                      "defaultValue": "",
                                      "paramType": "path",
                                      "allowMultiple": false,
                                      "description": "Category group id"
                                  }

                              ],
                              "summary": "Get Category terms"
                          },
                          {
                            "errorResponses": [
                                  {
                                      "reason": "Permission denied",
                                      "code": 401
                                  },
                                  {
                                      "reason": "Category term Deleted",
                                      "code": 200
                                  },
                                  {
                                      "reason": "Category not found",
                                      "code": 404
                                  }
                              ],

                              "httpMethod": "PUT",
                              "nickname": "id",
                              "parameters": [

                                  {
                                      "required": true,
                                      "dataType": "int",
                                      "name": "id",
                                      "defaultValue": "",
                                      "paramType": "path",
                                      "allowMultiple": false,
                                      "description": "term id"
                                  }

                              ],
                              "summary": "Insert or Update category terms"
                          },
                          {
                            "errorResponses": [
                                  {
                                      "reason": "Permission denied",
                                      "code": 401
                                  },
                                  {
                                      "reason": "Category term Deleted",
                                      "code": 200
                                  },
                                  {
                                      "reason": "Category not found",
                                      "code": 404
                                  }
                              ],

                              "httpMethod": "DELETE",
                              "nickname": "id",
                              "parameters": [

                                  {
                                      "required": true,
                                      "dataType": "int",
                                      "name": "id",
                                      "defaultValue": "",
                                      "paramType": "path",
                                      "allowMultiple": false,
                                      "description": "term id"
                                  }

                              ],
                              "summary": "Delete category terms"
                          }
                      ],
                      "path": "/admin/categoryterms/id/{id}",
                      "description": ""
                  }
              ]
          }
        <?php
  }


  function categories() {

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions( array('category:list', 'category:edit', 'category:delete' ), 'admin:full');
    if(!$access){
      header('Content-type: application/json');
      echo '[]';
      exit;
    }

    $taxgroupModel = new TaxonomygroupModel();
    $taxGroupList = $taxgroupModel->listItems(array( 'filters' => array( 'editable'=>1 ) ));

    header('Content-type: application/json');

    echo '[';

    $c = '';
    while ($taxGroup = $taxGroupList->fetch() )
    {
      $taxData = $taxGroup->getAllData();
      echo $c.json_encode( $taxData['data'] );
      if($c === ''){$c=',';}
    }
    echo ']';

  }

  function categoriesJson() {
    header('Content-type: application/json');


    ?>
    {
      "resourcePath": "/admin/adminCategories.json",
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
                  "reason": "Category term list",
                  "code": 200
                },
                {
                  "reason": "Category not found",
                  "code": 404
                }
              ],

              "httpMethod": "GET",
              "nickname": "group",
              "parameters": [



              ],
              "summary": "Get Category terms"
            }
          ],
          "path": "/admin/categories",
          "description": ""
        }
      ]
    }
    <?php
  }

  function resourcesTerm( $request ) {

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('starred:list', 'admin:full');
    if(!$access){
      header("HTTP/1.0 403 Forbidden");
      header('Content-type: application/json');
      echo '[]';
      exit;
    }

    $dataRequest = explode("/", $request[1]);

    $id = $dataRequest[0];


    header('Content-type: application/json');

    switch( $_SERVER['REQUEST_METHOD'] ) {
      case 'PUT':

        $putData = json_decode(file_get_contents('php://input'), true);
        $resourceTaxtermModel = new ResourceTaxonomytermModel();

        if( is_numeric( $id ) && is_numeric( $putData['resource'] ) ) {  // UPDATE
          $rterm = $resourceTaxtermModel->listItems( array( 'filters' => array( 'id' => $id, 'resource' => $putData['resource'] )))->fetch();


          if( isset( $putData['weight'] ) ) {
            $rterm->setter('weight', $putData['weight'] );
          }

          $rterm->save();

          $rtData = $rterm->getAllData();
          echo json_encode( $rtData['data'] );
        }
      break;

      case 'GET':

        if( array_key_exists( 'taxonomyterm', $_GET ) && is_numeric( $_GET['taxonomyterm'] ) ){
          $resourceModel = new ResourceModel();
          $fields = array(
            'id',
            'rTypeId',
            'published',
            'title_'.LANG_DEFAULT
          );



          $resourceStarred = $resourceModel->listItems(
            array(
              'filters' => array(
                'ResourceTaxonomytermModel.taxonomyterm' => $_GET['taxonomyterm']
              ),
              'affectsDependences' => array('ResourceTaxonomytermModel'),
              'joinType' => 'RIGHT',
              'fields' => $fields
            )
          );
          echo '[';
          $c = '';
          while ($rs = $resourceStarred->fetch() )
          {
            $dataDep = $rs->getterDependence('id', 'ResourceTaxonomytermModel');

            $rsData = $rs->getAllData();
            $newRsData = $rsData['data'];

            $newRsData['id'] = intval( $dataDep[0]->getter('id') );
            $newRsData['weight'] = intval( $dataDep[0]->getter('weight') );
            $newRsData['resource'] = $rsData['data']['id'];

            echo $c.json_encode( $newRsData );
            if($c === ''){$c=',';}
          }
          echo ']';
        }
        else{
          header("HTTP/1.0 404 Not Found");
        }
      break;

      case 'DELETE':
        $resourceTermModel = new ResourceTaxonomytermModel();
        $rTerm = $resourceTermModel->listItems(
          array(
            'filters' => array(
              'id'=> $id
            )
          )
        );
        if( $rTerm && $rt = $rTerm->fetch() ) {
          $rt->delete();
          header('Content-type: application/json');
          echo '[]';
        }
        else {
          header("HTTP/1.0 404 Not Found");
          header('Content-type: application/json');
          echo '[]';
        }
      break;
    }

  }

  function resourcesTermJson(){
    header('Content-type: application/json');
    ?>
    {
      "resourcePath": "/admin/adminResourcesTerm.json",
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
                  "reason": "Resources term list",
                  "code": 200
                },
                {
                  "reason": "Resources not found",
                  "code": 404
                }
              ],
              "httpMethod": "GET",
              "nickname": "taxonomyterm",
              "parameters": [
                {
                  "required": true,
                  "dataType": "int",
                  "name": "taxonomyterm",
                  "defaultValue": "",
                  "paramType": "path",
                  "allowMultiple": false,
                  "description": "Taxonomyterm id"
                }
              ],
              "summary": "Get Resources term"
            },
            {
              "errorResponses": [
                  {
                    "reason": "Permission denied",
                    "code": 401
                  },
                  {
                    "reason": "Resources term list",
                    "code": 200
                  },
                  {
                    "reason": "Resources not found",
                    "code": 404
                  }
                ],

                "httpMethod": "PUT",
                "nickname": "id",
                "parameters": [
                  {
                    "required": true,
                    "dataType": "int",
                    "name": "taxonomyterm",
                    "defaultValue": "",
                    "paramType": "path",
                    "allowMultiple": false,
                    "description": "term id"
                  },
                  {
                    "required": true,
                    "dataType": "int",
                    "name": "resource",
                    "defaultValue": "",
                    "paramType": "path",
                    "allowMultiple": false,
                    "description": "resource id"
                  }
                ],
                "summary": "Update resourceTerm"
            },
            {
              "errorResponses": [
                  {
                    "reason": "Permission denied",
                    "code": 401
                  },
                  {
                    "reason": "Resources term list",
                    "code": 200
                  },
                  {
                    "reason": "Resources not found",
                    "code": 404
                  }
                ],

                "httpMethod": "DELETE",
                "nickname": "id",
                "parameters": [
                  {
                    "required": true,
                    "dataType": "int",
                    "name": "taxonomyterm",
                    "defaultValue": "",
                    "paramType": "path",
                    "allowMultiple": false,
                    "description": "term id"
                  },
                  {
                    "required": true,
                    "dataType": "int",
                    "name": "resource",
                    "defaultValue": "",
                    "paramType": "path",
                    "allowMultiple": false,
                    "description": "resource id"
                  }
                ],
                "summary": "Delete resourceTerm"
            }
          ],
          "path": "/admin/resourcesTerm/{taxonomyterm}/resource/{resource}",
          "description": ""
        }
      ]
    }
    <?php
  }


  function starred() {

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('starred:list', 'admin:full');
    if(!$access){
      header('Content-type: application/json');
      echo '[]';
      exit;
    }

    $taxtermModel = new TaxonomytermModel();
    $starredList = $taxtermModel->listItems(array( 'filters' => array( 'TaxonomygroupModel.idName' => 'starred' ), 'affectsDependences' => array('TaxonomygroupModel'), 'joinType' => 'RIGHT' ));

    header('Content-type: application/json');

    echo '[';

    $c = '';
    while ($starred = $starredList->fetch() )
    {
      $starData = $starred->getAllData();
      echo $c.json_encode( $starData['data'] );
      if($c === ''){$c=',';}
    }
    echo ']';

  }

  function starredJson() {
    header('Content-type: application/json');
    ?>
    {
      "resourcePath": "/admin/adminStarred.json",
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
          "path": "/admin/starred",
          "description": ""
        }
      ]
    }
    <?php
  }

}
