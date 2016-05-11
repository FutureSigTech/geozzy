<?php
/*
  Previamente se definen las siguientes constantes:

  WEB_BASE_PATH - Apache DocumentRoot (en index.php)
  PRJ_BASE_PATH - Project Path (normalmente contiene app/ httpdocs/ formFiles/) (en index.php)
  APP_BASE_PATH - App Path (en index.php)
  IS_DEVEL_ENV  - Indica si estamos en el entorno de desarrollo (en setup.php)


  Normas de estilo:

  Nombres:
  - Inicia por mod:nombreModulo: para configuración de modulos
  - Fuera de módulos, de forma general, usaremos tema:subtema:variable
  - Usar nombres finalizados en "Path" (variablePath) para rutas

  Valores:
  - Las rutas NO finalizan en /
  - Las URL NO finalizan en /


  Llamadas a metodos:

  En ficheros de setup:
  cogumeloSetSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = cogumeloGetSetupValue( 'mod:nombreModulo:level1:level2' );

  En código cogumelo:
  Cogumelo::setSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = Cogumelo::getSetupValue( 'mod:nombreModulo:level1:level2' );
*/


//
// Public Access User
//
define( 'GA_ACCESS_USER', 'gaUser' );
define( 'GA_ACCESS_PASSWORD', 'gz15005' );


//
// Lang
//
cogumeloSetSetupValue( 'lang', array(
  'available' => array(
    'es' => array(
      'i18n' => 'es_ES',
      'name' => 'castellano' ),
    'gl' => array(
      'i18n' => 'gl_ES',
      'name' => 'galego' ),
    'en' => array(
      'i18n' => 'en_US',
      'name' => 'english' ),
  ),
  'default' => 'es'
));


//
// URL alias controller: Fichero que contiene una clase UrlAliasController con un metodo getAlternative
//
cogumeloSetSetupValue( 'urlAliasController:classFile', COGUMELO_DIST_LOCATION.'/distModules/geozzy/classes/controller/UrlAliasController.php' );


//
//  Module load
//
global $C_ENABLED_MODULES;
$C_ENABLED_MODULES = array(
  'i18nGetLang',
  'i18nServer',
  'mediaserver',
  'common',
  'devel',
  'user',
  'geozzyAPI',
  'filedata',
  'geozzy',
  'appResourceBridge',
  'bi',
  'biMetrics',
  'admin',
  'form',
  'Blocks',
  'table',
  'explorer',
  'appExplorer',
  // testing module
  'testData',
);

// resource Extenssions
global $C_REXT_MODULES;
$C_REXT_MODULES = array(
  'rextAccommodation',
  'rextEatAndDrink',
  'rextContact',
  'rextMap',
  'rextMapDirections',
  'rextUrl',
  'rextView',
  'rextFile',
  'rextUserProfile',
  'rextAppLugar',
  'rextAppEspazoNatural',
  'rextAppZona',
  'rextSocialNetwork',
  'rextEvent',
  'rextEventCollection',
  'rextAppFesta',
  'rextPoi',
  'rextPoiCollection',
  'rextComment',
  'rextRoutes'
);

// resource Types
global $C_RTYPE_MODULES;
$C_RTYPE_MODULES = array(
  'rtypeAppHotel',
  'rtypeAppRestaurant',
  'rtypeUrl',
  'rtypePage',
  'rtypeFile',
  'rtypeAppRuta',
  'rtypeAppLugar',
  'rtypeAppEspazoNatural',
  'rtypeAppFesta',
  'rtypeAppUser',
  'rtypePoi',
  'rtypeEvent'
);


// Ultimate modules
global $C_ULTIMATE_MODULES;
$C_ULTIMATE_MODULES = array(
  'initResources',
  'geozzyUser'
);

// Merge all modules
$C_ENABLED_MODULES = array_merge( $C_ENABLED_MODULES, $C_REXT_MODULES, $C_RTYPE_MODULES, $C_ULTIMATE_MODULES );


// before app/Cogumelo.php execution
// Needed for modules with their own urls
global $C_INDEX_MODULES;
$C_INDEX_MODULES  = array(
  'i18nGetLang',
  'i18nServer',
  'mediaserver',
  'user',
  'geozzyUser',
  'filedata',
  'geozzy',
  'appResourceBridge',
  'form',
  'admin',
  'Blocks',
  'geozzyAPI',
  'testData',
  'initResources',
  'explorer',
  'rextRoutes',
  'rextComment',
  'rtypeEvent',
  'rtypePoi',
  'devel'
); // DEVEL SIEMPRE DE ULTIMO!!!


//
// User config
//
cogumeloSetSetupValue( 'mod:geozzyUser', array(
  'profile' => 'rtypeAppUser'
));


//
// Dependences PATH
//
cogumeloSetSetupValue( 'dependences', array(
  'composerPath' => WEB_BASE_PATH.'/vendor/composer',
  'bowerPath' => WEB_BASE_PATH.'/vendor/bower',
  'manualPath' => WEB_BASE_PATH.'/vendor/manual',
  'manualRepositoryPath' => COGUMELO_LOCATION.'/packages/vendorPackages'
));


//
//  Devel Mod
//
cogumeloSetSetupValue( 'mod:devel', array(
  'allowAccess' => true,
  'url' => 'devel',
  'password' => 'develpassword'
));


//
//  i18n
//
cogumeloSetSetupValue( 'i18n', array(
  'path' => APP_BASE_PATH.'/conf/i18n',
  'localePath' => APP_BASE_PATH.'/conf/i18n/locale',
  'gettextUpdate' => true // update gettext files when working in localhost
));


//
//  Media server
//
cogumeloSetSetupValue( 'publicConf:globalVars', array( 'C_LANG', 'C_SESSION_ID' ) );
cogumeloSetSetupValue( 'publicConf:setupFields',
  array( 'session:lifetime', 'lang:available', 'lang:default', 'mod:geozzy:resource:directUrl', 'date:timezone' ) );
cogumeloSetSetupValue( 'publicConf:vars:langDefault', cogumeloGetSetupValue( 'lang:default' ) );
cogumeloSetSetupValue( 'publicConf:vars:langAvailableIds', array_keys( cogumeloGetSetupValue( 'lang:available' ) ) );
cogumeloSetSetupValue( 'publicConf:vars:mediaJs',
  ( cogumeloGetSetupValue( 'mod:mediaserver:productionMode' ) === true &&
    cogumeloGetSetupValue( 'mod:mediaserver:notCacheJs' ) !== true )
    ? cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:cachePath' )
    : cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:path' ) );
cogumeloSetSetupValue( 'publicConf:vars:media',
  ( cogumeloGetSetupValue( 'mod:mediaserver:productionMode' ) === true )
    ? cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:cachePath' )
    : cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:path' ) );
cogumeloSetSetupValue( 'publicConf:vars:mediaHost', cogumeloGetSetupValue( 'mod:mediaserver:host' ) );
cogumeloSetSetupValue( 'publicConf:vars:site_host', SITE_HOST );

cogumeloSetSetupValue( 'mod:mediaserver:publicConf:javascript',
  cogumeloGetSetupValue( 'publicConf' )
);
cogumeloSetSetupValue( 'mod:mediaserver:publicConf:less',
  cogumeloGetSetupValue( 'publicConf' )
);
cogumeloSetSetupValue( 'mod:mediaserver:publicConf:smarty',
  cogumeloGetSetupValue( 'publicConf' )
);
cogumeloSetSetupValue( 'mod:mediaserver:publicConf:smarty:setupFields',
  array_merge( cogumeloGetSetupValue( 'publicConf:setupFields' ), array('user:session') )
);


//
// Alias por defecto en recursos
//
cogumeloSetSetupValue( 'mod:geozzy:resource:urlAliasPatterns',
  array(
    'default' => '/',
    'rtypeAppHotel' => array(
      'default' => '/alojamientos/',
      'gl' => '/aloxamentos/',
      'en' => '/accommodation/'
    ),
    'rtypeAppRestaurant' => array(
      'default' => '/comidas/',
      'en' => '/food/'
    ),
    'rtypeAppEspazoNatural' => array(
      'default' => '/naturaleza/',
      'gl' => '/natureza/',
      'en' => '/nature/'
    ),
    'rtypeAppLugar' => array(
      'default' => '/rincones/',
      'gl' => '/recunchos/',
      'en' => '/places/'
    )
  )
);


//
//
//
cogumeloSetSetupValue( 'mod:geozzy:resource:collectionTypeRules',
  array(
    'default' => array(
      'multimedia' => array('rtypeUrl', 'rtypeFile'),
      'eventos' => array('rtypeEvent'),
      'poi' => array('rtypePoi'),
      'base' => array()
    ),
    'rtypeAppHotel' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeEvent'),
      'poi' => array(),
      'base' => array('rtypeAppHotel', 'rtypeAppRestaurant')
    ),
    'rtypeAppRestaurant' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeEvent'),
      'poi' => array(),
      'base' => array('rtypeAppHotel', 'rtypeAppRestaurant')
    ),
    'rtypeAppEspazoNatural' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'poi' => array('rtypePoi'),
      'base' => array()
    ),
    'rtypeAppLugar' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'poi' => array(),
      'base' => array()
    ),
    'rtypeAppFesta' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeAppFesta', 'rtypeEvent'),
      'poi' => array(),
      'base' => array()
    )
  )
);



//
// RTypes de "uso interno"
//
cogumeloSetSetupValue( 'mod:geozzy:resource:systemRTypes',
  array(
    'rtypeUrl',
    'rtypePage',
    'rtypeFile',
    'rtypeEvent'
  )
);


//
//
//
cogumeloSetSetupValue( 'mod:geozzy:resource:commentRules',
  array(
    'default' => array(
      'moderation' => 'none', // none|verified|all
      'ctype' => array() // 'comment','suggest'
    ),
    'rtypeAppHotel' => array(
      'moderation' => 'none', // none|verified|all
      'ctype' => array('comment','suggest') // 'comment','suggest'
    ),
    'rtypeAppRestaurant' => array(
      'moderation' => 'verified', // none|verified|all
      'ctype' => array('suggest') // 'comment','suggest'
    )
  )
);