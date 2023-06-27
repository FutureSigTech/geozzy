<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceViewModelTpl extends Model {

  public static $tableName = 'geozzy_resource_view_tpl';


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
    'userName' => array(
      'type' => 'VARCHAR',
      'size' => 255
    ),
    'userSurname' => array(
      'type' => 'VARCHAR',
      'size' => 255
    ),
    'userEmail' => array(
      'type' => 'VARCHAR',
      'size' => 255,
      'unique' => true
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
      'size' => 2000,
      'multilang'=> true
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
    ),
    'termIdList' => array(
      'type' => 'VARCHAR',
      'size' => 250
    ),
    'topicIdList' => array(
      'type' => 'VARCHAR',
      'size' => 250
    ),
    'collIdList' => array(
      'type' => 'VARCHAR',
      'size' => 250
    ),
    'relatedModels' => array(
      'type' => 'VARCHAR',
      'size' => 250
    ),
  );


  public static $extraFilters = array(
    'id' => ' res.id = ? ',

    'idIn' => ' res.id IN (?) ',
    'ids' => ' res.id IN (?) ',

    'published' => ' res.published = ? ',
  );


  public $notCreateDBTable = true;


  public function customSelectListItems( $extraArrayParam ) {

    $sql = '
      SELECT
        res.id, res.idName, res.rTypeId, rType.idName AS rTypeIdName, res.user,
        u.email AS userEmail, u.name AS userName, u.surname AS userSurname, res.userUpdate, res.published,
        {multilang:res.title_$lang,}
        {multilang:res.shortDescription_$lang,}
        {multilang:res.mediumDescription_$lang,}
        {multilang:res.content_$lang,}
        res.image, fd.name AS imageName, fd.AKey AS imageAKey,
        res.loc, ST_X(res.loc) AS locLat, ST_Y(res.loc) AS locLon, res.defaultZoom,
        {multilang:res.externalUrl_$lang,}
        {multilang:GROUP_CONCAT( DISTINCT if(lang="$lang",ua.urlFrom,null)) AS "urlAlias_$lang",}
        {multilang:res.headKeywords_$lang,}
        {multilang:res.headDescription_$lang,}
        {multilang:res.headTitle_$lang,}
        res.timeCreation, res.timeLastUpdate, res.timeLastPublish,
        res.countVisits, res.weight,
        GROUP_CONCAT( DISTINCT rTax.taxonomyterm ORDER BY rTax.weight, rTax.id ) AS termIdList,
        GROUP_CONCAT( DISTINCT rTopic.topic ORDER BY rTopic.weight, rTopic.id ) AS topicIdList,
        GROUP_CONCAT( DISTINCT rColl.collection ORDER BY rColl.weight, rColl.id ) AS collIdList,
        rType.relatedModels AS relatedModels
      FROM
        (((((((
        geozzy_resource res
        JOIN geozzy_resourcetype rType )
        LEFT JOIN user_user u ON (u.id = res.user) )
        LEFT JOIN geozzy_url_alias ua ON (ua.resource = res.id AND ua.http = 0 AND ua.canonical = 1 ) )
        LEFT JOIN filedata_filedata AS fd ON (res.image = fd.id) )
        LEFT JOIN geozzy_resource_taxonomyterm rTax ON (res.id = rTax.resource) )
        LEFT JOIN geozzy_resource_topic rTopic ON (res.id = rTopic.resource) )
        LEFT JOIN geozzy_resource_collections rColl ON (res.id = rColl.resource) )
      WHERE'.
        $extraArrayParam['strWhere'].'
        AND rType.id = res.rTypeId
      GROUP BY
        res.id '.
      $extraArrayParam['strOrderBy'].
      $extraArrayParam['strRange'].
      '';

    return $sql;
  }

  public function __construct( $datarray = [], $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
