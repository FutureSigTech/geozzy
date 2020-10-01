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
      strokeHoverColor: '#249c00',
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

    });

    that.parentExplorer.bindEvent('resourceHover', function( params ){
      var r = that.parentExplorer.resourceMinimalList.get(params.id);
      console.log('quensoneu',r.get('routeObj').routeInstance);
      if( r && r.get('routeObj') && r.get('routeObj').routeInstance ) {
        if(r.get('routeObj').inExplorerHoverShow == true) {
          r.get('routeObj').routeInstance.showRoute();
        }
        else {
          r.get('routeObj').routeInstance.strokeColorCustom(that.options.strokeHoverColor);
        }


        if( that.options.showGraph && r.get('routeObj').altitudeShow == true ) {
          $(that.options.hoverGraphDiv).show();
          r.get('routeObj').routeInstance.renderGraphRoute(true);
        }
        else {
          $(that.options.hoverGraphDiv).hide();
        }
      }

    });


    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      var r = that.parentExplorer.resourceMinimalList.get(params.id);
      if( r && r.get('routeObj') && r.get('routeObj').routeInstance ) {
        if(r.get('routeObj') && r.get('routeObj').routeInstance && r.get('routeObj').inExplorerHoverShow == true) {
          r.get('routeObj').routeInstance.hideRoute();
        }
        else {
          r.get('routeObj').routeInstance.strokeColorOriginal();
        }
      }


    });


    that.parentExplorer.bindEvent('resourceClick', function( params ){

    });


    that.parentExplorer.bindEvent('minimalLoadSuccess', function(ev){
      that.setRoutesOnResources();
    });

    that.parentExplorer.bindEvent('applyFilters', function(ev){
      that.refreshRoutes();
    });



  },


  refreshRoutes: function() {
    var that = this;

    that.parentExplorer.resourceMinimalList.each( function(r,i) {

      if( r && r.get('routeObj') && r.get('routeObj').routeInstance ) {
        if( r.get('routeObj').inExplorerHoverShow == true || r.get('mapMarker').visible == false ) {
          r.get('routeObj').routeInstance.hideRoute();
        }
        else {
          r.get('routeObj').routeInstance.showRoute();
        }
      }
    });
  },

  setRoutesOnResources: function() {
    var that = this;

    that.parentExplorer.resourceMinimalList.each( function(r,i) {
      that.setRouteOnResource( r );
    });
  },


  setRouteOnResource: function( r ) {
    var that = this;

    var isRoute = ( (that.options.interactionKeys.isRoute == false) || r.get(that.options.interactionKeys.isRoute) != false )? true: false;

    if( !r.get('routeObj') && isRoute ) {

      r.set( 'routeObj', {
        ready: false,
        inExplorerHoverShow: ( that.options.interactionKeys.routeInExplorerHoverShow != false && r.get(that.options.interactionKeys.routeInExplorerHoverShow ) == false && that.options.alwaysShow == false )? false: true,
        altitudeShow: ((that.options.interactionKeys.routeAltitudeShow == false || r.get(that.options.interactionKeys.routeAltitudeShow)) && that.options.showGraph == true )? true: false,
        routeInstanceModel: false,
        routeInstance: false
      });

      that.fetchRoute( r.get('id') , function( routeInstanceModel ) {

        if(routeInstanceModel) {
          r.get('routeObj').routeInstanceModel = routeInstanceModel;
          r.get('routeObj').routeInstance = that.createRouteInstance(r.get('routeObj').routeInstanceModel, r.get('routeObj').inExplorerHoverShow, r.get('routeObj').altitudeShow );

          if( r.get('routeObj').inExplorerHoverShow == true || r.get('mapMarker').visible == false ) {
            r.get('routeObj').routeInstance.hideRoute();
          }
        }

      });


    }
    else {
      r.set( 'routeObj', false );
    }

  },

  createRouteInstance: function(routeModel, inExplorerHoverShow, altitudeShow) {
    var that = this;

    var routeOpts = {
      map: that.parentExplorer.displays.map.map,
      routeModel: routeModel,
      showGraph: false,
      graphContainer: that.options.hoverGraphDiv ,
      showLabels: false,
      ShowRouteInZoomLevel: that.options.ShowRouteInZoomLevel,
      drawXGrid: false,
      drawYGrid: false,
      pixelsPerLabel:100,
      axisLineColor: 'transparent',
      allowsTrackHover: !inExplorerHoverShow ,
      hoverTrackMarker: false,
      onMouseover: function(id) {
        if( inExplorerHoverShow == false ) {
          that.parentExplorer.triggerEvent('resourceHover', { id: id });
        }
      },
      onMouseOut: function(id) {
        that.parentExplorer.triggerEvent('resourceMouseOut', {id: id });
      },
      onMouseClick: function( id ) {
        if( that.options.avoidClick != true && inExplorerHoverShow == false ) {
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

    return  new geozzy.rextRoutes.routeView( routeOpts );
  },


  fetchRoute: function( id, onLoad ) {
    var that = this;


    var routesCollectionInstance = new geozzy.rextRoutes.routeCollection();

    routesCollectionInstance.fetchOne(
      'id/' + id + '/resolution/' + that.options.routeResolution ,
      function(  ) {
        onLoad(routesCollectionInstance.get(id));
      }
    );


  }

});
