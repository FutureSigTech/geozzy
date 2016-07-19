<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class UrlAliasResourceViewModel extends Model {

  static $tableName = 'geozzy_url_alias_resource_view';

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
    'lang' => array(
      'type' => 'CHAR',
      'size' => 4
    ),
    'urlFrom' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    ),
    'rTypeId' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'rTypeIdName' => array(
      'type' => 'VARCHAR',
      'size' => 45,
      'unique' => true
    ),
  );

  static $extraFilters = array(
    'resourceIn' => ' geozzy_url_alias_resource_view.resource IN (?) ',
    'rTypeIdIn' => ' geozzy_url_alias_resource_view.rTypeId IN (?) ',
    'rTypeIdNotIn' => ' geozzy_url_alias_resource_view.rTypeId NOT IN (?) ',
    'rTypeIdNameIn' => ' geozzy_url_alias_resource_view.rTypeIdName IN (?) ',
    'rTypeIdNameNotIn' => ' geozzy_url_alias_resource_view.rTypeIdName NOT IN (?) ',
  );


  var $deploySQL = array(
    // All Times
    array(
      'version' => 'geozzy#1.5',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_url_alias_resource_view;

        CREATE VIEW geozzy_url_alias_resource_view AS

          SELECT
            ua.id, ua.resource, ua.lang, ua.urlFrom, ua.weight,
            r.rTypeId AS rTypeId, rt.idname AS rTypeIdName
          FROM
            geozzy_url_alias AS ua, geozzy_resource AS r, geozzy_resourcetype AS rt
          WHERE
            ua.resource=r.id AND r.rTypeId=rt.id AND
            http=0 AND canonical=TRUE
          ;
      '
    )
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}