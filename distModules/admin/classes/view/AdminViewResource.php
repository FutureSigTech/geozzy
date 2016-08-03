<?php
admin::load('view/AdminViewMaster.php');
geozzy::load( 'view/GeozzyResourceView.php' );



class AdminViewResource extends AdminViewMaster {

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
    Section list user
   **/
  public function listResources() {

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions();
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $template = new Template( $this->baseDir );
    $template->assign('resourceTable', table::getTableHtml('AdminViewResource', '/admin/resource/table') );
    $template->setTpl('listResource.tpl', 'admin');

    $resourcetype =  new ResourcetypeModel();
    $resourcetypelist = $resourcetype->listItems()->fetchAll();

    $resCreateByType = '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
    foreach( $resourcetypelist as $i => $res ) {
      $typeList[ $i ] = $res->getter('name_es');
      $resCreateByType .= '<li><a class="create-'.$res->getter('idName').'" href="/admin#resource/create/resourcetype/'.$res->getter('id').'">'.$res->getter('name_es').'</a></li>';
    }
    $resCreateByType .= '</ul>';

    $this->template->addToFragment( 'col12', $template );
    $this->template->assign( 'headTitle', __('Resource Management') );
    $this->template->assign( 'headActions', '<div class="btn-group assignResource AdminViewResource">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>'
    );
    $this->template->assign( 'footerActions', '<div class="btn-group assignResource AdminViewResource">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>' );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }


  public function listResourcesTable() {

    table::autoIncludes();
    $resource =  new ResourceModel();

    $tabla = new TableController( $resource );

    $tabla->setTabs(__('published'), array('1'=>__('Published'), '0'=>__('Unpublished'), '*'=> __('All') ), '*');

    // set id search reference.
    $tabla->setSearchRefId('find');

    // set table Actions
    $tabla->setActionMethod(__('Publish'), 'changeStatusPublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>1 ))');
    $tabla->setActionMethod(__('Unpublish'), 'changeStatusUnpublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>0 ))');
    $tabla->setActionMethod(__('Delete'), 'delete', 'listitems(array("filters" => array("id" => $rowId)))->fetch()->delete()');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#resource/edit/id/".$rowId');
    $tabla->setNewItemUrl('/admin#resource/create');

    // Nome das columnas
    $tabla->setCol('id', 'ID');
    $tabla->setCol('rTypeId', __('Type'));
    $tabla->setCol('title_'.Cogumelo::getSetupValue( 'lang:default' ), __('Title'));
    $tabla->setCol('published', __('Published'));

    // Filtrar por temática
    /*
    $tabla->setDefaultFilters( array('ResourceTopicModel.topic'=> 15 ) );
    $tabla->setAffectsDependences( array('ResourceTopicModel') ) ;
    $tabla->setJoinType('INNER');
    */

    // Contido especial
    $tabla->colRule('published', '#1#', '<span class=\"rowMark rowOk\"><i class=\"fa fa-circle\"></i></span>');
    $tabla->colRule('published', '#0#', '<span class=\"rowMark rowNo\"><i class=\"fa fa-circle\"></i></span>');

    $typeModel =  new ResourcetypeModel();
    $typeList = $typeModel->listItems()->fetchAll();
    foreach( $typeList as $id => $type ) {
      $tabla->colRule('rTypeId', '#'.$id.'#', $type->getter('name'));
    }

    // imprimimos o JSON da taboa
    $tabla->exec();
  }


  /**
   * Creacion de Recursos
   */
  public function resourceForm( $urlParams = false ) {

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('resource:create', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $recursoData = false;
    /* Validamos os parámetros da url e obtemos un array de volta*/
    $validation = array( 'topic'=> '#^\d+$#', 'resourcetype' => '#^\d+$#', 'star' => '#^\d+$#', 'story' => '#^\d+$#' );
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );

    if( $urlParamsList ) {
      $topicItem = false;
      $rTypeItem = false;

      if( isset( $urlParamsList['topic'] ) ) {
        $urlParamTopic = $urlParamsList['topic'];
        $topicControl = new TopicModel();
        $topicItem = $topicControl->ListItems( array( 'filters' => array( 'id' => $urlParamTopic ) ) )->fetch();
      }

      if( isset( $urlParamsList['resourcetype'] ) ) {
        $urlParamRtype = $urlParamsList['resourcetype'];
        $rtypeControl = new ResourcetypeModel();
        $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'id' => $urlParamRtype ) ) )->fetch();
      }

      if( isset( $urlParamsList['story'] ) ) {
        $urlParamStory = $urlParamsList['story'];
        $resourceControl = new ResourceModel();
        $resourceItem = $resourceControl->ListItems( array( 'filters' => array( 'id' => $urlParamStory ) ) )->fetch();
      }

      if( $rTypeItem ) {
        $recursoData = array();
        $recursoData['rTypeId'] = $rTypeItem->getter('id');
        $recursoData['rTypeIdName'] = $rTypeItem->getter('idName');
        $recursoData['typeReturn'] = $urlParamsList['resourcetype'];

        if( $topicItem ) {
          $rtypeTopicControl = new ResourcetypeTopicModel();
          $resourcetypeTopic = $rtypeTopicControl->ListItems(
            array( 'filters' => array( 'topic' => $urlParamTopic, 'resourceType' => $urlParamRtype ) )
          )->fetch();

          if( $resourcetypeTopic ){
            $recursoData['topicReturn'] = $topicItem->getter('id');
            $recursoData['topics'] = $topicItem->getter('id');
          }
        }
        // Creando un storystep dentro dunha story
        if( $resourceItem ) {
          // A partir do id da story recuperamos a súa collection 'steps'
          $collection = new CollectionModel( );
          $resourceCollectionsModel = new ResourceCollectionsModel();
          $resourceCollectionsList = $resourceCollectionsModel->listItems(
            array('filters' => array('resource' => $urlParamStory)) );
          $collectionId = false;
          while($resCol = $resourceCollectionsList->fetch()){
            $typecol = $collection->listItems(array('filters' => array('id' => $resCol->getter('collection'))))->fetch();
            if($typecol->getter('collectionType')==='steps'){
              $collectionId = $typecol->getter('id');
            }
          }
          $recursoData['collection'] = $collectionId;
          $recursoData['storyAssignReturn'] = $resourceItem->getter('id');
        }

      }
    }

    $this->resourceShowForm( 'resourceCreate', '/admin/resource/sendresource', $recursoData );
  } // function resourceForm()


  /**
   * Edicion de Recursos
   */
  public function resourceEditForm( $urlParams = false ) {
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
    $validation = array( 'topic'=> '#^\d+$#', 'resourceId'=> '#^\d+$#', 'type'=> '#^\d+$#', 'story'=> '#^\d+$#' );
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );

    $resCtrl = new ResourceController();
    $recursoData = $resCtrl->getResourceData( $urlParamsList['resourceId'] );

    if( isset( $urlParamsList['topic'] ) ) {
      $urlParamTopic = $urlParamsList['topic'];
      $topicControl = new TopicModel();
      $topicItem = $topicControl->ListItems( array( 'filters' => array( 'id' => $urlParamTopic ) ) )->fetch();
    }

    if (isset( $urlParamsList['type'])){
      $typeItem = $urlParamsList['type'];
      $recursoData['typeReturn'] = $typeItem;
    }

    if (isset( $urlParamsList['story'])){
      $typeItem = $urlParamsList['story'];
      $recursoData['storyReturn'] = $typeItem;
    }

    if( $topicItem ) {
      $rtypeTopicControl = new ResourcetypeTopicModel();
      $resourcetypeTopic = $rtypeTopicControl->ListItems(
        array( 'filters' => array( 'topic' => $urlParamTopic, 'resourceType' => $recursoData['rTypeId'] ) )
      )->fetch();

      if( $resourcetypeTopic ){
        $recursoData['topicReturn'] = $topicItem->getter('id');
      }
    }

    if( $recursoData ) {
      $this->resourceShowForm( 'resourceEdit', '/admin/resource/sendresource', $recursoData, $resCtrl );
    }
    else {
      cogumelo::error( 'Imposible acceder al recurso indicado.' );
    }
  } // function resourceEditForm()



  public function resourceShowForm( $formName, $urlAction, $valuesArray = false, $resCtrl = false ) {

    if( !$resCtrl ) {
      $resCtrl = new ResourceController();
    }
    if( !isset($valuesArray['storyReturn']) ) {
      if( !isset($valuesArray['storyAssignReturn']) ) {
        if( !isset($valuesArray['topicReturn']) ) {
          if (isset($valuesArray['typeReturn'])){ // tabla de páginas
            $rtypeControl = new ResourcetypeModel();
            $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'id' => $valuesArray['typeReturn'] ) ) )->fetch();
            if($rTypeItem && $rTypeItem->getter('idName') === "rtypePage"){
              $successArray[ 'redirect' ] = SITE_URL . 'admin#resourcepage/list';
            }else{
              $successArray[ 'redirect' ] = SITE_URL . 'admin#resource/list';
            }
          }
          else{ // tabla general de contenidos
            $successArray[ 'redirect' ] = SITE_URL . 'admin#resource/list';
          }
        }
        else { // tabla de recursos de una temática
          $successArray[ 'redirect' ] = SITE_URL . 'admin#topic/'.$valuesArray['topicReturn'];
        }
      }
      else { // tabla de recursos de una temática
        $successArray[ 'redirect' ] = SITE_URL . 'admin#storysteps/story/'.$valuesArray['storyAssignReturn'].'/assign';
      }
    }
    else { // tabla de recursos de una temática
      $successArray[ 'redirect' ] = SITE_URL . 'admin#storysteps/'.$valuesArray['storyReturn'];
    }
    $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, $successArray, $valuesArray );

    $resTplVar = $formBlockInfo['template']['adminFull']->getTemplateVars('res');
    if( !$resTplVar ) {
      $formBlockInfo['template']['adminFull']->assign( 'res', array( 'data' => $formBlockInfo['data'] ) );
    }
    $formBlockInfo['template']['adminFull']->exec();
  }

  /**
   * Creacion de Recursos type URL
   */
  public function resourceTypeUrlForm( $urlParams = false ) {
    $resCtrl = new ResourceController();
    $rtypeModel = new ResourcetypeModel();

    $formName = 'resourceUrlCreate';
    $urlAction = '/admin/resourcetypeurl/sendresource';

    $rtype = $rtypeModel->listItems( array( 'filters' => array('idName' => 'rtypeUrl') ) )->fetch();
    $valuesArray['rTypeId'] = $rtype->getter('id');

    $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, false, $valuesArray );


    $form = $formBlockInfo['objForm'];
    $form->setFieldParam('image', 'label', 'Thumbnail image (Optional)');
    $form->setFieldParam('published', 'type', 'reserved');
    $form->setFieldParam('published', 'value', '1');
    $urlAliasLang = $form->multilangFieldNames('urlAlias');
    foreach ($urlAliasLang as $key => $field) {
      $form->removeField( $field);
    }
    $form->removeValidationRules('published');
    $formBlockInfo['dataForm'] = array(
      'formOpen' => $form->getHtmpOpen(),
      'formFieldsArray' => $form->getHtmlFieldsArray(),
      'formFieldsHiddenArray' => array(),
      'formFields' => $form->getHtmlFieldsAndGroups(),
      'formClose' => $form->getHtmlClose(),
      'formValidations' => $form->getScriptCode()
    );
    $formBlockInfo['template']['miniFormModal']->assign( 'res', $formBlockInfo );
    $formBlockInfo['template']['miniFormModal']->exec();
  }


  /**
   * Creacion de Recursos type FILE
   */
  public function resourceTypeFileForm( $urlParams = false ) {

    $resCtrl = new ResourceController();
    $rtypeModel = new ResourcetypeModel();

    $formName = 'resourceFileCreate';
    $urlAction = '/admin/resourcetypefile/sendresource';

    $rtype = $rtypeModel->listItems( array( 'filters' => array('idName' => 'rtypeFile') ) )->fetch();
    $valuesArray['rTypeId'] = $rtype->getter('id');

    $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, false, $valuesArray );
    $form = $formBlockInfo['objForm'];

    $form->setFieldParam('image', 'label', 'Thumbnail image (Optional)');
    $form->setFieldParam('published', 'type', 'reserved');
    $form->setFieldParam('published', 'value', '1');
    $urlAliasLang = $form->multilangFieldNames('urlAlias');
    foreach( $urlAliasLang as $key => $field ) {
      $form->removeField( $field);
    }
    $form->removeField('externalUrl');
    $form->removeValidationRules('published');
    // $form->setValidationRule('rExtFile_file', 'required', true);

    $formBlockInfo['dataForm'] = array(
      'formOpen' => $form->getHtmpOpen(),
      'formFieldsArray' => $form->getHtmlFieldsArray(),
      'formFieldsHiddenArray' => array(),
      'formFields' => $form->getHtmlFieldsAndGroups(),
      'formClose' => $form->getHtmlClose(),
      'formValidations' => $form->getScriptCode()
    );

    $formBlockInfo['template']['miniFormModal']->assign( 'res', $formBlockInfo );
    $formBlockInfo['template']['miniFormModal']->exec();
  }


  public function sendResourceForm() {
    $resourceView = new GeozzyResourceView();
    $resourceView->actionResourceForm();
  }

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
      $thumbImg = false;
      //$imgDefault = false;
      //$isYoutubeID = false;

      $thumbSettings = array(
        'profile' => 'squareCut'
      );

      $rtypeControl = new ResourcetypeModel();
      $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'id' => $resource->getter('rTypeId') ) ) )->fetch();
      if( $rTypeItem && $rTypeItem->getter('idName') === 'rtypeUrl' && $form->getFieldValue('rExtUrl_url') ) {
        $thumbSettings['url'] = $form->getFieldValue('rExtUrl_url');
      }
      if( $resource->getter('image') ){
        $thumbSettings['imageId'] = $resource->getter('image');
        $thumbSettings['imageName'] = $resource->getter('image').'.jpg';
      }

      $resCtrl = new ResourceController();
      $thumbImg = $resCtrl->getResourceThumbnail( $thumbSettings );

      $form->removeSuccess( 'redirect' );
      $form->setSuccess( 'jsEval', ' successResourceForm( { '.
        ' id : "'.$resource->getter('id').'",'.
        ' title: "'.$resource->getter('title_'.$form->langDefault).'",'.
        ' image: "'.$thumbImg.'" });'
      );
    }

    // Enviamos el OK-ERROR a la BBDD y al formulario
    $resourceView->actionResourceFormSuccess( $form, $resource );
  }


  public function ytVidId( $url ) {
    $p = '#^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$#';
    return (preg_match($p, $url, $coincidencias)) ? $coincidencias[1] : false;
  }

}
