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
      routeResolution: 12,
      showMarkerStart: false,
      showMarkerEnd: false,
      alwaysShow: false, // always show, ignore hover
      interactionKeys: {
        'isRoute': false,
        'routeAltitudeShow': false,
        'routeInExplorerHoverShow': false
      }
    });

    that.options = $.extend(true, {}, options, opts);

  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;

    that.parentExplorer.bindEvent('zoomChanged', function(){
      that.hideRoutes();
    });

    that.parentExplorer.bindEvent('resourceHover', function( params ){
      that.showRoute(params.id);
    });


    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      that.hideRoutes( );
    });


    that.parentExplorer.bindEvent('resourceClick', function( params ){
      //that.show(params.id);
    });



    that.parentExplorer.bindEvent('minimalLoadSuccess', function(ev){
      console.log('minimalLoadSuccess')
      that.parentExplorer.resourceMinimalList.each( function(r,i) {
        var routeInExplorerHoverShow = ( ( that.options.interactionKeys.routeInExplorerHoverShow == false || r.get(that.options.interactionKeys.routeInExplorerHoverShow ) == 1 ) || that.options.alwaysShow == true )? true: false;


        if(routeInExplorerHoverShow == false) {
          console.log('mostrar ruta', r.get('id'));
          that.showRoute(r.get('id'));
        }

      });
    });


  },


  showRoute: function( id ) {
    var that = this;
    var r = that.parentExplorer.resourceMinimalList.get(id);
    var isRoute = ( (that.options.interactionKeys.isRoute == false) || r.get(that.options.interactionKeys.isRoute) != false )? true: false;
    var routeAltitudeShow = ((that.options.interactionKeys.routeAltitudeShow == false || r.get(that.options.interactionKeys.routeAltitudeShow)) && that.options.showGraph == true )? true: false;


    if( geozzy.explorerComponents.routesCollectionInstance === false ) {
      geozzy.explorerComponents.routesCollectionInstance = new geozzy.rextRoutes.routeCollection();
    }


    if( typeof( geozzy.explorerComponents.routesCollectionInstance.get( id ) ) != 'undefined' ){
      geozzy.explorerComponents.routesCollectionInstance.get( id ).get('routeViewInstance').showRoute();
    }
    else if( isRoute  ) {
      geozzy.explorerComponents.routesCollectionInstance.fetchOne(
        'id/' + id + '/resolution/' + that.options.routeResolution ,
        function(  ) {

          var r = geozzy.explorerComponents.routesCollectionInstance.get(id);
          var routeOpts = {
            map: that.parentExplorer.displays.map.map,
            routeModel: r,
            showGraph: routeAltitudeShow,
            graphContainer: that.options.hoverGraphDiv ,
            showLabels: false,
            allowsTrackHover:false,
            ShowRouteInZoomLevel: that.options.ShowRouteInZoomLevel,
            drawXGrid: false,
            drawYGrid: false,
            pixelsPerLabel:100,
            axisLineColor: 'transparent'
          };

          if( that.options.showMarkerStart == false ) {
            routeOpts.markerStart = false;
          }

          if( that.options.showMarkerEnd == false ) {
            routeOpts.markerEnd = false;
          }

          r.set('routeViewInstance', new geozzy.rextRoutes.routeView( routeOpts ));
          if( that.getLoadingPromise(id) ) {
            r.get('routeViewInstance').hideRoute();
          }

      });
    }
  },


  hideRoute: function( id ) {
    var that = this;
    var r = that.parentExplorer.resourceMinimalList.get(id);
    var routeInExplorerHoverShow = ( (that.options.interactionKeys.routeInExplorerHoverShow != false || r.get(that.options.interactionKeys.routeInExplorerHoverShow ) != false) && that.options.alwaysShow == false )? true: false;
    var route = false;
    if(geozzy.explorerComponents.routesCollectionInstance != false) {
      route = geozzy.explorerComponents.routesCollectionInstance.get(id);
      if( route &&  typeof route.get('routeViewInstance') != 'undefined' && routeInExplorerHoverShow == true ) {
        route.get('routeViewInstance').hideRoute();
      }
      else {
        that.setLoadingPromise(id);
      }
    }
  },


  hideRoutes: function() {
    var that = this;

    if(geozzy.explorerComponents.routesCollectionInstance) {
      geozzy.explorerComponents.routesCollectionInstance.each(  function(e,i){
        e.get('routeViewInstance').hideRoute();
        that.hideRoute(e.get('id'));
      });
    }
  },


  hideLoadingPromises: [],
  setLoadingPromise: function( id ) {
    var that = this;

    that.hideLoadingPromises.push(id);
  },

  getLoadingPromise: function(id) {
    var that = this;
    var endLoadingPromises = [];
    var found = false;

    $( that.hideLoadingPromises ).each( function(i,e){
      if(e == id) {

        found = true;
      }
      else {
        endLoadingPromises.push(e);
      }
    });

    that.hideLoadingPromises = endLoadingPromises;

    return found;
  }





});
