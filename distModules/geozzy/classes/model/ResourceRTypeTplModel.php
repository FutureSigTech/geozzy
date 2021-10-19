<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceRTypeTplModel extends Model {

  public static $tableName = 'geozzy_resource_rtype_tpl';

  public static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'idName' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'rTypeId' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourcetypeModel',
      'key'=> 'id'
    ),
    'rTypeIdName' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'user' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'published' => array(
      'type' => 'BOOLEAN'
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'shortDescription' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'image' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir' => '/Resource/'
    ),
    'externalUrl' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'timeCreation' => array(
      'type' => 'DATETIME'
    ),
    'timeLastUpdate' => array(
      'type' => 'DATETIME'
    ),
    'timeLastPublish' => array(
      'type' => 'DATETIME'
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    ),
    'relatedModels' => array(
      'type' => 'VARCHAR',
      'size' => 250
    ),
  );


  public static $extraFilters = [
    'find' => " ( UPPER( res.title_es )  LIKE CONCAT( '%', UPPER(?), '%' ) OR res.id = ? )",
    'notInRtype' => ' res.rTypeId NOT IN (?) ',
    'ids' => ' res.id IN (?) ',
    'inId' => ' res.id IN (?) ',
    'idIn' => ' res.id IN (?) ',
    'notInId' => ' res.id NOT IN (?) ',
    'inIdName' => ' res.idName IN (?) ',
    'idNameIn' => ' res.idName IN (?) ',
    'updatedfrom' => ' ( res.timeCreation >= ? OR res.timeLastUpdate >= ? ) ',
    'createdfrom' => ' ( res.timeCreation >= ? ) ',
    'lastUpdatefrom' => ' ( res.timeLastUpdate >= ? ) ',
    'createdAfter' => ' ( res.timeCreation > ? ) ',
    'createdBefore' => ' ( res.timeCreation < ? ) ',

    'inRtype' => ' res.rTypeId IN (?) ',
    'inRtypeIdName' => ' rType.idName IN (?) ',
    'notInRtypeIdName' => ' rType.idName NOT IN (?) ',

    //
    // Con este formato de modelo hay que declarar explicitamente los parametros
    //
    'id' => ' res.id = ? ',
    'idName' => ' res.idName = ? ',
    'rTypeIdName' => ' rType.idName = ? ',
  ];


  public function customSelectListItems( $extraArrayParam ) {

    $consulta = '
      SELECT
        res.id, res.idName, res.rTypeId, rType.idName AS rTypeIdName,
        res.user,res.published,
        {multilang:res.title_$lang,}
        {multilang:res.shortDescription_$lang,}
        res.image, res.externalUrl, res.weight,
        res.timeCreation, res.timeLastUpdate, res.timeLastPublish,
        rType.relatedModels AS relatedModels
      FROM
        geozzy_resource res
        JOIN geozzy_resourcetype rType ON rType.id = res.rTypeId
      WHERE '.
        $extraArrayParam['strWhere'] .
      $extraArrayParam['strOrderBy'] .
      $extraArrayParam['strRange'];

    return $consulta;
  }


  public $notCreateDBTable = true;

  public $deploySQL = [];
}
