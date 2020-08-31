<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class RExtUrlResourceTplModel extends Model {

  static $tableName = 'geozzy_rext_url_resource_tpl';

  static $cols = array(
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
    'userUpdate' => array(
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
    'imageName' => [
      'type' => 'VARCHAR',
      'size' => 250
    ],
    'imageAKey' => [
      'type' => 'VARCHAR',
      'size' => 16
    ],
    'loc' => array(
      'type' => 'GEOMETRY'
    ),
    'defaultZoom' => array(
      'type' => 'INT'
    ),
    'externalUrl' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'rextUrlUrl' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'urlAlias' => array(
      'type' => 'VARCHAR',
      'size' => 2000,
      'multilang' => true
    ),
    'headKeywords' => array(
      'type' => 'VARCHAR',
      'size' => 150,
      'multilang' => true
    ),
    'headDescription' => array(
      'type' => 'VARCHAR',
      'size' => 150,
      'multilang' => true
    ),
    'headTitle' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
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
    'countVisits' => array(
      'type' => 'INT'
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    )
  );


  static $extraFilters = [
    'id' => ' res.id = ? ',
    'user' => ' res.user = ? ',
    'published' => ' res.published = ? ',
    'ids' => ' res.id IN (?) ',
    'inId' => ' res.id IN (?) ',
    'idIn' => ' res.id IN (?) ',
    'notInId' => ' res.id NOT IN (?) ',
  ];


  var $notCreateDBTable = true;

  function customSelectListItems( $extraArrayParam ) {

    $sql = '
      SELECT
        res.id, res.idName, res.rTypeId, rt.idName AS rTypeIdName,
        res.user, res.userUpdate, res.published,
        {multilang:res.title_$lang,}
        {multilang:res.shortDescription_$lang,}
        {multilang:res.mediumDescription_$lang,}
        {multilang:res.content_$lang,}
        res.image, fd.name AS imageName, fd.AKey AS imageAKey,
        res.loc, res.defaultZoom, res.externalUrl,
        ru.url as rextUrlUrl,
        {multilang:GROUP_CONCAT( DISTINCT if(lang="$lang",ua.urlFrom,null)) AS "urlAlias_$lang",}
        {multilang:res.headKeywords_$lang,}
        {multilang:res.headDescription_$lang,}
        {multilang:res.headTitle_$lang,}
        res.timeCreation, res.timeLastUpdate, res.timeLastPublish,
        res.countVisits, res.weight
      FROM
        (((((((( geozzy_resource res
        JOIN geozzy_resourcetype rt ON rt.id = res.rTypeId )
        LEFT JOIN user_user u ON u.id = res.user )
        LEFT JOIN geozzy_url_alias ua ON (
          ua.resource = res.id AND ua.http = 0 AND ua.canonical = 1 ) )
        LEFT JOIN filedata_filedata AS fd ON res.image = fd.id )
        LEFT JOIN geozzy_resource_taxonomyterm rTax ON res.id = rTax.resource )
        LEFT JOIN geozzy_resource_topic rTopic ON res.id = rTopic.resource )
        LEFT JOIN geozzy_resource_collections rColl ON res.id = rColl.resource )
        LEFT JOIN geozzy_resource_rext_url AS ru ON res.id = ru.resource )
      WHERE '.
        $extraArrayParam['strWhere'].'
      GROUP BY
        res.id '.
      $extraArrayParam['strOrderBy'].
      $extraArrayParam['strRange'].
      '';

    return $sql;
  }



  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
