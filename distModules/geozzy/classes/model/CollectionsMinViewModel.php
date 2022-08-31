<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class CollectionsMinViewModel extends Model {

  public static $tableName = 'TPL_geozzy_collectons_min';


  public static $cols = [
    'id' => [
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ],
    'idName' => [
      'type' => 'VARCHAR',
      'size' => 240
    ],
    'collectionType' => [
      'type' => 'VARCHAR',
      'size' => 100
    ],
    'title' => [
      'type' => 'VARCHAR',
      'size' => 240,
      'multilang' => true
    ],
    'shortDescription' => [
      'type' => 'VARCHAR',
      'size' => 240,
      'multilang' => true
    ],
    'image' => [
      'type' => 'INT',
    ],
    'resource' => [
      'type' => 'INT',
    ],
    'weight' => [
      'type' => 'SMALLINT'
    ],
  ];


  public static $extraFilters = [
    //
    // Con este formato de modelo hay que declarar explicitamente los parametros
    //

    'id' => ' geozzy_collection.id = ? ',
    'idIn' => ' geozzy_collection.id IN (?) ',
    'idNotIn' => ' geozzy_collection.id NOT IN (?) ',

    'collectionIdName' => ' geozzy_collection.idName = ? ',
    'collectionIdNameIn' => ' geozzy_collection.idName IN (?) ',
    'collectionIdNameNotIn' => ' geozzy_collection.idName NOT IN (?) ',

    'collectionType' => ' geozzy_collection.collectionType = ? ',
    'collectionTypeIn' => ' geozzy_collection.collectionType IN (?) ',
    'collectionTypeNotIn' => ' geozzy_collection.collectionType NOT IN (?) ',

    'resource' => ' geozzy_resource_collections.resource = ? ',
    'resourceIn' => ' geozzy_resource_collections.resource IN (?) ',
    'resourceNotIn' => ' geozzy_resource_collections.resource NOT IN (?) ',

  ];



  public function customSelectListItems( $extraArrayParam ) {

    $consulta = 'SELECT
        geozzy_collection.id,
        geozzy_collection.idName,
        geozzy_collection.collectionType,
        {multilang:geozzy_collection.title_$lang,}
        {multilang:geozzy_collection.shortDescription_$lang,}
        geozzy_collection.image,

        geozzy_resource_collections.resource,
        geozzy_resource_collections.weight
      FROM
        (
        geozzy_collection
        join geozzy_resource_collections ON
        geozzy_resource_collections.collection = geozzy_collection.id )
      WHERE '. $extraArrayParam['strWhere'] .'
      '. $extraArrayParam['strOrderBy'] .'
      '. $extraArrayParam['strRange'] .'
    ';

    // error_log( 'CONULTA: '.$consulta );
    return $consulta;
  }


  public $notCreateDBTable = true;

  public $deploySQL = [];

}
