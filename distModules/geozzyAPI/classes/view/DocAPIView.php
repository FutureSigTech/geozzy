<?php

Cogumelo::load('coreView/View.php');
Cogumelo::autoIncludes();

/**
* Clase Master to extend other application methods
*/
class DocAPIView extends View
{

  function __construct($baseDir){
    parent::__construct($baseDir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {
    return true;
  }

  function doc(){
    $this->template->setTpl('doc.tpl', 'geozzyAPI');
    $this->template->assign('swaggerLocation', '/vendor/manual/swagger-ui-2.0.24/');    
    $this->template->exec();
  }

}


