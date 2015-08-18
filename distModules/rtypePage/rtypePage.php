<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypePage extends Module {

  public $name = 'rtypePage';
  public $version = '1.0';
  public $rext = array( 'rextView' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypePageController.php',
    'view/RTypePageView.php'
  );


  public function __construct() {
  }

}