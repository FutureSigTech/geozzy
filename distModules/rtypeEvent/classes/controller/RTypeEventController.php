<?php
rextEvent::autoIncludes();

class RTypeEventController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    // error_log( 'RTypeEventController::__construct' );

    parent::__construct( $defResCtrl, new rtypeEvent() );

  }


  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    //error_log( "RTypeEventController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    // Extensión alojamiento
    $rTypeExtNames[] = 'rextEvent';
    $this->eventCtrl = new RExtEventController( $this );
    $rExtFieldNames = $this->eventCtrl->manipulateForm( $form );

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Añadir validadores extra
    // $form->setValidationRule( 'hotelName_'.$form->langDefault, 'required' );

    return( $rTypeFieldNames );
  } // function manipulateForm()



  public function getFormBlockInfo( FormController $form ) {
    //error_log( "RTypeEventController: getFormBlockInfo()" );

    $formBlockInfo = array(
      'template' => false,
      'data' => false,
      'dataForm' => false,
      'ext' => array()
    );

    $formBlockInfo['dataForm'] = array(
      'formOpen' => $form->getHtmpOpen(),
      'formFieldsArray' => $form->getHtmlFieldsArray(),
      'formFieldsHiddenArray' => array(),
      'formFields' => $form->getHtmlFieldsAndGroups(),
      'formClose' => $form->getHtmlClose(),
      'formValidations' => $form->getScriptCode()
    );

    if( $resId = $form->getFieldValue( 'id' ) ) {
      $formBlockInfo['data'] = $this->defResCtrl->getResourceData( $resId );
    }

    $this->eventCtrl = new RExtEventController( $this );
    $accomViewInfo = $this->eventCtrl->getFormBlockInfo( $form );
    $viewBlockInfo['ext'][ $this->eventCtrl->rExtName ] = $accomViewInfo;

    // TEMPLATE panel principa del form. Contiene los elementos globales del form.
    $templates['formBase'] = new Template();
    $templates['formBase']->setTpl( 'rTypeFormBase.tpl', 'geozzy' );
    $templates['formBase']->assign( 'title', __('Main Resource information') );
    $templates['formBase']->assign( 'res', $formBlockInfo );

    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'title' ),
      $form->multilangFieldNames( 'shortDescription' ),
      $form->multilangFieldNames( 'mediumDescription' ),
      $form->multilangFieldNames( 'content' )
    );
    $formFieldsNames[] = 'externalUrl';
    $formFieldsNames[] = 'topics';
    $formFieldsNames[] = 'starred';
    $formFieldsNames[] = 'rTypeIdName';
    $templates['formBase']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel estado de publicacion
    $templates['publication'] = new Template();
    $templates['publication']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['publication']->assign( 'title', __( 'Publication' ) );
    $templates['publication']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'published', 'weight' );
    $templates['publication']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel SEO
    $templates['seo'] = new Template();
    $templates['seo']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['seo']->assign( 'title', __( 'SEO' ) );
    $templates['seo']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'urlAlias' ),
      $form->multilangFieldNames( 'headKeywords' ),
      $form->multilangFieldNames( 'headDescription' ),
      $form->multilangFieldNames( 'headTitle' )
    );
    $templates['seo']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel image
    $templates['image'] = new Template();
    $templates['image']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['image']->assign( 'title', __( 'Select a image' ) );
    $templates['image']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'image' );
    $templates['image']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel categorization
    $templates['categorization'] = new Template();
    $templates['categorization']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['categorization']->assign( 'title', __( 'Categorization' ) );
    $templates['categorization']->assign( 'res', $formBlockInfo );
    $formFieldsNames = $this->eventCtrl->prefixArray(array( 'EventType', 'EventView'));
    $templates['categorization']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel cuadro informativo
    $templates['info'] = new Template();
    $templates['info']->setTpl( 'rTypeFormInfoPanel.tpl', 'geozzy' );
    $templates['info']->assign( 'title', __( 'Information' ) );
    $templates['info']->assign( 'res', $formBlockInfo );

    $resourceType = new ResourcetypeModel();
    $type = $resourceType->listItems(array('filters' => array('id' => $formBlockInfo['data']['rTypeId'])))->fetch();
    if ($type){
      $templates['info']->assign( 'rType', $type->getter('name_es') );
    }

    $timeCreation = gmdate('d/m/Y', strtotime($formBlockInfo['data']['timeCreation']));

    $templates['info']->assign( 'timeCreation', $timeCreation );
    if (isset($formBlockInfo['data']['userUpdate'])){
      $userModel = new UserModel();
      $userUpdate = $userModel->listItems( array( 'filters' => array('id' => $formBlockInfo['data']['userUpdate']) ) )->fetch();
      $userUpdateName = $userUpdate->getter('name');
      $timeLastUpdate = gmdate('d/m/Y', strtotime($formBlockInfo['data']['timeLastUpdate']));
      $templates['info']->assign( 'timeLastUpdate', $timeLastUpdate.' ('.$userUpdateName.')' );
    }
    if (isset($formBlockInfo['data']['averageVotes'])){
      $templates['info']->assign( 'averageVotes', $formBlockInfo['data']['averageVotes']);
    }
    /* Temáticas */
    if (isset($formBlockInfo['data']['topicsName'])){
      $templates['info']->assign( 'resourceTopicList', $formBlockInfo['data']['topicsName']);
    }
    $templates['info']->assign( 'res', $formBlockInfo );
    $templates['info']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );
    // COL8
    $templates['adminFull']->addToBlock( 'col8', $templates['formBase'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['contact'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['social'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['reservation'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['location'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['seo'] );
    // COL4
    $templates['adminFull']->addToBlock( 'col4', $templates['publication'] );
    $templates['adminFull']->addToBlock( 'col4', $templates['image'] );
    $templates['adminFull']->addToBlock( 'col4', $templates['categorization'] );
    $templates['adminFull']->addToBlock( 'col4', $templates['info'] );

    // TEMPLATE en bruto con todos los elementos del form
    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rTypeFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'res', $formBlockInfo );


    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }


  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    //error_log( "RTypeEventController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->eventCtrl = new RExtEventController( $this );
      $this->eventCtrl->resFormRevalidate( $form );

    }

  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    //error_log( "RTypeEventController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->eventCtrl = new RExtEventController( $this );
      $this->eventCtrl->resFormProcess( $form, $resource );

    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    //error_log( "RTypeEventController: resFormSuccess()" );

    $this->eventCtrl = new RExtEventController( $this );
    $this->eventCtrl->resFormSuccess( $form, $resource );

  }


  /**
    Preparamos los datos para visualizar el Recurso
   **/
  public function getViewBlockInfo() {
    // error_log( "RTypeEventController: getViewBlockInfo()" );

    $viewBlockInfo = array(
      'template' => false,
      'data' => $this->defResCtrl->getResourceData( false, true ),
      'ext' => array()
    );

    $template = new Template();
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeEvent' );

    $this->eventCtrl = new RExtEventController( $this );
    $accomViewInfo = $this->eventCtrl->getViewBlockInfo();
    $viewBlockInfo['ext'][ $this->eventCtrl->rExtName ] = $accomViewInfo;

    $template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );

    $taxtermModel = new TaxonomytermModel();

    /* Recuperamos todos los términos de la taxonomía servicios*/
    $services = $this->defResCtrl->getOptionsTax( 'EventServices' );
    foreach( $services as $serviceId => $serviceName ) {
      $service = $taxtermModel->listItems(array('filters'=> array('id' => $serviceId)))->fetch();
      /*Quitamos los términos de la extensión que no se usan en este proyecto*/
        $allServices[$serviceId]['name'] = $serviceName;
        $allServices[$serviceId]['idName'] = $service->getter('idName');
        $allServices[$serviceId]['icon'] = $service->getter('icon');
    }
    $template->assign('allServices', $allServices);

    /* Recuperamos todos los términos de la taxonomía instalaciones*/
    $facilities = $this->defResCtrl->getOptionsTax( 'EventFacilities' );
    foreach( $facilities as $facilityId => $facilityName ) {
      $facility = $taxtermModel->listItems(array('filters'=> array('id' => $facilityId)))->fetch();
      /*Quitamos los términos de la extensión que no se usan en este proyecto*/
      if ($facility->getter('idName') !== 'bar' && $facility->getter('idName') !== 'lavadora'){
        $allFacilities[$facilityId]['name'] = $facilityName;
        $allFacilities[$facilityId]['idName'] = $facility->getter('idName');
        $allFacilities[$facilityId]['icon'] = $facility->getter('icon');
      }
    }
    $template->assign('allFacilities', $allFacilities);

    if( $accomViewInfo ) {
      if( $accomViewInfo['template'] ) {
        foreach( $accomViewInfo['template'] as $nameBlock => $templateBlock ) {
          $template->addToBlock( 'rextEventBlock', $templateBlock );
        }
      }
    }
    else {
      $template->assign( 'rextEventBlock', false );
    }

    $viewBlockInfo['template'] = array( 'full' => $template );

    return $viewBlockInfo;
  }

} // class RTypeEventController