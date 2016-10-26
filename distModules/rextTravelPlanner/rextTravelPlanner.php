<?php
Cogumelo::load("coreController/Module.php");

class rextTravelPlanner extends Module {

  public $name = "rextTravelPlanner";
  public $version = 1.0;

  public $models = array( 'TravelPlannerModel' );
  public $dependences = array(
    array(
     'id' =>'moment',
     'params' => array( 'moment' ),
     'installer' => 'bower',
     'includes' => array( 'min/moment-with-locales.min.js' )
    ),
    array(
     'id' =>'moment-timezone',
     'params' => array( 'moment-timezone' ),
     'installer' => 'bower',
     'includes' => array( 'builds/moment-timezone-with-data.min.js' )
    ),
    array(
     'id' =>'bootstrap-daterangepicker',
     'params' => array( 'bootstrap-daterangepicker' ),
     'installer' => 'bower',
     'includes' => array( 'daterangepicker.js', 'daterangepicker.css' )
    )
  );
  public $taxonomies = array();

  public $autoIncludeAlways = true;
  public $includesCommon = array(
    'js/view/Templates.js',
    'js/view/TravelPlannerInterfaceView.js',
    'js/view/TravelPlannerDatesView.js',
    'js/view/TravelPlannerPlanView.js',
    'js/view/TravelPlannerResourceView.js',
    'js/model/TravelPlannerModel.js',
    'js/travelPlannerLoader.js',
    'js/TravelPlannerApp.js',
    'controller/RExtTravelPlannerController.php',
    'model/TravelPlannerModel.php'
  );


  public function __construct() {
    // $this->addUrlPatterns( '#^geozzyFavourite/command$#', 'view:RExtFavouriteView::execCommand' );
    $this->addUrlPatterns( '#^api/travelplanner#', 'view:RExtTravelPlannerAPIView::apiQuery' );
    $this->addUrlPatterns( '#^api/doc/travelplanner.json$#', 'view:RExtTravelPlannerAPIView::apiInfoJson' );
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
