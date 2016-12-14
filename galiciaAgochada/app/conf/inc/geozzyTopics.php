<?php

$geozzyTopicsInfo = array(
  'PaisaxesEspectaculares' => array(
    'name' => array(
      'es' => 'Paisajes espectaculares',
      'en' => 'Spectacular scenery',
      'gl' => 'Paisaxes espectaculares'
    ),
    'resourceTypes' => array(
      'rtypeAppRuta' => array(
        'weight' => 1
      ),
      'rtypeAppEspazoNatural' => array(
        'weight' => 3
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
    'icon' => '',
    'weight' => 1
  ),
  'PraiasDeEnsono' => array(
    'name' => array(
      'es' => 'Playas de ensueño',
      'en' => 'Dream beaches',
      'gl' => 'Praias de ensono'
    ),
    'resourceTypes' => array(
      'rtypeAppRuta' => array(
        'weight' => 2
      ),
      'rtypeAppEspazoNatural' => array(
        'weight' => 1
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
    'icon' => '',
    'weight' => 1
  ),
  'RecantosConEstilo' => array(
    'name' => array(
      'es' => 'Lugares con estilo',
      'en' => 'Stylish places',
      'gl' => 'Recantos con estilo'
    ),
    'resourceTypes' => array(
      'rtypeAppRuta' => array(
        'weight' => 3
      ),
      'rtypeAppLugar' => array(
        'weight' => 1
      ),
      'rtypeAppEspazoNatural' => array(
        'weight' => 2
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
    'icon' => '',
    'weight' => 1
  ),
  'AutenticaGastronomia' => array(
    'name' => array(
      'es' => 'Auténtica gastronomía',
      'en' => 'Authentic cuisine',
      'gl' => 'Auténtica gastronomía'
    ),
    'resourceTypes' => array(
      'rtypeAppRestaurant' => array(
        'weight' => 2
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
    'icon' => '',
    'weight' => 1
  ),
  'AloxamentoConEncanto' => array(
    'name' => array(
      'es' => 'Alojamiento con encanto',
      'en' => 'Charming accommodation',
      'gl' => 'Aloxamento con encanto'
    ),
    'resourceTypes' => array(
      'rtypeAppHotel' => array(
        'weight' => 1
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
    'icon' => '',
    'weight' => 1
  ),
  'FestaRachada' => array(
    'name' => array(
      'es' => 'Fiesta',
      'en' => 'Party',
      'gl' => 'Festa rachada'
    ),
    'resourceTypes' => array(
      'rtypeAppFesta' => array(
        'weight' => 1
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
    'icon' => '',
    'weight' => 1
  ),
  'probasTopic' => array(
    'name' => array(
      'es' => 'Pruebas',
      'en' => 'Tests',
      'gl' => 'Probas'
    ),
    'resourceTypes' => array(
      'rtypeAppRestaurant' => array(
        'weight' => 2
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
    'icon' => '',
    'weight' => 1
  ),
  'participation' => array(
    'name' => array(
      'es' => 'Participación',
      'en' => 'Participation',
      'gl' => 'Participación'
    ),
    'resourceTypes' => array(
      'rtypeAppRestaurant' => array(
        'weight' => 2
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
    'icon' => '',
    'weight' => 1
  )
);


class geozzyTopicsTableExtras {
  public function AloxamentoConEncanto( $urlParamsList,  $tabla ) {

/*
    $topicId = $urlParamsList['topic'];
    $resourcetype =  new ResourcetypeModel();
    $resourcetypelist = $resourcetype->listItems( array( 'filters' => array( 'intopic' => $topicId ) ) )->fetchAll();

    $tiposArray = array();
    foreach ($resourcetypelist as $typeId => $type){
      $tiposArray[$typeId] = $typeId;
    }


    $filterByRtypeOpts = [];
    foreach ($resourcetypelist as $typeId => $type){
      $filterByRtypeOpts[$typeId] = $type->getter('name');
    }
    $filterByRtypeOpts['*'] = __('All');


    $tabla->setExtraFilter( 'times',  'combo', __('Published'), $filterByRtypeOpts, '*' );
*/

    //$tabla->setCol('id', 'REFERENCIA');
    $tabla->setColClasses('id', 'hidden-xs');
    $tabla->setColClasses('rTypeId', 'hidden-xs');
    //$tabla->unsetCol('published');
    //$tabla->unsetCol('rTypeId');
    return $tabla;
  }

}
