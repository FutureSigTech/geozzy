<?php
Cogumelo::load( 'coreController/Module.php' );


class initResources extends Module {

  public $includesCommon = array();


  public function __construct() {
    $this->addUrlPatterns( '#^initResources$#', 'view:InitResourcesView::generateResources' );
  }


  public function moduleRc() {

  }

}
