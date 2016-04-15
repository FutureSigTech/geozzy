<?php
Cogumelo::load('coreView/View.php');
rtypeEvent::load('controller/RTypeEventController.php');
geozzy::load('controller/ResourceController.php');
geozzy::load( 'view/GeozzyResourceView.php' );


class RTypeEventView extends View
{

  private $defResCtrl = null;
  private $rTypeCtrl = null;

  public function __construct( $defResCtrl = null ){
    parent::__construct( $baseDir = false );

    $this->defResCtrl = $defResCtrl;
    $this->rTypeCtrl = new RTypeEventController( $defResCtrl );
  }

  function accessCheck() {

    return true;

  }

  /**
    Defino un formulario con su TPL como Bloque
   */
  public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {
    // error_log( "RTypeEventView: getFormBlock()" );

    $form = $this->defResCtrl->getFormObj( $formName, $urlAction, $valuesArray );

    $this->template->assign( 'formOpen', $form->getHtmpOpen() );

    $this->template->assign( 'formFieldsArray', $form->getHtmlFieldsArray() );

    $this->template->assign( 'formFields', $form->getHtmlFieldsAndGroups() );

    $this->template->assign( 'formClose', $form->getHtmlClose() );
    $this->template->assign( 'formValidations', $form->getScriptCode() );

    $this->template->setTpl( 'resourceFormBlock.tpl', 'geozzy' );

    return( $this->template );
  } // function getFormBlock()



  /**
    Proceso formulario
   */
  public function actionResourceForm() {
    // error_log( "RTypeEventView: actionResourceForm()" );

    // Se construye el formulario con sus datos y se realizan las validaciones que contiene
    $form = $this->defResCtrl->resFormLoad();

    if( !$form->existErrors() ) {
      // Validaciones extra previas a usar los datos del recurso base
      $this->defResCtrl->resFormRevalidate( $form );
    }

    // Opcional: Validaciones extra previas de elementos externos al recurso base

    if( !$form->existErrors() ) {
      // Creación-Edición-Borrado de los elementos del recurso base
      $resource = $this->defResCtrl->resFormProcess( $form );
    }

    // Opcional: Creación-Edición-Borrado de los elementos externos al recurso base

    if( !$form->existErrors()) {
      // Volvemos a guardar el recurso por si ha sido alterado por alguno de los procesos previos
      $saveResult = $resource->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    // Enviamos el OK-ERROR a la BBDD y al formulario
    $this->defResCtrl->resFormSuccess( $form, $resource );
  } // function actionResourceForm()


  /**
   * Creacion de formulario de microevento
   */
  public function createModalForm( $urlParams = false ) {

    $resCtrl = new ResourceController();
    $rtypeModel = new ResourcetypeModel();

    $formName = 'eventCreate';
    $urlAction = '/rtypeEvent/event/sendevent';

    $rtype = $rtypeModel->listItems( array( 'filters' => array('idName' => 'rtypeEvent') ) )->fetch();
    $valuesArray['rTypeId'] = $rtype->getter('id');

    $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, false, $valuesArray );
    $form = $formBlockInfo['objForm'];

    $form->setFieldParam('published', 'type', 'reserved');
    $form->setFieldParam('published', 'value', '1');
    $urlAliasLang = $form->multilangFieldNames('urlAlias');
    foreach ($urlAliasLang as $key => $field) {
      $form->removeField( $field);
    }
    $form->removeField('externalUrl');
    $form->removeValidationRules('published');

    $formBlockInfo['dataForm'] = array(
      'formOpen' => $form->getHtmpOpen(),
      'formFieldsArray' => $form->getHtmlFieldsArray(),
      'formFieldsHiddenArray' => array(),
      'formFields' => $form->getHtmlFieldsAndGroups(),
      'formClose' => $form->getHtmlClose(),
      'formValidations' => $form->getScriptCode()
    );

    $formBlockInfo['template']['miniFormModal']->addToBlock('rextEventBlock', $formBlockInfo['ext']['rextEvent']['template']['full']);
    $formBlockInfo['template']['miniFormModal']->assign( 'res', $formBlockInfo );
    $formBlockInfo['template']['miniFormModal']->exec();

  }

  public function resourceShowForm( $formName, $urlAction, $valuesArray = false, $resCtrl = false ) {

    if( !$resCtrl ) {
      $resCtrl = new ResourceController();
    }

    $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, $valuesArray );

    $form = $formBlockInfo['objForm'];
    $form->setFieldParam('published', 'type', 'reserved');
    $form->setFieldParam('published', 'value', '1');
    $urlAliasLang = $form->multilangFieldNames('urlAlias');
    foreach ($urlAliasLang as $key => $field) {
      $form->removeField( $field);
    }
    $form->removeValidationRules('published');

    $formBlockInfo['template']['miniFormModal']->assign( 'res', $formBlockInfo );
    $formBlockInfo['template']['miniFormModal']->exec();
  }


  /**
   * Edicion de Recursos
   */
  public function editForm( $urlParams = false ) {
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('resource:edit', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $recursoData = false;
    $urlParamTopic = false;
    $topicItem = false;
    $typeItem = false;

    /* Validamos os parámetros da url e obtemos un array de volta*/
    $validation = array( 'resourceId'=> '#^\d+$#');
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );

    $resCtrl = new ResourceController();
    $recursoData = $resCtrl->getResourceData( $urlParamsList['resourceId'] );

    $recursoData['typeReturn'] = 'rtypeEvent';

    if( $recursoData ) {
      $this->resourceShowForm( 'resourceEdit', '/admin/resource/sendresource', $recursoData, $resCtrl );
    }
    else {
      cogumelo::error( 'Imposible acceder al recurso indicado.' );
    }
  } // function resourceEditForm()


  public function sendModalResourceForm() {

    $resourceView = new GeozzyResourceView();
    $resource = null;

    // Se construye el formulario con sus datos y se realizan las validaciones que contiene
    $form = $resourceView->defResCtrl->resFormLoad();

    if( !$form->existErrors() ) {
      // Validar y guardar los datos
      $resource = $resourceView->actionResourceFormProcess( $form );

    }

    if( !$form->existErrors() ) {

      $resCtrl = new ResourceController();

      $form->removeSuccess( 'redirect' );
      $form->setSuccess( 'jsEval', ' successResourceForm( { '.
        ' id : "'.$resource->getter('id').'",'.
        ' title: "'.$resource->getter('title_'.$form->langDefault).'" });'
      );
    }

    // Enviamos el OK-ERROR a la BBDD y al formulario
    $resourceView->actionResourceFormSuccess( $form, $resource );
  }

} // class RTypeEventView
