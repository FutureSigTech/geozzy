<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class ResourcetypeModel extends Model
{
  static $tableName = 'geozzy_resourcetype';
  static $cols = array(

    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'idName' => array(
      'type' => 'VARCHAR',
      'size' => 45,
      'unique' => true
    ),
    'name' => array(
      'type' => 'VARCHAR',
      'size' => 45,
      'multilang' => true
    )

  );

  static $extraFilters = array(
      'intopic' => "id IN (select resourceType from geozzy_resourcetype_topic where topic=?)"
    );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
