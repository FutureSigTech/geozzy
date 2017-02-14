<?php
interface RExtViewInterface {
  /**
   * Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck();

  /**
   * Visualizamos el Recurso
   *
   * @param $resId int ID del recurso
   */
  public function getViewBlockInfo( $resId = false );
}



class RExtViewCore extends View {

  public $defResCtrl = null;
  public $defRTypeCtrl = null;
  public $rExtModule = null;
  public $rExtCtrl = null;
  public $rExtName = 'RExtNameUnknown';
  public $prefix = 'rExt_';

  public function __construct( RTypeController $defRTypeCtrl, Module $rExtModule, $prefix = false ) {
    error_log( 'RExtViewCore: __construct() para '.$rExtModule->name.' - '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );

    if( $defRTypeCtrl ) {
      $this->defRTypeCtrl = $defRTypeCtrl;
      $this->defResCtrl = $defRTypeCtrl->defResCtrl;
    }
    $this->rExtName = $rExtModule->name;
    $this->prefix = ( $prefix ) ? $prefix : $this->rExtName.'_';

    parent::__construct();

    $rExtCtrlClassName = $this->rExtName.'Controller';
    $this->rExtCtrl = new $rExtCtrlClassName( $defRTypeCtrl );
  }

  /**
   * Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {
    error_log( 'RExtViewCore: accessCheck() para '.$this->rExtName );

    return true;
  }

  /**
   * Datos para la visualizacion de la Extension
   *
   * @param $resId int ID del recurso
   */
  public function getViewBlockInfo( $resId = false ) {
    error_log( 'RExtViewCore: getViewBlockInfo('.$resId.') para '.$this->rExtName );

    $rExtViewBlockInfo = $this->rExtCtrl->getViewBlockInfo( $resId );

    return $rExtViewBlockInfo;
  }

} // class RExtViewCore extends View