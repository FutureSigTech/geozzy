var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }


geozzy.explorerComponents.resourcePartialCollection = Backbone.Collection.extend({
  url: false,
  useLocalStorage: true,
  model: geozzy.explorerComponents.resourcePartialModel,
  lastCacheUpdate: false,
  options:{},
  allResourcesLoading: false,
  allResourcesLoaded: false,


  initialize( opts ) {
    var that = this;

    that.getLocalStorage();

  },

  fetchByIds: function( params ) {
    var that = this;


    if( that.lastCacheUpdate === false ) {

      if( params.ids.length === 1 && that.get(params.ids[0]) ){
        if(params.success) { params.success(); }
      }
      else
      if( that.allResourcesLoaded === false ) {
        that.fetch({
          data: {ids: params.ids},
          type: 'POST',
          remove: false,
          success: function( list ) {
            if(params.success) {
              params.success();
            }
            that.fetchFull();
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

      params.success();
    }

  },

  fetchFull: function(  ) {
    var that = this;

    if( that.allResourcesLoading === false  && that.allResourcesLoaded === false ) {
      that.allResourcesLoading = true;
      that.fetch({
        type: 'POST',
        remove: false,
        success: function( list ) {

          that.allResourcesLoaded = true;
          that.allResourcesLoading = false;

          console.log('Geozzy explorer loaded ' + that.length + ' resources');
          that.saveLocalStorage();

        }
      });

    }
  },




  getLocalStorage( ) {
    var that = this;

    if( that.useLocalStorage === true && typeof Storage !== "undefined" && that.url != false) {
      var lsData;
      var lsDataParsed = false;
      if( ( lsData = localStorage.getItem( that.url ) ) !== null ){

        try{
          lsDataParsed = JSON.parse( lsData );

        }
        catch(e){
          console.log('Geozzy exlorer, failed trying to get localstorage data:' + e);
          lsDataParsed = false;
        }

        that.reset()
        that.lastCacheUpdate = lsDataParsed.lastUpdate;

        that.set( lsDataParsed.resources );

      }

    }

  },

  saveLocalStorage( ) {
    var that = this;

    if( that.useLocalStorage === true && typeof Storage !== "undefined" && that.url != false) {

      localStorage.removeItem( that.url );


      localStorage.setItem( that.url, JSON.stringify({ lastUpdate: new Date().getTime() , resources: that.toJSON() }) )

    }


  }

});
