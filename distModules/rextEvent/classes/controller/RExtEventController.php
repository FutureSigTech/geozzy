<?php
geozzy::load( 'controller/RExtController.php' );
geozzy::load( 'controller/RTypeController.php' );

class RExtEventController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtEventController::__construct' );

    global $C_LANG;
    $this->actLang = $C_LANG;

    parent::__construct( $defRTypeCtrl, new rextEvent(), 'rextEvent_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtEventController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new EventModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );

      // Cargo todos los TAX terms del recurso agrupados por idName de Taxgroup
      $termsGroupedIdName = $this->defResCtrl->getTermsInfoByGroupIdName( $resId );
      if( $termsGroupedIdName !== false ) {
        foreach( $this->taxonomies as $tax ) {
          if( isset( $termsGroupedIdName[ $tax[ 'idName' ] ] ) ) {
            $rExtData[ $tax['idName'] ] = $termsGroupedIdName[ $tax[ 'idName' ] ];
          }
        }
      }
    }

    // error_log( 'RExtEventController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtContactController: manipulateForm()" );
    $rExtFieldNames = array();

    // systemRTypes
    $systemRtypes = Cogumelo::getSetupValue('mod:geozzy:resource:systemRTypes');

    $resourceModel = new ResourceModel();
    $rtypeModel = new resourceTypeModel();

    $rtypeArray = $rtypeModel->listItems(
        array( 'filters' => array( 'idNameExists' => $systemRtypes ) )
    );
    $filterRtype = array();
    while( $rtype = $rtypeArray->fetch() ){
      array_push( $filterRtype, $rtype->getter('id') );
    }

    $elemList = $resourceModel->listItems(
      array( 'filters' => array( 'notInRtype' => $filterRtype ) )
    );
    $allRes = array();
    $allRes['0'] = false;
    while( $elem = $elemList->fetch() ){
      $allRes[ $elem->getter( 'id' ) ] = $elem->getter( 'title' );
    }

    $fieldsInfo = array(
      'initDate' => array(
        'params' => array('type' => 'hidden'),
        'rules' => array( 'maxlength' => 200 )
      ),
      'endDate' => array(
        'params' => array('type' => 'hidden'),
        'rules' => array( 'maxlength' => 200 )
      ),
      'rextEventType' => array(
        'params' => array( 'label' => __( 'Event type' ), 'type' => 'select',  'multiple' => true, 'class' => 'cgmMForm-order',
          'options' => $this->defResCtrl->getOptionsTax( 'rextEventType' )
        )
      ),
      'relatedResource' => array(
        'params' => array( 'label' => __( 'Related resource' ), 'type' => 'select',
          'options' => $allRes
        )
      )
    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Si es una edicion, añadimos el ID y cargamos los datos
    $valuesArray = $this->getRExtData( $form->getFieldValue( 'id' ) );
    if( $valuesArray ) {
      $valuesArray = $this->prefixArrayKeys( $valuesArray );
      $form->setField( $this->addPrefix( 'id' ), array( 'type' => 'reserved', 'value' => null ) );

      // Limpiando la informacion de terms para el form
      if( $this->taxonomies ) {
        foreach( $this->taxonomies as $tax ) {
          $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );

          if( isset( $valuesArray[ $taxFieldName ] ) && is_array( $valuesArray[ $taxFieldName ] ) ) {
            $taxFieldValues = array();
            foreach( $valuesArray[ $taxFieldName ] as $value ) {
              $taxFieldValues[] = ( is_array( $value ) ) ? $value[ 'id' ] : $value;
            }
            $valuesArray[ $taxFieldName ] = $taxFieldValues;
          }
        }
      }


      $form->loadArrayValues( $valuesArray );
    }

    // Add RExt info to form
    foreach( $fieldsInfo as $fieldName => $info ) {
      if( isset( $info[ 'translate' ] ) && $info[ 'translate' ] ) {
        $rExtFieldNames = array_merge( $rExtFieldNames, $form->multilangFieldNames( $fieldName ) );
      }
      else {
        $rExtFieldNames[] = $fieldName;
      }
    }
    $rExtFieldNames[] = 'FieldNames';
    $form->setField( $this->addPrefix( 'FieldNames' ), array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );

    $form->saveToSession();

    return( $rExtFieldNames );
  } // function manipulateForm()


  public function getFormBlockInfo( FormController $form ) {
    // error_log( "RExtContactController: getFormBlockInfo()" );

    $formBlockInfo = array(
      'template' => false,
      'data' => false,
      'dataForm' => false
    );

    $prefixedFieldNames = $this->prefixArray( $form->getFieldValue( $this->addPrefix( 'FieldNames' ) ) );

    $formBlockInfo['dataForm'] = array(
      'formId' => $form->getId(),
      'formFieldsArray' => $form->getHtmlFieldsArray( $prefixedFieldNames ),
      'formFields' => $form->getHtmlFieldsAndGroups()
    );

    if( $form->getFieldValue( 'id' ) ) {
      $formBlockInfo['data'] = $this->getRExtData();
    }


    $templates['full'] = new Template();
    //$templates['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
    $templates['full']->setTpl( 'rExtViewBlock.tpl', 'rextEvent' );
    $templates['full']->assign( 'rExtName', $this->rExtName );
    $templates['full']->assign( 'rExt', $formBlockInfo );
    $templates['full']->addClientScript('js/rextEvent.js', 'rextEvent');

    $prevContent = '<style type="text/css">.bootstrap-datetimepicker-widget table th{background: none;}</style>';
    $templates['full']->assign( 'prevContent', $prevContent );

    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }


  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RExtContactController: resFormRevalidate()" );

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtEventController: resFormProcess()" );
    if( !$form->existErrors() ) {

      //$numericFields = array( 'averagePrice', 'capacity' );
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );
      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      if( $form->isFieldDefined( 'rextEvent_initDate' ) && is_numeric( $form->getFieldValue( 'rextEvent_initDate' ) ) ) {
        $valuesArray[ 'initDate' ] = gmdate( "Y-m-d H:i:s", $form->getFieldValue( 'rextEvent_initDate' ) );
      }
      if( $form->isFieldDefined( 'rextEvent_endDate' ) && is_numeric( $form->getFieldValue( 'rextEvent_endDate' ) ) ) {
        $valuesArray[ 'endDate' ] = gmdate( "Y-m-d H:i:s", $form->getFieldValue( 'rextEvent_endDate' ) );
      }

      // error_log( 'NEW RESOURCE: ' . print_r( $valuesArray, true ) );
      $rExtModel = new EventModel( $valuesArray );
      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }

    if( !$form->existErrors() ) {
      foreach( $this->taxonomies as $tax ) {
        $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
        if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
          $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
        }
      }
    }

    if( !$form->existErrors() ) {
      $saveResult = $rExtModel->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtContactController: resFormSuccess()" );

  }


  /**
    Datos y template por defecto de la extension
   */
  public function getViewBlockInfo() {
    // error_log( "RExtContactController: getViewBlockInfo()" );

    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData()
    );



    if( $rExtViewBlockInfo['data'] ) {
      // TODO: esto será un campo da BBDD
      $rExtViewBlockInfo['data'] = $this->defResCtrl->getTranslatedData( $rExtViewBlockInfo['data'] );

      if (isset($rExtViewBlockInfo['data']['date'])){
        $template = new Template();

        $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );


        $rExtViewBlockInfo['template'] = array( 'full' => $template );
      }
    }

    return $rExtViewBlockInfo;
  }

} // class RExtEventController
