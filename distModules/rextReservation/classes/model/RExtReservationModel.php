<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class RExtReservationModel extends Model {

  static $tableName = 'geozzy_resource_rext_reservation';
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
    'url' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'phone' => array(
      'type' => 'VARCHAR',
      'size' => 20
    ),
    'email' => array(
      'type' => 'VARCHAR',
      'size' => 255
    ),
    'channel' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'idRelate' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'moreInfo' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'reservas' => [
      'type' => 'BOOLEAN'
    ],
    'txtBotonReservas' => [
      'multilang' => true,
      'type' => 'VARCHAR',
      'size' => 25
    ],
    'bloqueReservas' => [
      'multilang' => true,
      'type' => 'VARCHAR'
    ]
  );

  static $extraFilters = array();

  var $deploySQL = [
    [
      'version' => 'rextReservation#2',
      'sql' => '
        ALTER TABLE geozzy_resource_rext_reservation ADD COLUMN reservas bit(1);
        {multilang:ALTER TABLE geozzy_resource_rext_reservation ADD COLUMN txtBotonReservas_$lang VARCHAR(25) default NULL;}
        {multilang:ALTER TABLE geozzy_resource_rext_reservation ADD COLUMN bloqueReservas_$lang TEXT default NULL;}
      '
    ]
  ];


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
