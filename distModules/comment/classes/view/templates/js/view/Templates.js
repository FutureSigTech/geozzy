var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};


geozzy.commentComponents.modalMdTemplate = ''+
'<div id="<%- modalId %>" class="modal fade" tabindex="-1" role="dialog">'+
  '<div class="modal-dialog modal-md">'+
    '<div class="modal-content">'+
      '<div class="modal-header">'+
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
        '<h3 class="modal-title"><%- modalTitle %></h3>'+
      '</div>'+
      '<div class="modal-body"></div>'+
    '</div>'+
  '</div>'+
'</div>';

geozzy.userSessionComponents.commentFormTemplate = ''+
'<div class="commentFormModal"></div>';
