<?php

Cogumelo::load('coreController/I18nController.php');

class RExtSocialNetworkController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextSocialNetwork(), 'rExtSocialNetwork_' );
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

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtSocialNetworkModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    if( $rExtList ) {
      $rExtObj = $rExtList->fetch();
    }

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );
      $rExtDataFields = $rExtObj->getCols();
      foreach( $rExtDataFields as $key => $value ) {
        // error_log( "=== Res Col: $key" );
        if( !isset( $rExtData[ $key ] ) ) {
          $rExtData[ $key ] = $rExtObj->getter( $key );
        }
      }
    }

    // error_log( 'RExtSocialNetworkController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtSocialNetworkController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'activeFb' => array(
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => __('Activate share on facebook') ))
      ),
      'activeTwitter' => array(
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => __('Activate share on twitter') ))
      )
    );

    $i18nCtrl = new I18nController();

    $fieldsInfo3 = array();
    foreach (Cogumelo::getSetupValue( 'lang:available' ) as $key => $lang){
      $fieldsInfo3['textFb_'.$key] = array( 'params' => array( 'class'=>  'js-tr js-tr-'.$key, 'label' => __( 'Text to share on facebook' ), 'type' => 'textarea', 'placeholder' => $i18nCtrl->getLangTranslation('You should visit #TITLE#. Seen in en #URL#', $lang['i18n']) ),
        'rules' => array( 'maxlength' => '2000' ));
      $fieldsInfo3['textTwitter_'.$key] = array( 'params' => array( 'class'=>  'js-tr js-tr-'.$key, 'label' => __( 'Text to share on twitter' ), 'type' => 'textarea', 'placeholder' => $i18nCtrl->getLangTranslation('I liked this place: #TITLE# via #URL#', $lang['i18n']) ),
        'rules' => array( 'maxlength' => '2000' ));

    }
    $fieldsInfo = array_merge($fieldsInfo, $fieldsInfo3);

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

    /*******************************************************************
     * Importante: Guardar la lista de campos del RExt en 'FieldNames' *
     *******************************************************************/
    //$rExtFieldNames[] = 'FieldNames';
    $form->setField( $this->addPrefix( 'FieldNames' ), array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );

    $form->saveToSession();

    return( $rExtFieldNames );
  } // function manipulateForm()


  /**
    getFormBlockInfo
  */
  public function getFormBlockInfo( FormController $form ) {

    $formBlockInfo = parent::getFormBlockInfo( $form );
    $templates = $formBlockInfo['template'];

    $templates['basic'] = new Template();
    $templates['basic']->setTpl( 'rExtFormBasic.tpl', 'rextSocialNetwork' );
    $templates['basic']->assign( 'rExt', $formBlockInfo );
    $templates['basic']->assign('textFb', $form->multilangFieldNames( 'rExtSocialNetwork_textFb' ));
    $templates['basic']->assign('textTwitter', $form->multilangFieldNames( 'rExtSocialNetwork_textTwitter' ));

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

      if (isset($valuesArray['activeFb'])){
        $textFb = $form->multilangFieldNames( 'textFb' );
        foreach( $textFb as $text ) {
          if ($valuesArray[$text]==""){
            $valuesArray[$text] = $form->getFieldParam('rExtSocialNetwork_'.$text, 'placeholder');
            $form->setFieldValue('rExtSocialNetwork_'.$text, $form->getFieldParam('rExtSocialNetwork_'.$text, 'placeholder'));
          }
        }
      }
      if (isset($valuesArray['activeTwitter'])){
        $twitterFb = $form->multilangFieldNames( 'textTwitter' );
        foreach( $twitterFb as $text ) {
          if( $valuesArray[$text]=="" ) {
            $valuesArray[$text] = $form->getFieldParam('rExtSocialNetwork_'.$text, 'placeholder');
            $form->setFieldValue('rExtSocialNetwork_'.$text, $form->getFieldParam('rExtSocialNetwork_'.$text, 'placeholder'));
          }
        }
      }

      $rExtModel = new RExtSocialNetworkModel( $valuesArray );

      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
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
  public function getViewBlockInfo() {

    $rExtViewBlockInfo = parent::getViewBlockInfo();

    if( $rExtViewBlockInfo['data'] ) {
      $template = new Template();

      foreach( $rExtViewBlockInfo['data'] as $key => $socialField ) {
        $text[$key] = str_replace('#TITLE#', $this->defResCtrl->resObj->getter('title'), $socialField );
        $text2[$key] = str_replace('#URL#', SITE_HOST.$this->defResCtrl->resData['urlAlias'], $text[$key] );
        $rExtViewBlockInfo['data'][$key] = $text2[$key];
      }

      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextSocialNetwork' );

      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtSocialNetworkController
