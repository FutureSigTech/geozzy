<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceViewModelImageTpl extends Model {

  public static $tableName = 'geozzy_resource_view_image_tpl';


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
    'published' => array(
      'type' => 'BOOLEAN'
    ),
    'title' => array(
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
    'imageName' => [
      'type' => 'VARCHAR',
      'size' => 250
    ],
    'imageAKey' => [
      'type' => 'VARCHAR',
      'size' => 16
    ],
    'timeCreation' => array(
      'type' => 'DATETIME'
    ),
    'timeLastUpdate' => array(
      'type' => 'DATETIME'
    ),
    'timeLastPublish' => array(
      'type' => 'DATETIME'
    ),
  );


  public static $extraFilters = array(
    'id' => ' res.id = ? ',

    'idIn' => ' res.id IN (?) ',
    'ids' => ' res.id IN (?) ',

    'inRtypeIdName' => ' rType.idName IN (?) ',

    'published' => ' res.published = ? ',
  );

  public function customSelectListItems( $extraArrayParam ) {

    return '
      SELECT
        res.id, res.idName, res.rTypeId, rType.idName AS rTypeIdName, res.published,
        {multilang:res.title_$lang,}
        res.image, fd.name AS imageName, fd.AKey AS imageAKey,
        res.timeCreation, res.timeLastUpdate, res.timeLastPublish
      FROM
        (((
        geozzy_resource res
        JOIN geozzy_resourcetype rType ON (res.rTypeId = rType.id) )
        LEFT JOIN user_user u ON (u.id = res.user) )
        LEFT JOIN filedata_filedata AS fd ON (res.image = fd.id) )
      WHERE'.
        $extraArrayParam['strWhere'].'
      GROUP BY
        res.id '.
      $extraArrayParam['strOrderBy'].
      $extraArrayParam['strRange'].
    '';
  }

  public function __construct( $datarray = [], $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

  public $notCreateDBTable = true;
}
