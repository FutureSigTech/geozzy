var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryBackgroundView = Backbone.View.extend({
  displayType: 'background',
  parentStory: false,
  blockSoftAnimation: false,

  scrollDirection: 1,
  scrollPosition:0,

  currentStepDOM:false,
  currentStepGeoLatLng: false,

  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      map: false,
      drawLine:true,
      lineColor: '#ffffff',
      lineWidth: 2,
      lineDotRadious: 10,
      moveToStep:true
    });

    that.options = $.extend(true, {}, options, opts);

    $(window).on('scroll', function(){
      that.softAnimation( $(this) );
    } );


  },

  setParentStory: function( obj ) {
    var that = this;

    that.parentStory = obj;
  },

  render: function() {
    var that = this;

    that.parentStory.bindEvent('stepChange', function(obj){
      that.setStep(obj);
    });
    that.setCanvasLayer();
  },

  setStep: function( obj ) {
    var that = this;



    var step = that.parentStory.storySteps.get( obj.id );

    var loc = false;

    if( that.options.moveToStep === true && step.get('lat') && typeof step.get('lng') ) {

      that.currentStepGeoLatLng = { lat: step.get('lat'), lng: step.get('lng') };

      that.blockSoftAnimation = true;

      if( that.options.map.getZoom() > step.get('defaultZoom') ) {
        that.options.map.setZoom(step.get('defaultZoom'));
        window.setTimeout(function(){
          that.options.map.panTo( new google.maps.LatLng( that.currentStepGeoLatLng.lat, that.currentStepGeoLatLng.lng ) );
          that.blockSoftAnimation = false;
        }, 400);
      }
      else {
        that.options.map.panTo( new google.maps.LatLng( that.currentStepGeoLatLng.lat, that.currentStepGeoLatLng.lng ) );
        window.setTimeout(function(){
          that.options.map.setZoom(step.get('defaultZoom'));
          that.blockSoftAnimation = false;
        }, 400);
      }


    }
  },


  setScrollDirection: function( scroll ) {
    var that = this;

    if( scroll.scrollTop() > that.scrollPosition ) {
      that.scrollDirection = 1;
    }
    else {
      that.scrollDirection = -1;
    }

  },

  softAnimation: function( scroll ) {

    var that = this;

    that.setScrollDirection(scroll);

    if( that.blockSoftAnimation === false ) {
      that.scrollPosition = scroll.scrollTop();
      that.options.map.panBy( 0 ,  1 * that.scrollDirection );
    }

  },



  setCanvasLayer: function() {
    var that = this;
    // initialize the canvasLayer
    var canvasLayerOptions = {
      map: that.options.map,
      resizeHandler: function(){ that.resizeCanvasLayer() },
      animate: false,
      updateHandler: function(){ that.updateCanvasLayer(); },
      resolutionScale: function(){ return window.devicePixelRatio || 1; }
    };

    that.canvasLayer = new CanvasLayer(canvasLayerOptions);
    that.layerContext = that.canvasLayer.canvas.getContext('2d');
  },

  resizeCanvasLayer: function() {

  },

  updateCanvasLayer: function() {
    var that = this;

    // clear previous canvas contents
    var canvasWidth = that.canvasLayer.canvas.width;
    var canvasHeight = that.canvasLayer.canvas.height;
    that.layerContext.clearRect(0, 0, canvasWidth, canvasHeight);

    var mapProjection = that.options.map.getProjection();

    that.layerContext.setTransform(1, 0, 0, 1, 0, 0);
    var scale = Math.pow(2, that.options.map.zoom) * window.devicePixelRatio || 1;
    that.layerContext.scale(scale, scale);
    var offset = mapProjection.fromLatLngToPoint(that.canvasLayer.getTopLeft());
    that.layerContext.translate(-offset.x, -offset.y);

    if( that.currentStepGeoLatLng != false ) {
      var rectLatLng = new google.maps.LatLng( that.currentStepGeoLatLng.lat, that.currentStepGeoLatLng.lng);

      var originPoint = mapProjection.fromLatLngToPoint(rectLatLng);
      //that.layerContext.fillRect(worldPoint.x, worldPoint.y, 1, 1);

      var destPoint = mapProjection.fromLatLngToPoint(
        new google.maps.LatLng(
          that.options.map.getBounds().getNorthEast().lat(),
          that.options.map.getBounds().getSouthWest().lng()
        )
      );




      // line
      that.layerContext.moveTo( originPoint.x, originPoint.y);
      that.layerContext.strokeStyle = that.options.lineColor;
      that.layerContext.lineWidth = that.options.lineWidth / scale;
      that.layerContext.lineTo( destPoint.x, destPoint.y );
      that.layerContext.stroke();
      that.layerContext.beginPath();

      // circle
      that.layerContext.fillStyle = that.options.lineColor;
      that.layerContext.arc( originPoint.x, originPoint.y, that.options.lineDotRadious/scale ,0,  2*Math.PI);
      that.layerContext.fill();
      that.layerContext.stroke();
      that.layerContext.beginPath();
    }

  }


});
