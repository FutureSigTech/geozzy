<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class AllstoryStepsViewModel extends Model
{
  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'rtypeStoryStep#1.7',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_allstories_index;
        CREATE VIEW geozzy_allstories_index AS
					SELECT
						storyStep.id,

						storyStep.rTypeId,
						storyStep.user,
						storyStep.published,

						{multilang: storyStep.title_$lang, }
						{multilang: storyStep.shortDescription_$lang, }
						{multilang: storyStep.mediumDescription_$lang, }
						{multilang: storyStep.content_$lang, }

						storyStep.image,
						storyStep.loc,
						storyStep.defaultZoom,
						storyStep.externalUrl,


						{multilang: storyStep.headKeywords_$lang, }
						{multilang: storyStep.headDescription_$lang, }
						{multilang: storyStep.headTitle_$lang, }

						storyStep.timeCreation,
						storyStep.timeLastUpdate,
						storyStep.timeLastPublish,

						geozzy_resource_rext_storystep.storystepResource as relatedResource,
						geozzy_resource_rext_storystep.storystepLegend as legend,
            geozzy_resource_rext_storystep.storystepKML as KML,
            geozzy_resource_rext_storystep.drawLine as drawLine,
            geozzy_resource_rext_storystep.mapType as mapType,

						geozzy_collection_resources.weight as weight,
						story.idName as storyName,
	          group_concat(geozzy_resource_taxonomyterm.taxonomyterm) as terms
					FROM geozzy_resource as storyStep

          LEFT JOIN geozzy_resource_taxonomyterm
          ON storyStep.id = geozzy_resource_taxonomyterm.resource

					RIGHT JOIN geozzy_resource_rext_storystep
					ON geozzy_resource_rext_storystep.resource = storyStep.id

					RIGHT JOIN geozzy_collection_resources
					ON geozzy_collection_resources.resource = storyStep.id

					RIGHT JOIN geozzy_collection
					ON geozzy_collection.id = geozzy_collection_resources.collection

					RIGHT JOIN geozzy_resource_collections
					ON geozzy_collection.id = geozzy_resource_collections.collection

					RIGHT JOIN geozzy_resource as story
					ON geozzy_resource_collections.resource = story.id

					WHERE storyStep.published = 1
          GROUP BY storyStep.id
					ORDER BY weight;

      '
    )
  );



  static $tableName = 'geozzy_allstories_index';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'shortDescription' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'mediumDescription' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'content' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),

    'image' => array(
      'type' => 'INT'
    ),
    'loc' => array(
      'type'=>'GEOMETRY'
    ),
    'defaultZoom' => array(
      'type' => 'INT'
    ),
    'externalUrl' => array(
      'type' => 'VARCHAR'
    ),

    'headKeywords' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'headDescription' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'headTitle' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),

    'timeCreation' => array(
      'type'=>'DATETIME'
    ),
    'timeLastUpdate' => array(
      'type'=>'DATETIME'
    ),
    'timeLastPublish' => array(
      'type'=>'DATETIME'
    ),

    'relatedResource' => array(
      'type'=>'INT'
    ),
    'legend' => array(
      'type'=>'INT'
    ),
    'KML' => array(
      'type'=>'INT'
    ),
    'mapType' => array(
      'type'=>'VARCHAR'
    ),
    'drawLine' => array(
      'type'=>'BOOLEAN'
    ),
    'weight' => array(
      'type'=>'INT'
    ),
    'storyName' => array(
      'type'=>'VARCHAR'
    ),
    'terms' => array(
      'type'=>'VARCHAR'
    )
  );

  static $extraFilters = array(
    'updatedfrom' => ' timeLastUpdate > FROM_UNIXTIME(?) '

  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
