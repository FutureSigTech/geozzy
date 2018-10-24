var geozzy = geozzy || {};
if(!geozzy.userSessionComponents) geozzy.userSessionComponents={};


geozzy.userSessionComponents.modalMdTemplate = ''+
'<div id="<%- modalId %>" class="modal fade" tabindex="-1" role="dialog">'+
  '<div class="modal-dialog modal-md">'+
    '<div class="modal-content">'+
      '<div class="modal-header">'+
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
        '<img class="iconModal img-fluid" src="'+cogumelo.publicConf.media+'/img/iconModal.png"></img>'+
        //'<h3 class="modal-title"><%- modalTitle %></h3>'+
      '</div>'+
      '<div class="modal-body"></div>'+
    '</div>'+
  '</div>'+
'</div>';

geozzy.userSessionComponents.userLoginBoxTemplate = ''+
'<div class="loginInfoContainer">'+
  /*'<h3>'+__("Necesitas tener una cuenta para participar")+'</h3>'+
  '<button type="button" class="gotoregister btn btnGeozzySampleApp">'+__("Crear una cuenta")+'</button>'+
  '<hr />'+
  '<h3>'+__("¿Ya tienes una cuenta?")+'</h3>'+*/
  '<h3>'+__("Inicia sesión con tu cuenta para continuar")+'</h3>'+
  '<div class="loginModalForm"></div>'+
'</div>'+
'<a class="initRecoveryPass">'+__("He olvidado mi contraseña")+'</a>'+
'<div class="recoveryPasswordForm" style="display:none;">'+
  '<h3>'+__("Recovery password")+'</h3>'+
  '<p>'+__("Introduce la dirección de correo electrónico asociada a tu cuenta y te enviaremos un enlace para restablecer tu contraseña")+'</p>'+
  '<form><div class="cgmMForm-wrap"><input type="text" class="recoveryPassEmail" placeholder="Email"></div>'+
  '<div class="cgmMForm-wrap"><input value="'+__("Send")+'" type="button" class="recoveryPassSubmit btn btnGeozzySampleApp pull-right"></div></form>'+
'</div>'+
'<div class="recoveryPasswordFinalMsg" style="display:none;">'+
  '<div class="alert alert-success" role="alert"><i class="far fa-check-circle" aria-hidden="true"></i>    '+__("Correo electrónico enviado correctamente")+'</div>'+
'</div>';

geozzy.userSessionComponents.userRegisterBoxTemplate = ''+
'<h3>'+__("Bienvenido a nuestra web Geozzy Sample App. Completa los campos solicitados a continuación para finalizar tu registro en la plataforma")+'.</h3>'+
'<div class="registerModalForm"></div>';


geozzy.userSessionComponents.userRegisterOkBoxTemplate = ''+
'<h3>'+__("Muchas gracias")+'.</h3>'+
'<h3>'+__("Tu cuenta se ha creado con éxito")+'</h3>'+
// '<p>'+__("En breve recibirás un correo-e de confirmación con tu número de expediente")+'.</p>'+
'<p>'+__("Si no lo recibes en las próximas horas, comprueba tu bandeja de SPAM")+'</p>'+
'<button type="button" class="btn btnGeozzySampleApp" data-dismiss="modal">'+__("Continuar")+'</button>';
