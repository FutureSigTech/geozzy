<?php


class FormTest extends Module {

  public $name = 'FormTest';
  public $version = '1';

  public $dependences = [];

  public $includesCommon = [];



  public function __construct() {
    $this->addUrlPatterns( '#^formTest/viewForm#', 'view:FormTestView::viewForm' );
    $this->addUrlPatterns( '#^formTest/sendForm#', 'view:FormTestView::sendForm' );
  }


  public function moduleRc() {
  }
}
