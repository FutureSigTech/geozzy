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
      that.refreshVisibleRoutes();
    });

    that.parentExplorer.bindEvent('resourceHover', function( params ){
      var r = that.parentExplorer.resourceMinimalList.get(params.id);
      var routeAttributes = that.getRouteAttributes(r.get('id'));

      if(  routeAttributes.routeInExplorerHoverShow != false ) {
        that.showRoute(params.id);
      }
    });


    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      that.refreshVisibleRoutes( );
    });


    that.parentExplorer.bindEvent('resourceClick', function( params ){
      that.refreshVisibleRoutes();
    });


    that.parentExplorer.bindEvent('minimalLoadSuccess', function(ev){
      that.showPermanentRoutes();
    });

    that.parentExplorer.bindEvent('applyFilters', function(ev){
      that.refreshVisibleRoutes( );
    });





  },

  showPermanentRoutes: function() {
    var that = this;
    that.parentExplorer.resourceMinimalList.each( function(r,i) {

      var routeAttributes = that.getRouteAttributes(r.get('id') );
      if( routeAttributes.isRoute && routeAttributes.routeInExplorerHoverShow == false) {
        //console.log('mostrar ruta permanente',r.get('id'));
        that.showRoute(r.get('id'));
      }
    });
  },



  showRoute: function( id ) {
    var that = this;
    var r = that.parentExplorer.resourceMinimalList.get(id);
    var routeAttributes = that.getRouteAttributes(r.get('id'));


    if( geozzy.explorerComponents.routesCollectionInstance === false ) {
      geozzy.explorerComponents.routesCollectionInstance = new geozzy.rextRoutes.routeCollection();
    }


    if( typeof( geozzy.explorerComponents.routesCollectionInstance.get( id ) ) != 'undefined' ){
      geozzy.explorerComponents.routesCollectionInstance.get( id ).get('routeViewInstance').showRoute();
    }
    else if( routeAttributes.isRoute  ) {
      that.fetchRoute(id, function( route ) {
/*
          if( r.get('mapMarker').visible == false ){
            route.get('routeViewInstance').hideRoute();
          }
          else {
            route.get('routeViewInstance').showRoute();
          }*/


          if( that.getLoadingPromise(id) ) {
            route.get('routeViewInstance').hideRoute();
          }

      });
    }
  },


  hideRoute: function( id ) {

    var that = this;
    var r = that.parentExplorer.resourceMinimalList.get(id);
    var routeAttributes = that.getRouteAttributes(id);
    var route = geozzy.explorerComponents.routesCollectionInstance.get(id);

    route.get('routeViewInstance').hideRoute();
    if( route &&  typeof route.get('routeViewInstance') != 'undefined' ) {
      route.get('routeViewInstance').hideRoute();
    }
    else {
      that.setLoadingPromise(id);
    }


  },

  refreshVisibleRoutes: function() {
    var that = this;

    if(geozzy.explorerComponents.routesCollectionInstance) {
      geozzy.explorerComponents.routesCollectionInstance.each(  function(e,i){

        var r = that.parentExplorer.resourceMinimalList.get(e.get('id'));
        var routeAttributes = that.getRouteAttributes(r.get('id'));

        e.get('routeViewInstance').hideRoute();
        if(  routeAttributes.routeInExplorerHoverShow == false && r.get('mapMarker').visible == true  ) {
          e.get('routeViewInstance').showRoute();
        }



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
  },


  fetchRoute: function(id, onLoad) {
    var that = this;
    var routeAttributes = that.getRouteAttributes(id);

    geozzy.explorerComponents.routesCollectionInstance.fetchOne(
      'id/' + id + '/resolution/' + that.options.routeResolution ,
      function() {
        var route = geozzy.explorerComponents.routesCollectionInstance.get(id);

        var routeOpts = {
          map: that.parentExplorer.displays.map.map,
          routeModel: route,
          showGraph: routeAttributes.routeAltitudeShow,
          graphContainer: that.options.hoverGraphDiv ,
          showLabels: false,
          ShowRouteInZoomLevel: that.options.ShowRouteInZoomLevel,
          drawXGrid: false,
          drawYGrid: false,
          pixelsPerLabel:100,
          axisLineColor: 'transparent',
          allowsTrackHover: !routeAttributes.routeInExplorerHoverShow ,
          hoverTrackMarker: false,
          onMouseover: function(id) {
            if(routeAttributes.routeInExplorerHoverShow == false ) {
              that.parentExplorer.triggerEvent('resourceHover', { id: id });
            }
          },
          onMouseOut: function(id) {
            that.parentExplorer.triggerEvent('resourceMouseOut', {id: id });
          },
          onMouseClick: function( id ) {
            if( that.options.avoidClick != true && routeAttributes.routeInExplorerHoverShow == false ) {
              that.parentExplorer.triggerEvent('resourceClick', { id: id });
            }
          }
        };

        if( that.options.showMarkerStart == false ) {
          routeOpts.markerStart = false;
        }

        if( that.options.showMarkerEnd == false ) {
          routeOpts.markerEnd = false;
        }
        if( typeof route.get('routeViewInstance') == 'undefined' ) {
          route.set('routeViewInstance', new geozzy.rextRoutes.routeView( routeOpts ));
          onLoad( route );
        }

      }
    );
  },

  getRouteAttributes: function(id) {
    var that = this;
    var r = that.parentExplorer.resourceMinimalList.get(id);

    return {
      isLoading: false,
      isRoute: ( (that.options.interactionKeys.isRoute == false) || r.get(that.options.interactionKeys.isRoute) != false )? true: false,
      routeInExplorerHoverShow: ( that.options.interactionKeys.routeInExplorerHoverShow != false && r.get(that.options.interactionKeys.routeInExplorerHoverShow ) == false && that.options.alwaysShow == false )? false: true,
      routeAltitudeShow: ((that.options.interactionKeys.routeAltitudeShow == false || r.get(that.options.interactionKeys.routeAltitudeShow)) && that.options.showGraph == true )? true: false
    };
  }

});
