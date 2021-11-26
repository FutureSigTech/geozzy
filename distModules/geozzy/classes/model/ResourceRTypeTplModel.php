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
    'mediumDescription' => array(
      'type' => 'TEXT',
      'multilang' => true
    ),
    'content' => array(
      'type' => 'TEXT',
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
      'size' => 2000,
      'multilang'=> true
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
    //
    // Con este formato de modelo hay que declarar explicitamente los parametros
    //
    'user' => ' res.user = ? ',
    'published' => ' res.published = ? ',
    'find' => " ( UPPER( res.title_es )  LIKE CONCAT( '%', UPPER(?), '%' ) OR res.id = ? )",

    'id' => ' res.id = ? ',
    'idIn' => ' res.id IN (?) ',
    'idNotIn' => ' res.id NOT IN (?) ',
    'ids' => ' res.id IN (?) ',
    'inId' => ' res.id IN (?) ',
    'notInId' => ' res.id NOT IN (?) ',

    'idName' => ' res.idName = ? ',
    'idNameIn' => ' res.idName IN (?) ',
    'inIdName' => ' res.idName IN (?) ',

    'rTypeId' => ' res.rTypeId = ? ',
    'rTypeIdIn' => ' res.rTypeId IN (?) ',
    'rTypeIdNotIn' => ' res.rTypeId NOT IN (?) ',
    'inRtype' => ' res.rTypeId IN (?) ',
    'notInRtype' => ' res.rTypeId NOT IN (?) ',

    'rTypeIdName' => ' rType.idName = ? ',
    'rTypeIdNameIn' => ' rType.idName IN (?) ',
    'rTypeIdNameNotIn' => ' rType.idName NOT IN (?) ',
    'inRtypeIdName' => ' rType.idName IN (?) ',
    'notInRtypeIdName' => ' rType.idName NOT IN (?) ',

    'lastUpdatefrom' => ' ( res.timeLastUpdate >= ? ) ',
    'updatedfrom' => ' ( res.timeCreation >= ? OR res.timeLastUpdate >= ? ) ',
    'createdfrom' => ' ( res.timeCreation >= ? ) ',
    'createdAfter' => ' ( res.timeCreation > ? ) ',
    'createdBefore' => ' ( res.timeCreation < ? ) ',
  ];


  public function customSelectListItems( $extraArrayParam ) {

    $consulta = '
      SELECT
        res.id, res.idName, res.rTypeId, rType.idName AS rTypeIdName,
        res.user, res.published,
        {multilang:res.title_$lang,}
        {multilang:res.shortDescription_$lang,}
        {multilang:res.mediumDescription_$lang,}
        {multilang:res.content_$lang,}
        res.image,
        {multilang:res.externalUrl_$lang,}
        res.timeCreation, res.timeLastUpdate, res.timeLastPublish,
        res.weight,
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
