<?php


class RExtStoryStepController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextStoryStep(), 'rextStoryStep_' );

    $this->numericFields = array();
  }

  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
   public function getRExtData( $resId = false ) {
     $rExtData = false;

     // @todo Esto ten que controlar os idiomas

     if( $resId === false ) {
       $resId = $this->defResCtrl->resObj->getter('id');
     }

     $rExtModel = new RExtStoryStepModel();
     $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ), 'affectsDependences' => array( 'FiledataModel' ) ) );
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

       $fileDep = $rExtObj->getterDependence( 'storystepLegend' );
       if( $fileDep !== false ) {
         foreach( $fileDep as $fileModel ) {
           $rExtData[ 'storystepLegend' ] = $fileModel->getAllData( 'onlydata' );
         }
       }
     }



     return $rExtData;
   }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {

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
      'storystepLegend' => array(
        'params' => array( 'label' => __( 'Leggend' ), 'type' => 'file', 'id' => 'stepLegend',
        'placeholder' => 'Escolle unha imaxe', 'destDir' => RExtStoryStepModel::$cols['storystepLegend']['uploadDir'] ),
        'rules' => array( 'minfilesize' => '1024', 'maxfilesize' => '2097152', 'accept' => 'image/png' )
      ),
      'storystepMapType' => array(
        'params' => array( 'label' => __( 'Map type' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'storystepMapType' )
        )
      ),
      'storystepResource' => array(
        'params' => array( 'label' => __( 'Related resource' ), 'type' => 'select',
          'options' => $allRes
        )
      )
    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Valadaciones extra
    // $form->setValidationRule( 'hotelName_'.$form->langDefault, 'required' );

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

    /*******************************************************************
     * Importante: Guardar la lista de campos del RExt en 'FieldNames' *
     *******************************************************************/
    //$rExtFieldNames[] = 'FieldNames';
    $form->setField( $this->addPrefix( 'FieldNames' ), array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );

    $form->saveToSession();

    return( $rExtFieldNames );
  } // function manipulateForm()



  /**
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
   */
   public function getFormBlockInfo( FormController $form ) {

     $formBlockInfo = parent::getFormBlockInfo( $form );
     $templates = $formBlockInfo['template'];

     $templates['basic'] = new Template();
     $templates['basic']->setTpl( 'rExtFormBlock.tpl', 'rextStoryStep' );
     $templates['basic']->assign( 'rExt', $formBlockInfo );
     $templates['basic']->addClientScript('js/rextStoryStep.js', 'rextStoryStep');

     $formBlockInfo['template'] = $templates;

     return $formBlockInfo;
   }



  /**
   * Validaciones extra previas a usar los datos
   *
   * @param $form FormController
   */
  // parent::resFormRevalidate( $form );



  /**
   * Creación-Edición-Borrado de los elementos de la extension
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {

    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      $this->rExtModel = new RExtStoryStepModel( $valuesArray );
      if( $this->rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }

    $fileField = $this->addPrefix( 'storystepLegend' );
    if( !$form->existErrors() && $form->isFieldDefined( $fileField ) ) {

      $this->defResCtrl->setFormFiledata( $form, $fileField, 'storystepLegend', $this->rExtModel );
      $this->rExtModel->save();
    }

    if( !$form->existErrors() && $this->taxonomies) {
      foreach( $this->taxonomies as $tax ) {
        $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
        if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
          $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
        }
      }
    }

    if( !$form->existErrors() ) {
      $saveResult = $this->rExtModel->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }
  }



  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  // parent::resFormSuccess( $form, $resource )



  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
   //parent::getViewBlockInfo();


} // class RExtStoryStepController