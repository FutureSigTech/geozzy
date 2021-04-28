<?php

Cogumelo::load('coreView/View.php');
Cogumelo::autoIncludes();

common::autoIncludes();
form::autoIncludes();
filedata::autoIncludes();


class FormTestView extends View {

  public function accessCheck() {
    return true;
  }

  /**
   * Preparamos la visualizacion de la pagina
   **/
  public function viewForm() {
    $pageTemplate = new Template();

    $form = new FormController( 'formTest', '/formTest/sendForm' );
    $fieldsInfo = [
      'testNome' => [
        'params' => [ 'label' => 'TEST NOME' ],
        'rules' => [ /* 'required' => true,  */'maxlength' => '250' ]
      ],
      'testFoto' => [
        'params' => [
          'id' => 'testFoto', 'label' => 'TEST FOTO',
          'destDir' => '/FormTest',
          // 'data-cmpReduceMax' => 1600, // image-blob-reduce
          'data-cmpjsQuality' => 0.80, // compressorjs
          'data-cmpjsMaxWidth' => 1920, // compressorjs
          'type' => 'file'
        ],
        'rules' => [ 'maxfilesize' => 20000000 ]
      ],
      'submit' => [
        'params' => [ 'type' => 'submit', 'value' => 'Enviar' ]
      ]
    ];
    $form->definitionsToForm( $fieldsInfo );
    $form->setSuccess( 'accept', 'Envio OK. Recargamos para probar outra vez...' );
    $form->setSuccess( 'reload' );
    $form->saveToSession();

    $pageTemplate->assign( 'formTestOpen', $form->getHtmpOpen() );
    $pageTemplate->assign( 'formTestFields', $form->getHtmlFieldsArray() );
    $pageTemplate->assign( 'formTestClose', $form->getHtmlClose() );
    $pageTemplate->assign( 'formTestValidations', $form->getScriptCode() );

    $pageTemplate->setTpl( 'FormTest.tpl', 'FormTest' );



    $pageTemplate->addClientStyles( 'styles/primary.scss' );
    $pageTemplate->addClientScript( 'js/resource.js' );
    $pageTemplate->exec();
  }

  /**
   * Procesamos el formulario
   **/
  public function sendForm( $urlParams = false ) {
    //
    // URL: /formTest/sendForm
    //

    $form = new FormController();

    // Recuperamos form
    if( !$form->loadPostInput() ) {
      $form->addFormError( 'No han llegado los datos o lo ha hecho con errores.' );
    }

    if( !$form->existErrors() && !$form->processFileFields() ) {
      $form->addFormError( 'Ha sucedido un problema con los ficheros adjuntos.', );
    }

    if( !$form->existErrors() ) {
      error_log( 'TEST NOME: '.$form->getFieldValue( 'testNome' ) );

      if( !empty( $form->getFieldValue( 'testFoto' ) ) ) {

        $fileField = $form->getFieldValue( 'testFoto' );
        error_log( 'TEST FOTO: '.print_r( $fileField, true ) );

        $filedataCtrl = new FiledataController();
        if( isset( $fileField['status'] ) ) {
          switch( $fileField['status'] ) {
            case 'LOADED':
              $fileField['values']['privateMode'] = true;
              $newFiledataObj = $filedataCtrl->createNewFile( $fileField['values'] );
              if( $newFiledataObj ) {
                error_log( 'Fichero newFiledataObj ID: '.$newFiledataObj->getter( 'id' ) );
              }
              else {
                error_log( 'Fichero newFiledataObj ERROR' );
                $form->addFormError( 'Fichero newFiledataObj ERROR' );
              }
              break;
            default:
              error_log( 'Fichero STATUS='.$fileField['status'] );
              $form->addFormError( 'Fichero STATUS='.$fileField['status'] );
            break;
          } // switch
        }
      }
    }

    // Notificamos el resultado al UI
    $form->sendJsonResponse();
  }


}
