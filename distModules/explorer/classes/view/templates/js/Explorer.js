var geozzy = geozzy || {};

geozzy.explorerComponents.routerInstance = false;
geozzy.explorer = function( opts ) {

  var that = this;


  that.getLang= function(){
    var lang = false;
    if (typeof(cogumelo.publicConf.C_LANG)!='undefined'){
      lang = '/'+cogumelo.publicConf.C_LANG;
    }
    return lang;
  }

  //  Options

  that.options = {
    explorerSectionName: 'Geozzy Explorer',
    explorerAPIHost: '/api/explorer/',
    explorerId: 'default',

    firstLoadSuccess: function() {},

    aditionalParameters: {},

    // cache times (in seconds)
    cacheTimeIndex: 20,
    debug: false,

    // events
    filterChangeEvent: function(){},
    filteringEndEvent: function(){},
    firstLoadEvent: function(){},
    resourceAccess: function( ){ return false;},
    resourceQuit: function( ) { return false;}
  }
  $.extend(true, that.options, opts);

  that.explorerTouchDevice = $('html').hasClass('touch');
  // metrics
  that.metricsExplorerController = geozzy.biMetricsInstances.explorer;
  that.metricsResourceController = geozzy.biMetricsInstances.resource;


  // events
  that.explorerEvents = [];


  //  Debuger
  that.timeDebugerMain = new TimeDebuger( {debug: that.options.debug, instanceName:'Explorer main'} );
  that.timeDebugerExtended =  new TimeDebuger( {debug: that.options.debug, instanceName:'Explorer extended data'} );


  // Resource Collections and Indexes
  that.resourceIndex = false;
  that.resourceMinimalList = new geozzy.explorerComponents.resourceMinimalCollection();
  that.resourcePartialList =  false;//new geozzy.explorerComponents.resourcePartialCollection();


  // Displays
  that.displays = {
    map: false,
    activeList: false,
    reccomendList: false,
    plugins: []
  }

  // filters
  that.filters = [ ];






  //
  // First Execution
  //

  that.exec = function( ) {
    // set multiple fetches
    lang = that.getLang();

    that.resourceMinimalList.url = lang + that.options.explorerAPIHost + 'explorer/' + that.options.explorerId+ '/request/minimal';


    // set explorer router
    geozzy.explorerComponents.routerInstance = new geozzy.explorerComponents.mainRouter();
    geozzy.explorerComponents.routerInstance.parentExplorer = that;

    if( !Backbone.History.started ){
      Backbone.history.start();
    }
    else {
      Backbone.history.stop();
      Backbone.history.start();
    }


    that.bindEvent('resourceClick', function(param){

      if( that.options.resourceAccess(param.id) !== false ) {
        geozzy.explorerComponents.routerInstance.navigate('resource/'+param.id);
        //that.metricsResourceController.eventAccessed(param.id, 'Explorer: '+that.options.explorerSectionName );
      }

    });



    $(document).ready( function(){
      if( !Backbone.History.started ){
        Backbone.history.start();
      }
    });

    // render filters
    if( that.filters.length > 0 ) {
      $.each( that.filters, function(i,e){
        e.render();
      });
    }


    that.resourceMinimalList.fetch(
      {
        cache: true,
        type: 'POST',
        data: that.options.aditionalParameters ,
        expires: that.options.cacheTimeIndex ,
        success: function() {
          that.timeDebugerMain.log('&nbsp;- Fetch first resource index with '+ that.resourceMinimalList.length + ' elements');
          that.resourceIndex = new Backbone.Obscura(that.resourceMinimalList);
          that.timeDebugerMain.log( '&nbsp;- Resources Indexed first time' );
          that.applyFilters();
          that.options.firstLoadSuccess();
        }
      }

    );

  }


  //
  // Apply filters
  //

  that.addFilter = function( filter ) {
    filter.parentExplorer = this;
    that.filters.push( filter );

  }

  that.applyFilters = function() {


    that.timeDebugerMain.reset();
    that.timeDebugerExtended.reset();

    if(typeof that.resourceIndex.removePagination != 'undefined'){
      that.resourceIndex.removePagination();
    }


    // Set filters for current index

    that.resourceIndex.filterBy( function(model) {



      var matches = 0;
      var ret = false;

      $.each( that.filters, function(i, filter){

        if( filter.filterAction( model ) ) {
          matches++;
          return;
        }

      });

      if( matches == that.filters.length ) {
        ret = true
      }

      // if matches number is same of filters number
      return ret;

    });
    that.timeDebugerMain.log( '&nbsp;- Resultado filtrado final '+ that.resourceIndex.length + ' Records' );



    that.render();
  }

  //
  // Display methods
  //

  that.addDisplay = function( displayObj ){

    if( displayObj.displayType == 'map' ) {
      that.displays.map = displayObj;
      that.displays.map.setParentExplorer( that );
    }
    else
    if( displayObj.displayType == 'activeList' ) {
      that.displays.activeList = displayObj;
      that.displays.activeList.setParentExplorer( that );
    }
    else
    if( displayObj.displayType == 'reccomendList' ) {
      that.displays.reccomendList = displayObj;
      that.displays.reccomendList.setParentExplorer( that );
    }
    else
    if( displayObj.displayType == 'plugin' ) {
      displayObj.setParentExplorer( that );
      that.displays.plugins.push( displayObj );
    }
    else {
      console.log('Geozzy explorer ERROR: Display type key not found');
    }
  }



  that.render = function( dontRenderMap ) {

    var resourcesToLoad = [];
    var metricData = {bounds:[], zoom: false,  filters:[0], explorerId: that.options.explorerId };


    if(that.displays.map ) {

      if( that.displays.map.isReady() ){

        var mapbounds = that.displays.map.getMapBounds();
        metricData.zoom = that.displays.map.map.getZoom();

        metricData.bounds = [ [mapbounds[0].lat(), mapbounds[0].lng()], [mapbounds[1].lat(), mapbounds[1].lng()] ];

        resourcesToLoad = that.displays.map.getVisibleResourceIds();
        //resourcesToLoad = $.merge( that.displays.map.getVisibleResourceIds() , resourcesToLoad);

        $.each(that.filters, function(i,e) {
          metricData.filters = $.merge( metricData.filters, e.getSelectedTerms() );

        });

        // add metric
        that.metricsExplorerController.addMetric(metricData);
      }

      if( !dontRenderMap ) {
        that.displays.map.render();
      }
    }

    if(that.displays.activeList) {
      that.displays.activeList.currentPage = 0;
      resourcesToLoad = $.merge( that.displays.activeList.getVisibleResourceIds() , resourcesToLoad);
    }

    if(that.displays.pasiveList) {
      that.displays.pasiveList.currentPage = 0;
      resourcesToLoad = $.merge( that.displays.pasiveList.getVisibleResourceIds() , resourcesToLoad);
    }



    // Advanced Fetch
    that.timeDebugerExtended.log('Starting second data fetch at')

    that.fetchPartialList(
      resourcesToLoad,
      function() {
        that.timeDebugerExtended.log( '&nbsp;- Fetch partial resource data' );

        if(that.displays.activeList) {
          that.displays.activeList.render();
        }

        if(that.displays.reccomendList) {
          that.displays.reccomendList.render();
        }

        if( that.displays.plugins.length > 0 ) {
          $.each( that.displays.plugins, function(pluginIndex, plugin) {
            plugin.render();
          });
        }

        that.timeDebugerExtended.log( '&nbsp;- Render lists' );

      }

    );

  },


  that.fetchPartialList = function( resourcesToLoad, fetchSuccess ) {
    lang = that.getLang();



    if( that.resourcePartialList === false ) {
      var partialCollection = geozzy.explorerComponents.resourcePartialCollection.extend(
        {
          url: lang + that.options.explorerAPIHost + 'explorer/' + that.options.explorerId+ '/request/partial'
        });

      that.resourcePartialList = new partialCollection();
    }


    that.resourcePartialList.fetchByIds({
      ids: resourcesToLoad,
      success: function() {
        fetchSuccess();

      }
    });


  }


  that.setMetricsExplorer = function( obj ) {

    that.metricsExplorerController = obj;//new geozzy.biMetrics.controller.explorer();

  }

  that.setMetricsResource = function( obj) {

    that.metricsResourceController = obj;
  }


  that.triggerEvent = function( eventName, parameters) {
    var that = this;


    $.each( that.explorerEvents, function( i, event ){
      if( typeof event.name != 'undefined' && event.name == eventName  ) {
        if( typeof event.action != 'undefined'  ) {
          event.action( parameters );
        }
      }
    });
  }


  that.bindEvent = function( eventName, action ) {
    var that = this;

    that.explorerEvents.push( {
      name: eventName,
      action: action
    });
  }


}
