<?php


interface RTypeInterface {

  /**
   * Alteramos el objeto form. del recursoBase para adaptarlo a las necesidades del RType
   *
   * @param $form FormController Objeto form. del recursoBase
   *
   * @return array $rTypeFieldNames
   */
  public function manipulateForm( FormController $form );

  /**
   * Preparamos los datos para visualizar el formulario del Recurso
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'ext' => array, 'dataForm' => array }
   */
  public function getFormBlockInfo( FormController $form );

  /**
   * Validaciones extra previas a usar los datos del recurso
   *
   * @param $form FormController Objeto form. del recurso
   */
  public function resFormRevalidate( FormController $form );

  /**
   * Creación-Edicion-Borrado de los elementos del recurso segun el RType
   *
   * @param $form FormController Objeto form. del recurso
   * @param $resource ResourceModel Objeto form. del recurso
   */
  public function resFormProcess( FormController $form, ResourceModel $resource );

  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource );

  /**
   * Preparamos los datos para visualizar el Recurso con sus cambios y sus extensiones
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'ext' => array }
   */
  public function getViewBlockInfo();

} // interface RTypeInterface


class RTypeController {

  public $defResCtrl = null;
  public $rTypeModule = null;
  public $rExts = false;
  public $rTypeName = 'rType';

  /**
   * Inicializamos FormController defResCtrl, rTypeName, RTypeController rTypeModule y Array rExts
   *
   * @param $defResCtrl FormController
   * @param $rTypeModule RTypeController
   */
  public function __construct( $defResCtrl, $rTypeModule ){
    $this->defResCtrl = $defResCtrl;
    // error_log( 'this->defResCtrl '.print_r( $this->defResCtrl, true ) );

    $this->rTypeName = $rTypeModule->name;

    $this->rTypeModule = $rTypeModule;
    if( property_exists( $rTypeModule, 'rext' ) && is_array( $rTypeModule->rext )
      && count( $rTypeModule->rext ) > 0 )
    {
      $this->rExts = $rTypeModule->rext;
    }

    // Cargamos los autoIncludes de los RExt de este RType
    foreach( $this->rExts as $rExtName ) {
      $rExtName::autoIncludes();
    }
  }

  /**
   * Alteramos el objeto form. del recursoBase para adaptarlo a las necesidades del RType
   *
   * @param $form FormController Objeto form. del recursoBase
   *
   * @return array $rTypeFieldNames
   */
  public function manipulateForm( FormController $form ) {

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    // Lanzamos los manipulateForm de los RExt de este RType
    foreach( $this->rExts as $rExtName ) {
      $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
      $rExtCtrl = new $rExtCtrlName( $this );
      $rExtCtrl->manipulateForm( $form );
    }
  }

  /**
   * Preparamos los datos para visualizar el formulario del Recurso
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form ) {
    $formBlockInfo = array(
      'template' => false,
      'data' => false,
      'dataForm' => false,
      'ext' => array()
    );

    $formBlockInfo['dataForm'] = array(
      'formId' => $form->getId(),
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


    // Lanzamos los getFormBlockInfo de los RExt de este RType
    foreach( $this->rExts as $rExtName ) {
      $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
      $rExtCtrl = new $rExtCtrlName( $this );
      $rExtFormViewInfo = $rExtCtrl->getFormBlockInfo( $form );
      $formBlockInfo['ext'][ $rExtCtrl->rExtName ] = $rExtFormViewInfo;
    }

    return $formBlockInfo;
  }

  /**
   * Validaciones extra previas a usar los datos del recurso
   *
   * @param $form FormController Objeto form. del recurso
   */
  public function resFormRevalidate( FormController $form ) {
    if( !$form->existErrors() ) {
      // Lanzamos los resFormRevalidate de los RExt de este RType
      foreach( $this->rExts as $rExtName ) {
        $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
        $rExtCtrl = new $rExtCtrlName( $this );
        $rExtFormViewInfo = $rExtCtrl->resFormRevalidate( $form );
      }
    }
  }

  /**
   * Creación-Edicion-Borrado de los elementos del recurso segun el RType
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    if( !$form->existErrors() ) {
      // Lanzamos los resFormProcess de los RExt de este RType
      foreach( $this->rExts as $rExtName ) {
        $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
        $rExtCtrl = new $rExtCtrlName( $this );
        $rExtFormViewInfo = $rExtCtrl->resFormProcess( $form, $resource );
      }
    }
  }


  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // Lanzamos los resFormSuccess de los RExt de este RType
    foreach( $this->rExts as $rExtName ) {
      $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
      $rExtCtrl = new $rExtCtrlName( $this );
      $rExtCtrl->resFormSuccess( $form, $resource );
    }
  }

  /**
   * Preparamos los datos para visualizar el Recurso con sus cambios y sus extensiones
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'ext' => array }
   */
  public function getViewBlockInfo() {
    $viewBlockInfo = array(
      'template' => array(
        'full' => new Template() // Definimos un Template 'full' por defecto
      ),
      'data' => $this->defResCtrl->getResourceData( false, true ),
      'ext' => array()
    );

    // Lanzamos los getViewBlockInfo de los RExt de este RType
    // y preasignamos su template['full'] a un bloque por defecto
    foreach( $this->rExts as $rExtName ) {
      $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
      $rExtCtrl = new $rExtCtrlName( $this );
      $viewBlockInfo['ext'][ $rExtName ] = $rExtCtrl->getViewBlockInfo();
      if( isset( $viewBlockInfo['ext'][ $rExtName ]['template']['full'] ) ) {
        $viewBlockInfo['template']['full']->addToFragment( $rExtName.'Block',
          $viewBlockInfo['ext'][ $rExtName ]['template']['full'] );
      }
      else {
        $viewBlockInfo['template']['full']->assign( $rExtName.'Block', false );
      }
    }

    $viewBlockInfo['template']['full']->assign( 'res',
      array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );

    return $viewBlockInfo;
  }

} // class RTypeController
