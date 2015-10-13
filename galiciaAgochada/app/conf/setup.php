<?php


if( develEnviroment() ) {
  define( 'IS_DEVEL_ENV', true );
  require_once('setup.dev.php');
}
else {
  define( 'IS_DEVEL_ENV', false );
  require_once('setup.final.php');
}





/**
* Estima si estamos en el entorno de desarrollo o produccion
* @return bool
*/
function develEnviroment() {
  $develEnv = false;

  $develEnv = !file_exists( 'setup.final.php' );

  /*
  if( isset( $_SERVER['REMOTE_ADDR'] ) && $_SERVER['REMOTE_ADDR'] != 'local_shell' ) {
    if( isPrivateIp( $_SERVER['REMOTE_ADDR'] ) ) {
      $develEnv = true;
    }
  }
  else {
    // ESTO HAI QUE REPASALO !!!!
    $ipLocal = gethostbyname( gethostname() );
    error_log( 'IP LOCAL: '. $ipLocal );
    if( isPrivateIp( $ipLocal ) ) {
      $develEnv = true;
    }
  }
  */

  return $develEnv;
}


function isPrivateIp( $ip ) {
  return( strpos( $ip, '127.' ) === 0 || !filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) );
}
