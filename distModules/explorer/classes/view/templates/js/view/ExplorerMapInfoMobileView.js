var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

var mapInfoMobileView_is_blocked = false;

geozzy.explorerComponents.mapInfoMobileView = Backbone.View.extend({

  displayType: 'plugin',
  parentExplorer: false,
  template: _.template(""),
  containerMap: false,
  divId: 'geozzyExplorerMapInfoMobile',
  currentMousePos: { x: -1, y: -1 },
  ready: true,
  activeList: false,

  events: {
    // resource events
    "click .accessButton": "resourceClick",
    "click .nextButton": "next",
    "click .previousButton": "previous",
    "click .closeButton": "close",
    /*"swipeleft": "next",
    "swiperight": "previous",*/
    "swipe": "swipeIt"
  },


  initialize: function( opts ) {
    var that = this;
    var options = new Object({
      categories: false,
      tpl: geozzy.explorerComponents.mapInfoViewMobileTemplate,
      enumerateItems: false,
      enumerateConditionKey: false
    });

    that.options = $.extend(true, {}, options, opts);

    that.template = _.template( that.options.tpl );
    that.mousePosEventListener();
  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;

    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      that.hide(params.id);
    });

    that.parentExplorer.bindEvent('resourceHover', function( params ){
      that.show(params.id);
    });

    that.parentExplorer.bindEvent('mobileTouch', function( params ){
      that.show(params.id);
    });




  },


  mousePosEventListener: function() {
    var that = this;

    $(document).mousemove(function(event) {
        that.currentMousePos.x = event.pageX;
        that.currentMousePos.y = event.pageY;
    });
  },

  createInfoMapDiv: function () {
    var that = this;
    var pos = that.getTopLeftPosition();

    var highest = -999;

    $("*").each(function() {
        var current = parseInt($(this).css("z-index"), 10);
        if(current && highest < current) highest = current+1;
    });

    if( $( '#'+that.divId ).length === 0 ) {
      $('body').append( '<div id="' + that.divId + '" style="" ></div>' );
      $('#'+that.divId).hammer();
    }


    //$('#'+that.divId).css('z-index',highest);
  },

  swipeIt: function(e){
    var that = this;
    if (e.gesture.direction == 2) {
      that.next();
    }
    else if (e.gesture.direction == 4) {
      that.previous();
    }
  },


  renderDialog: function( id ) {


  },

  show: function( id ) {
    var that = this;

    that.createInfoMapDiv();
    if(typeof MapIframeCom != 'undefined') {
      MapIframeCom.trigger('hideButtons', {});
    }

    var resourceInfo = new Backbone.Model(  );

    resourceInfo.set(that.parentExplorer.resourceMinimalList.get(id).toJSON());

    that.ready = id;

    that.parentExplorer.fetchPartialList(
      [id],
      function() {
         var minJSON = that.parentExplorer.resourceMinimalList.get( id ).toJSON();
         var element = $.extend( true,
           minJSON,
           that.parentExplorer.resourcePartialList.get( id ).toJSON()
         );
         var elementCategory = false;

         if( that.options.categories /*&& that.options.categories.length>1*/) {
           that.options.categories.each( function(e2){
             //cogumelo.log(e.get('id'));
             //console.debug(markerData.get('terms'));
             if( $.inArray(e2.get('id'), that.parentExplorer.resourceMinimalList.get( id ).get('terms')  ) > -1 ) {

               elementCategory = e2;
               if(e2) {
                 elementCategory = e2.toJSON();
               }
               return false;
               /*
               if( jQuery.isNumeric( e2.get('icon') )  ){
                 return false;
               }*/
             }
           });

         }
         element.category = elementCategory;

         element.touchAccess = that.parentExplorer.explorerTouchDevice;


         // enumeración
         var visibleResources = that.parentExplorer.resourceIndex.setPerPage(10000);
         var contador = 1;

         $.each( visibleResources.pluck( 'id' ), function(i,e){
           if (
             (that.options.enumerateItems == true && that.options.enumerateConditionKey == false) ||
             (that.options.enumerateItems == true && minJSON[that.options.enumerateConditionKey])
           ) {
             if( parseInt(e) == parseInt(id) ) {
               element.enumeration = contador;
               return
             }
             contador++;

           }
           else {
             element.enumeration = false;
           }
         });



         $( '#'+that.divId ).html( that.template( element ) );

         if( that.ready == id){
           that.$el = $('#'+that.divId);
           that.delegateEvents();
           if(that.activeList==false){
             that.activeList = that.getActiveList();
           }
           that.updateArrows();
          $( '#'+that.divId ).show();
         }

      }
    );

  },

  close: function() {
    var that = this;
    that.parentExplorer.triggerEvent('mapInfoWindowMobileClose', false);
    that.hide();
  },

  hide: function() {
    var that = this;

    that.activeList = false;
    that.ready = false;
    $('#'+that.divId).hide();
    $('#'+that.divId+ ' *').remove();
  },

  getTopLeftPosition: function() {
    var that = this;


    return {x: $(that.parentExplorer.displays.map.map.getDiv() ).offset().left , y: $(that.parentExplorer.displays.map.map.getDiv() ).offset().top };
  },

  resourceClick: function() {
    var that = this;

    that.parentExplorer.triggerEvent('resourceClick',{
      id: that.ready,
      section: 'Explorer: '+that.parentExplorer.options.explorerSectionName
    });
  },
  next: function() {
    var that = this;
    var id = parseInt(that.ready);

    var index = $.inArray(id, that.activeList);
    if ( typeof that.activeList[index+1] != 'undefined') {

      var nextResId = that.activeList[index+1];

      that.parentExplorer.triggerEvent('mobileTouch', {id:nextResId});
      that.parentExplorer.triggerEvent('resourceHover', {id:nextResId});
      $( '#'+that.divId+' .gempiItem').animate({
          right: '800px'
      }, 250);
      setTimeout(function(){
        that.show( nextResId );
      }, 250);
    }
  },

  previous: function() {
    var that = this;
    var id = parseInt(that.ready);

    var index = $.inArray(id, that.activeList);
    if ((index -1 ) >= 0) {
      var nextResId = that.activeList[index-1];

      that.parentExplorer.triggerEvent('mobileTouch', {id:nextResId});
      that.parentExplorer.triggerEvent('resourceHover', {id:nextResId});
      $( '#'+that.divId+' .gempiItem').animate({
          left: '800px'
      }, 250);
      setTimeout(function(){
        that.show( nextResId );
      }, 250);
    }

  },

  updateArrows: function() {
    var that = this;
    var id = parseInt(that.ready);

    var index = $.inArray(id, that.activeList);

    if ( index == 0) {
      that.$el.find('.previousButton').hide();
    }
    else {
      that.$el.find('.previousButton').show();
    }

    if ( index  === that.activeList.length-1) {
      that.$el.find('.nextButton').hide();
    }
    else {
      that.$el.find('.nextButton').show();
    }
  },

  getActiveList: function() {
    var that = this;
    var ret = [];

    $( that.getVisibleResourceIds() ).each(function(i,e){

      if(
        that.parentExplorer.resourceMinimalList.get(e).get('mapMarker').visible != 'undefined' &&
        that.parentExplorer.resourceMinimalList.get(e).get('mapMarker').visible == true
      ) {
        ret.push(e);
      }
    });
    return ret;
    //return that.parentExplorer.displays.activeList.getVisibleResourceIds();
    //getVisibleResourceIds
  },


  getVisibleResourceIds: function() {
    var that = this;
    var ret = false;
    if(typeof that.parentExplorer.resourceIndex.removePagination != 'undefined'){
      that.parentExplorer.resourceIndex.removePagination();


      var visibleResources = that.parentExplorer.resourceIndex.setPerPage(100);

      if( that.options.sortByResourceWeightOnly != true ) {
        if( that.listOrderAccordingIds == true ) {
          visibleResources.setSort('weight','asc');
          //visibleResources.setSort('id','asc');
        }
        else {
          visibleResources.setSort('weight','asc');
          visibleResources.setSort('distanceToCenterKm', 'asc');
        }
      }

      // get total packages
      that.options.totalPages = that.parentExplorer.resourceIndex.getNumPages();

      // set current page
      visibleResources.setPage(that.currentPage);

      that.visibleResources = visibleResources.pluck( 'id' );

      ret = visibleResources.pluck( 'id' );
    }


    return ret;

  }

});
