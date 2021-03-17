<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class ParticipationModel extends Model
{
  static $tableName = 'geozzy_resource_rext_participation';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'participation' => array(
      'type' => 'BOOLEAN',
      'default' => 0
    ),
    'observation' => array(
      'type' => 'VARCHAR',
      'size' => 200
    )
  );

  static $extraFilters = array();

  var $deploySQL = [
    [
      'version' => 'rextParticipation#2',
      'sql' => '
        ALTER TABLE geozzy_resource_rext_participation CHANGE COLUMN observation `observation` VARCHAR(500) NULL DEFAULT NULL ;
      '
    ]
  ];



  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }


}
