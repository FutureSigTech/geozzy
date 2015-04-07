<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class TaxonomygroupModel extends Model
{
  static $tableName = 'geozzy_taxonomygroup';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'idName' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'unique' => true
    ),
    'editable' => array(
      'type' => 'BOOLEAN'
    )

  );

  var $filters = array( );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
