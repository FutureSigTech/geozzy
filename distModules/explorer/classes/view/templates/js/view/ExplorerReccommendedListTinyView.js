var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.reccommendedListView = Backbone.View.extend({

  displayType: 'reccomendList',
  parentExplorer: false,

  events: {

  },

  initialize: function( opts ) {


  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;
  },

  render: function() {

    var that = this;

    var col = new geozzy.collection.ResourceCollection({urlAlias: true});


    if( that.parentExplorer.displays.map  && that.parentExplorer.displays.map.isReady() ) {
      var bounds = that.parentExplorer.displays.map.getMapBoundsInArray();
    }
    else {
      var bounds = [[0,0],[0,0]];
    }


    geozzy.biMetricsInstances.recommender.explorer( that.parentExplorer.options.explorerId , bounds, function(res){

      // Para pruebas
      if (res.length>0){
        var res_ids = [];


        $.each(res, function(i,e){
          res_ids.push(e.resource_id)
          console.log('recomendado por ITG:', e.resource_id)
        });

        col.fetchByIds(res_ids, function(){
          col.each( function(elm, i){
            //$(that.el).append(that.template({
            //  id:elm.get('id'),
            //  title:elm.get('title'),
            //  image:elm.get('image'),
            //  urlAlias:elm.get('urlAlias'),
            //  shortDescription:elm.get('shortDescription')
            //}));

            console.log('Recomendado existe: ',elm);
          });

        });

      }

    });



  }

});