var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {}; }
geozzy.explorerComponents.resourcePartialCollection = Backbone.Collection.extend({
  url: false,
  useLocalStorage: true,
  model: geozzy.explorerComponents.resourcePartialModel,
  lastCacheUpdate: false,
  options:{
    fetchType: 'POST'
  },
  allResourcesLoading: false,
  allResourcesLoaded: false,
  initialize: function( opts ) {
    var that = this;
    cogumelo.log('getLocalStorage START');
    that.getLocalStorage();
    cogumelo.log('getLocalStorage END');
  },
  extendOptions: function( opts ) {
    $.extend( true, this.options, opts );
  },
  fetchByIds: function( params ) {
    var that = this;
    if( that.lastCacheUpdate === false ) {
      if( typeof params.data.ids !== 'undefined' && params.data.ids.length === 1 && that.get(params.data.ids[0]) ){
        if(params.success) {
          params.success();
          cogumelo.log('Partial Success (Primeiro)');
        }
      }
      else
      if( that.allResourcesLoaded === false ) {
        that.fetch({
          data: params.data,
          type: that.options.fetchType,
          // type: 'POST',
          remove: false,
          success: function( list ) {
            if(params.success) {
              params.success();
            }
            params.data = false;
            that.fetchFull( params );
          }
        });
      }
      else {
        if(params.success) {
          params.success();
        }
      }
    }
    else {
      params.data.lastCacheUpdate = that.lastCacheUpdate
      params.data = false;
      that.fetchFull( params );
      params.success(  );


    }

  },

  fetchFull: function( paramsFf ) {
    var that = this;


    if( that.allResourcesLoading === false  && that.allResourcesLoaded === false ) {
      that.allResourcesLoading = true;
      that.fetch({
        type: that.options.fetchType,
        // type: 'POST',
        remove: false,
        data: paramsFf.data,
        success: function( list ) {
          cogumelo.log('Geozzy explorer loaded ' + that.length + ' resources');
          that.saveLocalStorage();
          if( paramsFf.success) {
            paramsFf.success();
            //cogumelo.log('Partial Success (fetchFull Ajax)');
          }
          that.allResourcesLoaded = true;
          that.allResourcesLoading = false;
        }
      });

    }
    else {
      if(paramsFf.success) {

        var successInterval = setInterval(function() {
          if( that.allResourcesLoading == false) {
            paramsFf.success();
            clearInterval(successInterval);
            //cogumelo.log('Partial Success (Direct, only success event)');
          }
          else {
            //cogumelo.log('Partial Success (Direct, only success event Waiting...)');
          }
        }, 50);
        //paramsFf.success();
      }
    }
  },


  getLocalStorage: function( ) {
    var that = this;
    if( that.useLocalStorage === true && typeof Storage !== "undefined" && that.url != false) {
      var lsData;
      var lsDataParsed = false;
      if( ( lsData = localStorage.getItem( that.url ) ) !== null ){
        try{
          lsDataParsed = JSON.parse( lsData );
        }
        catch(e){
          cogumelo.log('Geozzy exlorer, failed trying to get localstorage data:' + e);
          lsDataParsed = false;
        }
        that.reset();
        that.lastCacheUpdate = lsDataParsed.lastUpdate;
        that.set( lsDataParsed.resources );
      }
    }
  },
  saveLocalStorage: function( ) {
    var that = this;
    if( that.useLocalStorage === true && typeof Storage !== "undefined" && that.url != false) {
      localStorage.removeItem( that.url );
      localStorage.setItem( that.url, JSON.stringify({ lastUpdate: Math.floor(Date.now() / 1000) , resources: that.toJSON() }) );
    }
  }
});
