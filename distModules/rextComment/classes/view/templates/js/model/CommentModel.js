var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.CommentModel = Backbone.Model.extend({
  defaults: {
    id: false,
    type: 0,
    typeIdName: '',
    resource: 0,
    published: 0,
    content: '',
    timeCreation: '',
    rate: 0,
    suggestType: 0,
    suggestTypeName: '',
    status: 0,
    anonymousName: '',
    anonymousEmail: '',
    user: 0,
    userName: '',
    userEmail: '',
    userVerified: false
  }
});
