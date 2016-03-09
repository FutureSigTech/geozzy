<?php

Cogumelo::load('coreView/View.php');
Cogumelo::load('coreController/I18nController.php');
common::autoIncludes();
geozzy::autoIncludes();
Cogumelo::autoIncludes();



/**
* Clase Master to extend other application methods
*/
class MasterView extends View
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
    global $C_LANG;
    $this->actLang = $C_LANG;
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {

    $accessValid = false;

    $validIp = array(
      '213.60.18.106', // Innoto
      '176.83.204.135', '91.117.124.2', // ITG
      '91.116.191.224', // Zadia
      '127.0.0.1'
    );

    $conectionIP = isset( $_SERVER['HTTP_X_REAL_IP'] ) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
    if( in_array( $conectionIP, $validIp ) || strpos( $conectionIP, '10.77.' ) === 0 ) {
      $accessValid = true;
    }
    else {
      if(
        ( !isset( $_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER']!= GA_ACCESS_USER ) &&
        ( !isset( $_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW']!= GA_ACCESS_PASSWORD ) )
      {
        error_log( 'BLOQUEO --- Acceso Denegado!!!' );
        header('WWW-Authenticate: Basic realm="Galicia Agochada"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Acceso Denegado.';
        // exit;
      }
      else {
        $accessValid = true;
      }
    }

    return $accessValid;
  }


  public function page404() {
    echo 'PAGE404: Recurso non atopado';
  }

  public function page403() {
    $this->template->addClientStyles('styles/master.less');
    $this->template->setTpl('403.tpl');
    $this->template->exec();
  }


  public function home() {

    // Autodetección idioma
    $i18nCtrl = new I18nController();
    $i18nCtrl->redirectLang('home');

    $resourceTaxAllModel = new ResourceTaxonomyAllModel( );
    $resourceModel = new ResourceModel( );

    /**
    Destacados de RecantosConEstilo
    **/

    $resList = $resourceModel->listItems(
      array(
        'filters'=> array(
          'ResourceTaxonomyAllModel.idName' => 'RecantosConEstilo',
          'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
        ),
        'affectsDependences' => array('ResourceTaxonomyAllModel'),
        'joinType' => 'RIGHT'
      )
    );
    $resourceArrayRecantos = array();
    while ( $res = $resList->fetch() ) {
      $resAll = $res->getAllData();
      $resourceArrayRecantos[$res->getter('id')]['data'] = $resAll['data'];
      $urlAlias = $this->getUrlAlias($res->getter('id'));
      $resourceArrayRecantos[$res->getter('id')]['urlAlias'] = $urlAlias;
    }

    $this->template->assign('rdRecantosConEstilo', $resourceArrayRecantos);

    /**
    Destacados de Festa Rachada
    **/

    $resList = $resourceModel->listItems(
      array(
        'filters'=> array(
          'ResourceTaxonomyAllModel.idName' => 'FestaRachada',
          'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
        ),
        'affectsDependences' => array('ResourceTaxonomyAllModel'),
        'joinType' => 'RIGHT'
      )
    );
    $resourceArrayFesta = array();
    while ( $res = $resList->fetch() ) {
      $resAll = $res->getAllData();
      $resourceArrayFesta[$res->getter('id')]['data'] = $resAll['data'];
      $urlAlias = $this->getUrlAlias($res->getter('id'));
      $resourceArrayFesta[$res->getter('id')]['urlAlias'] = $urlAlias;
    }

    $this->template->assign('rdFestaRachada', $resourceArrayFesta);

    /**
    Destacados de PraiasDeEnsono
    **/

    $resList = $resourceModel->listItems(
      array(
        'filters'=> array(
          'ResourceTaxonomyAllModel.idName' => 'PraiasDeEnsono',
          'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
        ),
        'affectsDependences' => array('ResourceTaxonomyAllModel'),
        'joinType' => 'RIGHT'
      )
    );
    $resourceArrayPraias = array();
    while ( $res = $resList->fetch() ) {
      $resAll = $res->getAllData();
      $resourceArrayPraias[$res->getter('id')]['data'] = $resAll['data'];
      $urlAlias = $this->getUrlAlias($res->getter('id'));
      $resourceArrayPraias[$res->getter('id')]['urlAlias'] = $urlAlias;
    }

    $this->template->assign('rdPraiasDeEnsono', $resourceArrayPraias);

    /**
    Destacados de PaisaxesEspectaculares
    **/

    $resList = $resourceModel->listItems(
      array(
        'filters'=> array(
          'ResourceTaxonomyAllModel.idName' => 'PaisaxesEspectaculares',
          'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
        ),
        'affectsDependences' => array('ResourceTaxonomyAllModel'),
        'joinType' => 'RIGHT'
      )
    );
    $resourceArrayPaisaxes = array();
    while ( $res = $resList->fetch() ) {
      $resAll = $res->getAllData();
      $resourceArrayPaisaxes[$res->getter('id')]['data'] = $resAll['data'];
      $urlAlias = $this->getUrlAlias($res->getter('id'));
      $resourceArrayPaisaxes[$res->getter('id')]['urlAlias'] = $urlAlias;
    }

    $this->template->assign('rdPaisaxesEspectaculares', $resourceArrayPaisaxes);

    /**
    Destacados de AloxamentoConEncantos
    **/

    $resList = $resourceModel->listItems(
      array(
        'filters'=> array(
          'ResourceTaxonomyAllModel.idName' => 'AloxamentoConEncanto',
          'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
        ),
        'affectsDependences' => array('ResourceTaxonomyAllModel'),
        'joinType' => 'RIGHT'
      )
    );
    $resourceArrayAloxamentos = array();
    while ( $res = $resList->fetch() ) {
      $resAll = $res->getAllData();
      $resourceArrayAloxamentos[$res->getter('id')]['data'] = $resAll['data'];
      $urlAlias = $this->getUrlAlias($res->getter('id'));
      $resourceArrayAloxamentos[$res->getter('id')]['urlAlias'] = $urlAlias;
    }

    $this->template->assign('rdAloxamentoConEncanto', $resourceArrayAloxamentos);

    /**
    Destacados de AutenticaGastronomia
    **/

    $resList = $resourceModel->listItems(
      array(
        'filters'=> array(
          'ResourceTaxonomyAllModel.idName' => 'AutenticaGastronomia',
          'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
        ),
        'affectsDependences' => array('ResourceTaxonomyAllModel'),
        'joinType' => 'RIGHT'
      )
    );
    $resourceArrayGastronomia = array();
    while ( $res = $resList->fetch() ) {
      $resAll = $res->getAllData();
      $resourceArrayGastronomia[$res->getter('id')]['data'] = $resAll['data'];
      $urlAlias = $this->getUrlAlias($res->getter('id'));
      $resourceArrayGastronomia[$res->getter('id')]['urlAlias'] = $urlAlias;
    }

    $this->template->assign('rdAutenticaGastronomia', $resourceArrayGastronomia);

    $this->template->assign('isFront', true);
    $this->template->addClientScript('js/portada.js');
    $this->template->addClientStyles('styles/masterPortada.less');
    $this->template->setTpl('portada.tpl');
    $this->template->exec();
  }

  public function exampleComarca() {
    $this->template->setTpl('zonaMap.tpl','rextAppZona');
    $this->template->exec();
  }

  // Obtiene la url del recurso en el idioma especificado y sino, en el idioma actual
  public function getUrlAlias($resId, $lang = false){
    $urlAliasModel = new UrlAliasModel();

    if ($lang){
      $langId = $lang;
    }
    else{
      $langId = $this->actLang;
    }
    $urlAlias = false;
    $urlAliasList = $urlAliasModel->listItems( array( 'filters' => array( 'resource' => $resId, 'lang' => $langId ) ) )->fetch();

    if ($urlAliasList){
      $urlAlias = $langId.$urlAliasList->getter('urlFrom');
    }

    return $urlAlias;
  }

}
