<?php


interface RExtInterface {

  // Defino el formulario
  public function manipulateForm( FormController $form );

  // Validaciones extra previas a usar los datos del recurso base
  public function resFormRevalidate( FormController $form );

  // Creación-Edición-Borrado de los elementos del recurso base. Iniciar transaction
  public function resFormProcess( FormController $form, ResourceModel $resource );

  // Enviamos el OK-ERROR a la BBDD y al formulario. Finalizar transaction
  public function resFormSuccess( FormController $form, ResourceModel $resource );

  // Visualizamos el Recurso
  public function getViewBlock( ResourceModel $resource, Template $resBlock );

} // interface RExtInterface


class RExtController {

  public $prefix = 'rExt_';

  public $defRTypeCtrl = null;
  public $defResCtrl = null;
  public $rExtModule = null;
  public $taxonomies = false;


  public function __construct( $defRTypeCtrl, $rExtModule, $prefix ){
    error_log( 'RExtController::__construct' );

    $this->defRTypeCtrl = $defRTypeCtrl;
    $this->defResCtrl = $defRTypeCtrl->defResCtrl;
    $this->prefix = $prefix;

    $this->rExtModule = $rExtModule;
    if( property_exists( $this->rExtModule, 'taxonomies' ) && is_array( $this->rExtModule->taxonomies )
      && count( $this->rExtModule->taxonomies ) > 0 )
    {
      $this->taxonomies = $this->rExtModule->taxonomies;
    }
  }


  public function getRExtFormValues( $formValuesArray, $numericFields = array() ) {
    error_log( "RExtController: getRExtFormValues()" );
    $valuesArray = array();

    foreach( $formValuesArray as $key => $value ) {
      $newKey = $this->delPrefix( $key );
      if( $newKey !== $key ) {
        if( $formValuesArray[ $key ] === '' && in_array( $newKey, $numericFields ) ) {
          $valuesArray[ $newKey ] = null;
        }
        else {
          $valuesArray[ $newKey ] = $formValuesArray[ $key ];
        }
      }
    }

    return ( count( $valuesArray ) < 1 ) ? false : $valuesArray;
  }

  public function prefixArrayKeys( $valuesArray ) {
    if( is_array( $valuesArray ) ) {
      $prefixArray = array();
      foreach( $valuesArray as $key => $value ) {
        $prefixArray[ $this->addPrefix( $key ) ] = $value;
      }
    }
    else {
      $prefixArray = $valuesArray;
    }

    return $prefixArray;
  }

  public function addPrefix( $text ) {

    return $this->prefix . $text;
  }

  public function delPrefix( $text ) {
    if( strpos( $text, $this->prefix ) === 0 ) {
      $text = substr( $text, strlen( $this->prefix ) );
    }

    return $text;
  }

} // class RExtController