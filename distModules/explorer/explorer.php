<?php


Cogumelo::load("coreController/Module.php");


class explorer extends Module
{
  public $name = "explorer";
  public $version = "1.0";



  public $dependences = array(
    array(
     "id" =>"underscore",
     "params" => array("underscore#1.8.3"),
     "installer" => "bower",
     "includes" => array("underscore-min.js")
   ),
    array(
     "id" =>"backbonejs",
     "params" => array("backbone#1.1.2"),
     "installer" => "bower",
     "includes" => array("backbone.js")
    ),
    array(
     "id" =>"backbone-fetch-cache",
     "params" => array("backbone-fetch-cache#1.3.0"),
     "installer" => "bower",
     "includes" => array("backbone.fetch-cache.min.js")
    ),
    array(
     "id" =>"Backbone.localStorage",
     "params" => array("Backbone.localStorage"),
     "installer" => "bower",
     "includes" => array("backbone.localStorage-min.js")
    ),
     array(
      "id" =>"backbone.obscura",
      "params" => array("backbone.obscura#1.0.1"),
      "installer" => "bower",
      "includes" => array("backbone.obscura.js")
     )

  );



  public $includesCommon = array(
    'js/models/ExplorerResourceModel.js',
    'js/collections/ExplorerResourceCollection.js',
    'js/Explorer.js'
  );


  function __construct() {
    $this->addUrlPatterns( '#^api/explorer/(.*)#', 'view:ExplorerAPIView::explorer' );
    $this->addUrlPatterns( '#^api/explorer.json#', 'view:ExplorerAPIView::explorerJson' ); // Main swagger JSON
    $this->addUrlPatterns( '#^api/explorerList$#', 'view:ExplorerAPIView::explorerList' );
    $this->addUrlPatterns( '#^api/explorerList.json$#', 'view:ExplorerAPIView::explorerListJson' ); // Main swagger JSON
  }

}
