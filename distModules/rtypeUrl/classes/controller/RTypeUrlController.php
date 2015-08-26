<?php
rextUrl::autoIncludes();


class RTypeUrlController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeUrlController::__construct' );

    parent::__construct( $defResCtrl, new rtypeUrl() );
  }


  private function newRExtContr() {

    return new RExtUrlController( $this );
  }


  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeUrlController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    $rTypeExtNames[] = 'rextUrl';
    $this->rExtCtrl = $this->newRExtContr();
    $rExtFieldNames = $this->rExtCtrl->manipulateForm( $form );

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Valadaciones extra

    // Eliminamos campos del formulario de recurso que no deseamos
    $removeFields = array_merge(
      $form->multilangFieldNames( 'content' ),
      $form->multilangFieldNames( 'datoExtra1' ),
      $form->multilangFieldNames( 'datoExtra2' ),
      array( 'collections', 'addCollections', 'multimediaGalleries', 'addMultimediaGalleries',
        'topics', 'starred', 'locLat', 'locLon', 'defaultZoom' )
    );
    $form->removeField( $removeFields );
    $form->saveToSession();

    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypeUrlController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = $this->newRExtContr();
      $this->rExtCtrl->resFormRevalidate( $form );
    }

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeUrlController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = $this->newRExtContr();
      $this->rExtCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeUrlController: resFormSuccess()" );

    $this->rExtCtrl = $this->newRExtContr();
    $this->rExtCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeUrlController: getViewBlock()" );
    $template = false;

    $template = $resBlock;
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeUrl' );

    $this->rExtCtrl = $this->newRExtContr();
    $urlBlock = $this->rExtCtrl->getViewBlock( $resBlock );

    if( $urlBlock ) {
      $template->addToBlock( 'rextUrl', $urlBlock );
      $template->assign( 'rExtBlockNames', array( 'rextUrl' ) );
    }
    else {
      $template->assign( 'rextUrl', false );
      $template->assign( 'rExtBlockNames', false );
    }

    return $template;
  }

} // class RTypeUrlController
