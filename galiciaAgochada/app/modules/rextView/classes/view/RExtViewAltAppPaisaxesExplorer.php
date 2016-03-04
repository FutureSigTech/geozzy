<?php
Cogumelo::load('view/ExplorerPageView.php');

class RExtViewAltAppPaisaxesExplorer {

  public $defRExtViewCtrl = false;
  public $defRTypeCtrl = false;
  public $defResCtrl = false;


  public function __construct( $defRExtViewCtrl ){
    //error_log( 'RExtViewAltAppPaisaxesExplorer::__construct' );
    $this->defRExtViewCtrl = $defRExtViewCtrl;
    $this->defRTypeCtrl = $this->defRExtViewCtrl->defRTypeCtrl;
    $this->defResCtrl = $this->defRTypeCtrl->defResCtrl;
  }

  /**
    Alteramos la visualizacion el Recurso
   */
  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    //error_log( "RExtViewAltAppPaisaxesExplorer: alterViewBlockInfo( viewBlockInfo, $templateName )" );
    $explorerView = new ExplorerPageView(false);
    $explorerView->paisaxesExplorer();
    $viewBlockInfo['footer'] = false;
    $viewBlockInfo['template']['full'] = $explorerView->template;

    return $viewBlockInfo;
  }

} // class RExtViewAltAppPaisaxesExplorer
