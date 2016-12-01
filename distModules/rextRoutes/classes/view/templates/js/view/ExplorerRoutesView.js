var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.routesCollectionInstance = false;


geozzy.explorerComponents.routesView = Backbone.View.extend({

  displayType: 'plugin',
  parentExplorer: false,

  template: false,

  visible: false,

  initialize: function( opts ) {
    var that = this;
    var options = new Object({
      showGraph: false,
      hoverGraphDiv: false,
      ShowRouteInZoomLevel: 10,
      routeResolution: 90,
      showMarkerStart: false,
      showMarkerEnd: false
    });

    that.options = $.extend(true, {}, options, opts);

  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;

    that.parentExplorer.bindEvent('resourceHover', function( params ){

      //that.hideRoute();
      //alert(params.id)
      if( geozzy.explorerComponents.routesCollectionInstance === false ) {
        geozzy.explorerComponents.routesCollectionInstance = new geozzy.rextRoutes.routeCollection();
      }



      if( typeof( geozzy.explorerComponents.routesCollectionInstance.get( params.id ) ) != 'undefined' ){

        geozzy.explorerComponents.routesCollectionInstance.get( params.id ).get('routeViewInstance').showRoute();

      }
      else {
        //var routesCollectionProvisional = new geozzy.rextRoutes.routeCollection();
        //routesCollectionProvisional.url = '/api/routes/id/' + params.id + '/resolution/' + that.options.routeResolution;
        geozzy.explorerComponents.routesCollectionInstance.fetchOne(
          'id/' + params.id + '/resolution/' + that.options.routeResolution ,
          function(  ) {


            setTimeout(function(){

              var r = geozzy.explorerComponents.routesCollectionInstance.get(params.id);


              var routeOpts = {
                    map: that.parentExplorer.displays.map.map,
                    routeModel: r,
                    showGraph: that.options.showGraph,
                    graphContainer: that.options.hoverGraphDiv ,
                    showLabels: false,
                    allowsTrackHover:false,
                    ShowRouteInZoomLevel: that.options.ShowRouteInZoomLevel,
                    drawXGrid: false,
                    drawYGrid: false,
                    pixelsPerLabel:100,
                  };

              if( that.options.showMarkerStart == false ) {
                routeOpts.markerStart = false;
              }

              if( that.options.showMarkerEnd == false ) {
                routeOpts.markerEnd = false;
              }



              r.set('routeViewInstance', new geozzy.rextRoutes.routeView( routeOpts ));


                //geozzy.explorerComponents.routesCollectionInstance.set( res.toJSON() );
                //console.log(geozzy.explorerComponents.routesCollectionInstance.get(params.id) );



            }, 3000);




        });
      }




    });

    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      //that.hide(params.id);
      that.hideRoutes();
    });


    that.parentExplorer.bindEvent('resourceClick', function( params ){
      //that.show(params.id);
    });

  },

  hideRoutes: function() {
    var that = this;

    if(geozzy.explorerComponents.routesCollectionInstance) {
      geozzy.explorerComponents.routesCollectionInstance.each(  function(e,i){
        e.get('routeViewInstance').hideRoute();
      });
    }
  }



});
