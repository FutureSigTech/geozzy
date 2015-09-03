<?php
Cogumelo::load('coreView/View.php');
geozzy::load('controller/ResourceController.php');



class GeozzyResourceView extends View {

  public $defResCtrl = null;
  public $rTypeCtrl = null;

  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );

    common::autoIncludes();
    form::autoIncludes();
    user::autoIncludes();
    filedata::autoIncludes();

    $this->defResCtrl = new ResourceController();
    // error_log( 'this->defResCtrl '.print_r( $this->defResCtrl, true ) );
  }

  /**
   * Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {

    return true;
  }





  /**
   * Defino el formulario
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Obj-Form
   **/
  public function getFormObj( $formName, $urlAction, $valuesArray = false ) {
    error_log( "GeozzyResourceView: getFormObj()" );

    $form = $this->defResCtrl->getFormObj( $formName, $urlAction, $valuesArray );

    $this->rTypeCtrl = $this->defResCtrl->getRTypeCtrl( $form->getFieldValue( 'rTypeId' ) );
    if( $this->rTypeCtrl ) {
      $rTypeFieldNames = $this->rTypeCtrl->manipulateForm( $form );
      // error_log( 'rTypeFieldNames: '.print_r( $rTypeFieldNames, true ) );
    }

    // Una vez que lo tenemos completamente definido, guardamos el form en sesion
    $form->saveToSession();

    return $form;
  } // function getFormObj()


  /**
   * Defino el formulario y creo su Bloque con su TPL
   *
   * @param $form object Form
   *
   * @return Obj-Template
   **/
  public function formToTemplate( $form ) {
    error_log( "GeozzyResourceView: getFormBlock()" );

    $template = new Template();

    $template->assign( 'formOpen', $form->getHtmpOpen() );

    $template->assign( 'formFieldsArray', $form->getHtmlFieldsArray() );

    $template->assign( 'formFields', $form->getHtmlFieldsAndGroups() );

    $template->assign( 'rTypeName', $form->getFieldValue( 'rTypeName' ) );

    $template->assign( 'rTypeFieldNames', $form->getFieldValue( 'rTypeFieldNames' ) );

    $template->assign( 'formClose', $form->getHtmlClose() );
    $template->assign( 'formValidations', $form->getScriptCode() );

    $template->setTpl( 'resourceFormBlock.tpl', 'geozzy' );

    return( $template );
  } // function getFormBlock()




  /**
   * Defino el formulario y creo su Bloque con su TPL
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Obj-Template
   **/
  public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {
    error_log( "GeozzyResourceView: getFormBlock()" );


    $form = $this->getFormObj( $formName, $urlAction, $valuesArray );

    $template = $this->formToTemplate( $form );

    return( $template );
  } // function getFormBlock()





  /**
   * Action del formulario
   */
  public function actionResourceForm() {
    error_log( "GeozzyResourceView: actionResourceForm()" );
    $resource = null;

    // Se construye el formulario con sus datos y se realizan las validaciones que contiene
    $form = $this->defResCtrl->resFormLoad();

    if( !$form->existErrors() ) {
      // Validar y guardar los datos
      $resource = $this->actionResourceFormProcess( $form );
    }

    if( !$form->existErrors() && $resource ) {
      // Enviamos el OK-ERROR a la BBDD y al formulario
      $this->actionResourceFormSuccess( $form, $resource );
    }
  } // function actionResourceForm()



  /**
   * Action del formulario Process
   */
  public function actionResourceFormProcess( $form ) {
    error_log( "GeozzyResourceView: actionResourceFormProcess()" );
    $resource = null;

    if( !$form->existErrors() ) {
      // Validaciones extra previas a usar los datos del recurso base
      $this->defResCtrl->resFormRevalidate( $form );
    }

    $this->rTypeCtrl = $this->defResCtrl->getRTypeCtrl( $form->getFieldValue( 'rTypeId' ) );

    // Validaciones extra previas de elementos externos al recurso base
    if( $this->rTypeCtrl && !$form->existErrors() ) {
      $this->rTypeCtrl->resFormRevalidate( $form );
    }

    if( !$form->existErrors() ) {
      // Creación-Edición-Borrado de los elementos del recurso base
      $resource = $this->defResCtrl->resFormProcess( $form );
    }

    // Creación-Edición-Borrado de los elementos externos al recurso base
    if( $this->rTypeCtrl && !$form->existErrors() ) {
      $this->rTypeCtrl->resFormProcess( $form, $resource );
    }

    if( !$form->existErrors()) {
      // Volvemos a guardar el recurso por si ha sido alterado por alguno de los procesos previos
      $saveResult = $resource->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    // Preparaciones del Success de los elementos externos al recurso base
    if( $this->rTypeCtrl && !$form->existErrors() ) {
      $this->rTypeCtrl->resFormSuccess( $form, $resource );
    }

    return $resource;
  } // function actionResourceFormProcess()



  /**
   * Action del formulario Success
   */
  public function actionResourceFormSuccess( $form, $resource ) {
    error_log( "GeozzyResourceView: actionResourceFormSuccess()" );

    // Enviamos el OK-ERROR a la BBDD y al formulario
    $this->defResCtrl->resFormSuccess( $form, $resource );
  } // function actionResourceFormSuccess()





  /**
   * Visualizamos el Recurso
   *
   * @param $resId int ID del recurso
   */
  public function showResource( $resId = false ) {
    error_log( "GeozzyResourceView: showResource()" );

    $resObj = false;
    $resBlock = false;
    $htmlMsg = '';

    $resData = $this->defResCtrl->getResourceData( $resId, true ); // true -> translated version
    if( $resData ) {
      // error_log( '$resData === ' . print_r( $resData, true ) );

      $loadFields = array( 'headKeywords', 'headDescription', 'headTitle', 'title' );
      foreach( $loadFields as $field ) {
        $this->template->assign( $field, $resData[ $field ] );
      }

      $resBlock = $this->defResCtrl->getViewBlock( $resData );
      $this->template->setBlock( 'resourceBlock', $resBlock );
      error_log( 'Hai Recurso - Hai Template' );
    }
    else {
      $htmlMsg = '<span class="error">' . __('Error: Imposible mostrar el recurso ') . $resId . '</span>';
      $this->template->assign( 'headTitle', __('Unknown Resource') );
      $this->template->assign( 'htmlMsg', $htmlMsg );
      error_log( 'NON hai Recurso: ' . $htmlMsg );
    }

    $this->template->setTpl( 'resourceViewPage.tpl', 'geozzy' );
    $this->template->exec();
  } // function showResource( $resId = false )

} // class ResourceView extends Vie
