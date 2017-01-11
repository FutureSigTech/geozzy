function twoLinesIntersection() {
  var that = this;
  // line-segments-intersect.js
  // intersection point https://en.wikipedia.org/wiki/Line%E2%80%93line_intersection
  // line 1: x1,y1,x2,y2
  // line 2: x3,y3,x4,y4
  // for comparing the float number, that.fixing the number to int to required
  // precision
  that.linesIntersect = function(seg1, seg2, precision) {
    var x1 = seg1[0][0],
      y1 = seg1[0][1],
      x2 = seg1[1][0],
      y2 = seg1[1][1],
      x3 = seg2[0][0],
      y3 = seg2[0][1],
      x4 = seg2[1][0],
      y4 = seg2[1][1],
      intPt,x,y,result = false,
      p = precision || 6,
      denominator = (x1 - x2)*(y3 - y4) - (y1 -y2)*(x3 - x4);
    if (denominator == 0) {
      // check both segments are Coincident, we already know
      // that these two are parallel
      if (that.fix((y3 - y1)*(x2 - x1),p) == that.fix((y2 -y1)*(x3 - x1),p)) {
        // second segment any end point lies on first segment
        result = that.intPtOnSegment(x3,y3,x1,y1,x2,y2,p) ||
          that.intPtOnSegment(x4,y4,x1,y1,x2,y2,p);
      }
    } else {
      x = ((x1*y2 - y1*x2)*(x3 - x4) - (x1 - x2)*(x3*y4 - y3*x4))/denominator;
      y = ((x1*y2 - y1*x2)*(y3 - y4) - (y1 - y2)*(x3*y4 - y3*x4))/denominator;
      // check int point (x,y) lies on both segment
      result = that.intPtOnSegment(x,y,x1,y1,x2,y2,p)
        && that.intPtOnSegment(x,y,x3,y3,x4,y4,p);
    }
    return result;
  };

  that.intPtOnSegment = function(x,y,x1,y1,x2,y2,p) {
    return that.fix(Math.min(x1,x2),p) <= that.fix(x,p) && that.fix(x,p) <= that.fix(Math.max(x1,x2),p)
      && that.fix(Math.min(y1,y2),p) <= that.fix(y,p) && that.fix(y,p) <= that.fix(Math.max(y1,y2),p);
  };

  // that.fix to the precision
  that.fix = function(n,p) {
    return parseInt(n * Math.pow(10,p));
  };

}


var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.mapView = Backbone.View.extend({

  displayType: 'map',
  parentExplorer: false ,
  map: false ,
  projection: false,
  ready:false,

  markersCreated: false,
  markerClusterer: false,

  outerPanToIntervalometer: false,
  outerPanToIntervalometerValue: false,

//  markerClustererHover: false,

  lastCenter: false,


  initialize: function( options ) {
    var that = this;
    var opts = new Object({
      map : false,
      clusterize: false,
      clustererStyles: false,
      clustererMaxZoom: 15,
      clustererGridSize: 90,
      clustererZoomOnClick: true,

      chooseMarkerIcon: function() {return false},
      mapZones: {
        outerMargin: {
          left:200,
          top:100,
          right:200,
          bottom:100
        },
        innerMargin:{
          left:400,
          top:100 ,
          right:60,
          bottom:100
        },
      }
    });


    that.options = $.extend(true, {}, opts, options );

    this.setMap( this.options.map );
  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;
  },

  setMap: function( mapObj ) {
    this.map = mapObj;
    this.setMapEvents();
  },

  setMapEvents: function() {
    var that = this;


    // drag event on map
    google.maps.event.addListener(this.map, "dragend", function() {
      that.ready = true;
      that.parentExplorer.render(true);
    });

    google.maps.event.addListener(this.map, "click", function() {
      if(that.parentExplorer.explorerTouchDevice) {
        //that.markerOut( 0 );
        that.parentExplorer.triggerEvent('resourceMouseOut',{id:0});
      }
    });

    // zoom event on map
    google.maps.event.addListener(this.map, "zoom_changed", function() {
      that.ready = true;
      that.parentExplorer.render(true);
      that.parentExplorer.triggerEvent('zoomChanged', {});
    });

    // map first load
    google.maps.event.addListener(this.map, "idle", function() {

      if( that.ready !== true) {
        that.ready = true;
        that.parentExplorer.render(true);
      }

    });

  },

  getVisibleResourceIds: function() {

    var that = this;
    // AQUÍ hai que seleccionar os que están dentro dos bounds, non o paxinado

    var visibleResources = [];

    google.maps.event.trigger( that.map, "resize");

    that.parentExplorer.resourceMinimalList.each(function(m, index) {
      // Assign values 2:visible in map, 1:not visible in map but present in buffer zone, 0:not in map or buffer

      var markerPosition = that.aboutMarkerPosition( m.get('lat'), m.get('lng') );
      /*var markerPosition = {
        outerZone: false,
        inMap: 3,
        distanceToInnerMargin: false
      };*/

      //that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'mapOuterZone', markerPosition.outerZone );
      that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'mapVisible', markerPosition.inMap  );
      that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'mapDistanceToInnerMargin', markerPosition.distanceToInnerMargin  );
      //m.set( 'mapVisible', that.coordsInMap( m.get('lat'), m.get('lng') ) );
    });

    //console.log(visibleResources.length)

    return visibleResources;
  },




  render: function() {

    var that = this;





    if( that.options.clusterize !== false ) {
      that.renderWithCluster();

    }
    else {
      that.renderWithoutCluster();

    }


    if( that.parentExplorer.options.debug ) {
      that.parentExplorer.timeDebugerMain.log( '&nbsp;- Pintado Mapa '+that.parentExplorer.resourceIndex.length+ 'recursos' );
    }
  },

  createAllMarkers: function() {
    var that = this;

    if( !that.markersCreated ) {
      that.parentExplorer.resourceMinimalList.each( function(e) {

//console.log(that.chooseMarker(e))
        var marker = e.mapMarker = new google.maps.Marker({
                  position: new google.maps.LatLng( e.get('lat'), e.get('lng') ),
                  map: that.map,
                  flat: true,
                  optimized: false,
                  visible:true
                });

        marker.setVisible(false);

        marker.explorerResourceId = e.get('id');

        marker.addListener('mousedown', function() {

          if(!that.parentExplorer.explorerTouchDevice){
            that.markerClick( e.get('id') );
          }
          else {
          //  that.markerClick( e.get('id') );
            that.markerHover( e.get('id') );
          }
        });
        marker.addListener('mouseover', function() {
          if(!that.parentExplorer.explorerTouchDevice){
            that.markerHover( e.get('id') );
          }

        });
        marker.addListener('mouseout', function() {
          if(!that.parentExplorer.explorerTouchDevice){
            that.markerOut( e.get('id') );
          }
        });

        that.parentExplorer.resourceMinimalList.get( e.get('id') ).set('mapMarker', marker );

      });
    }

    that.markersCreated = true;
  },


  hideAllMarkers: function() {
    var that = this;
    that.parentExplorer.resourceMinimalList.each( function(e) {
      e.mapMarker.setVisible(false);
    });
  },

  showAllMarkers: function() {
    var that = this;
    that.parentExplorer.resourceMinimalList.each( function(e) {
      e.mapMarker.setVisible(true);
    });
  },


  hide: function() {
    var that = this;

    that.hideAllMarkers();

    if( that.options.clusterize && that.markerClusterer != false  ) {
      that.markerClusterer.clearMarkers();
    }

  },

  renderWithoutCluster: function() {
    var that = this;

    if( !that.markersCreated ) {
      that.createAllMarkers();
    }
    that.hideAllMarkers();

    that.parentExplorer.resourceIndex.each( function(e) {
      e.mapMarker.setIcon( that.chooseMarker(e) );
      e.mapMarker.setVisible(true);
    });



  },

  renderWithCluster: function() {


    var that = this;


    that.markers = [];

    if( !that.markersCreated ) {
      that.createAllMarkers();
    }
    that.hideAllMarkers();

/*

    that.parentExplorer.resourceIndex.each( function(e) {
      e.mapMarker.setIcon( that.chooseMarker(e) );
      e.mapMarker.setVisible(true);
    });

*/



    that.parentExplorer.resourceIndex.each( function( e ) {
      e.mapMarker.setIcon( that.chooseMarker(e) );
      e.mapMarker.setVisible(true);
      that.markers.push( e.mapMarker );
    });


    if( that.markerClusterer == false ) {


      that.markerClusterer = new MarkerClusterer(this.map, that.markers, {
        maxZoom: that.options.clustererMaxZoom, // 15,
        gridSize: that.options.clustererGridSize, //90,
        zoomOnClick: that.options.clustererZoomOnClick, //true,
        styles: that.options.clustererStyles
      });

      var roseta = new geozzy.explorerComponents.clusterRoseView({mapView: that});



      that.markerClusterer.setMouseover(
        function( markers ) {
          roseta.show( markers );

        }
      );

      that.markerClusterer.setMouseout(
        function() {

        }
      );


    }
    else {
      this.markerClusterer.clearMarkers();
      this.markerClusterer.addMarkers( that.markers );
      this.markerClusterer.redraw();
    }

  },


/*
  coordsInMap: function( lat, lng ) {
    var that = this;

    var rt = that.aboutMarkerPosition(lat,lng);
    return rt.inMap;
  },*/

  aboutMarkerPosition: function( lat, lng) {
    var that = this;

    //google.maps.event.trigger( that.map, "resize");

    var markerPixel = that.coordToPixel( new google.maps.LatLng(lat, lng) );
    var mapcenterPixel = that.coordToPixel( that.map.getCenter());

    var ret = {
      inMap:0, // NOT IN MAP OR BUFFER
      //outerZone:false,
      distanceToInnerMargin: 0
    }


    var mb = that.getMapBounds();

    var sw = mb[0];
    var ne = mb[1];

    var scale = Math.pow(2, that.map.getZoom());




    var swOuter = new google.maps.Point(   that.coordToPixel(sw ).x- that.options.mapZones.outerMargin.right /scale,   that.coordToPixel(sw).y+ that.options.mapZones.outerMargin.top /scale );
    var neOuter = new google.maps.Point(   that.coordToPixel(ne ).x+ that.options.mapZones.outerMargin.left /scale ,   that.coordToPixel(ne).y- that.options.mapZones.outerMargin.bottom /scale );

    var swInner = new google.maps.Point(   that.coordToPixel(sw ).x+ that.options.mapZones.innerMargin.left /scale,   that.coordToPixel(sw).y- that.options.mapZones.innerMargin.bottom /scale );
    var neInner = new google.maps.Point(   that.coordToPixel(ne ).x- that.options.mapZones.innerMargin.right /scale ,   that.coordToPixel(ne).y+ that.options.mapZones.innerMargin.top /scale );



    var swO = that.map.getProjection().fromPointToLatLng( swOuter );
    var neO = that.map.getProjection().fromPointToLatLng( neOuter );
    var swI = that.map.getProjection().fromPointToLatLng( swInner );
    var neI = that.map.getProjection().fromPointToLatLng( neInner );

    if( lat < ne.lat() && lng < ne.lng() && lat > sw.lat() && lng > sw.lng() ) {

      if( lat < neI.lat() && lng < neI.lng() && lat > swI.lat() && lng > swI.lng() ) {
        ret.inMap = 3; // ********* IN CENTER OF MAP AREA **********
      }
      else{
        ret.inMap = 2; // ********* IN INNER MARGIN *******
      }
    }
    else if(lat < neO.lat() && lng < neO.lng() && lat > swO.lat() && lng > swO.lng() ) {
      ret.inMap = 1; // ********* IN OUTER MARGIN ********
    }
/*
    oNe = neI;
    oSw = swI;
*/

/*
    if( lat < swI.lat() ) {
      //ret.outerZone = 'S';
      //ret.distanceToInnerMargin = markerPixel.y - swInner.y;
      ret.distanceToInnerMargin = Math.sqrt( Math.pow( markerPixel.y - swInner.y, 2 )  + Math.pow( markerPixel.x - swInner.x, 2) );
    }
    else
    if( lat > neI.lat() ) {
      //ret.outerZone = 'N';
      //ret.distanceToInnerMargin = -(markerPixel.y -neInner.y);
      ret.distanceToInnerMargin = Math.sqrt( Math.pow( markerPixel.y - neInner.y, 2 )  + Math.pow( markerPixel.x - swInner.x, 2) );
    }
    else
    if( lng < swI.lng() ) {
      //ret.outerZone = 'W';
      //ret.distanceToInnerMargin = -(markerPixel.x -swInner.x);
      ret.distanceToInnerMargin = Math.sqrt( Math.pow( markerPixel.y - swInner.y, 2 )  + Math.pow( markerPixel.x - swInner.x, 2) );
    }
    else
    if( lng > neI.lng() ) {
      //ret.outerZone = 'E';
      //ret.distanceToInnerMargin = markerPixel.x - neInner.x;
      ret.distanceToInnerMargin = Math.sqrt( Math.pow( markerPixel.y - neInner.y, 2 )  + Math.pow( markerPixel.x - neInner.x, 2) );
    }
*/

    var centerToMarkerSegment = [ [ markerPixel.x, markerPixel .y ],[ mapcenterPixel.x, mapcenterPixel.y ] ];
    var TOPSegment = [[swInner.x, neInner.y], [neInner.x, neInner.y]];
    var RIGHTSegment = [[neInner.x, neInner.y], [neInner.x, swInner.y]];
    var BOTTOMSegment = [[swInner.x, swInner.y], [neInner.x, neInner.y]];
    var LEFTSegment = [[swInner.x, neInner.y], [swInner.x, swInner.y]];

    var intersectionPoint = [];
console.log(twoLinesIntersection);
    var lineUtils = new twoLinesIntersection()
    // TOP segment
    if( lineUtils.linesIntersect( centerToMarkerSegment, TOPSegment )){
      console.log('TOP');
    }
    else
    // RIGHT segment
    if( lineUtils.linesIntersect( centerToMarkerSegment, RIGHTSegment )){
      console.log('RIGHT');

    }
    else
    // BOTTOM segment
    if( lineUtils.linesIntersect( centerToMarkerSegment, BOTTOMSegment) ){
      console.log('BOTTOM');

    }
    else
    // LEFT segment
    if( lineUtils.linesIntersect( centerToMarkerSegment, LEFTSegment )){
      console.log('LEFT');

    }



    ret.distanceToInnerMargin = Math.sqrt( Math.pow( markerPixel.y - neInner.y, 2 )  + Math.pow( markerPixel.x - neInner.x, 2) );

    return ret;
  },

  getMapBoundsInArray: function() {
    var that = this;
    var bounds = that.getMapBounds();

    return [ [bounds[0].lat(),bounds[0].lng()], [bounds[1].lat(),bounds[1].lng()] ];
  },

  getMapBounds: function() {
    var that = this;

    return [ that.map.getBounds().getSouthWest(), that.map.getBounds().getNorthEast() ];
  },

  coordToPixel: function( latLng) {
    var that = this;
    return that.map.getProjection().fromLatLngToPoint( latLng );
  },

  pixelToCoord: function( x, y) {
    var that = this;

    return that.map.getProjection().fromPointToLatLng( new google.maps.Point( x ,y ) );
  },


  isReady: function() {
    return this.ready;
  },


  chooseMarker: function( e ) {
    var that = this;
    var retObj;

    var iconOptions = that.options.chooseMarkerIcon(e);
    if(iconOptions) {
      retObj = iconOptions;
    }
    else {
      retObj = {
        url: cogumelo.publicConf.media+'/module/admin/img/geozzy_marker.png',
        // This marker is 20 pixels wide by 36 pixels high.
        size: new google.maps.Size(30, 36),
        // The origin for this image is (0, 0).
        origin: new google.maps.Point(0, 0),
        // The anchor for this image is the base of the flagpole at (0, 36).
        anchor: new google.maps.Point(13, 36)
      };
    }

    return retObj;



  },

  markerBounce: function(id) {
    var that = this;

    //if(!that.parentExplorer.explorerTouchDevice) {
    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setOptions({
      title: 'selected'
    });
    //}

    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setMap( null );


    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setMap( that.map );


    //that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setAnimation(google.maps.Animation.BOUNCE);
    //setTimeout(function(){ that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setAnimation(null); }, 800);
  },

  markerBounceEnd: function(id) {
    var that = this;

    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setOptions({
      title: ''
    });
    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setMap( null );

    if( that.options.clusterize != false ) {

      $(that.markerClusterer.clusters_).each( function(i,e){
        if( e.isMarkerAlreadyAdded( that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker') ) == true ) {
          if(e.markers_.length == 1) {
            that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setMap( that.map );
          }
        }
      });
    }
    else {
      that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setMap( that.map );
    }

  },

  panTo: function( id, forcePan ) {
    var that = this;
    var mapVisible = that.parentExplorer.resourceMinimalList.get( id ).get('mapVisible');
    //var mapOuterZone = that.parentExplorer.resourceMinimalList.get( id ).get('mapOuterZone');
    var mapDistanceToInnerMargin = that.parentExplorer.resourceMinimalList.get( id ).get('mapDistanceToInnerMargin');
    var scale = Math.pow(2, that.map.getZoom());

    //console.log(mapVisible)
    if( mapVisible == 1 || mapVisible == 2  || forcePan == true ) {
      if( that.lastCenter == false ){
        that.lastCenter = that.map.getCenter();
      }

      //console.log(mapDistanceToInnerMargin, mapOuterZone);

      // PANTO
      var toMove = that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').getPosition() ;
      var P = that.coordToPixel( toMove )

      var fromMove = that.map.getCenter();
      var C = that.coordToPixel( fromMove );

      var pVx = P.x-C.x;
      var pVy = P.y-C.y;

      var Vx = Math.sin( Math.atan2( pVx , pVy  ) );
      var Vy = Math.cos( Math.atan2( pVx , pVy  ) );


      that.map.panTo( that.pixelToCoord( C.x + (Vx*mapDistanceToInnerMargin), C.y + (Vy*mapDistanceToInnerMargin) ) );

    }
    else {
      that.panToLastCenter();



      if( mapVisible == 0 ){
        that.outerPanTo( that.parentExplorer.resourceMinimalList.get( id ) )
      }
    }
  },

  outerPanTo: function( resource ) {
    var that = this;



    if( !$('div.explorerPositionArrows').length ) {
      $('<div class="explorerPositionArrows" style="display:none;">'+
          '<div class="pos N"> <div class="counter"></div> </div>'+
        '<div>').appendTo('body');
    }

    that.resetOuterPanTo();
    var outerPos = 'N';
    $('div.explorerPositionArrows div.pos' ).hide();
    $('div.explorerPositionArrows' ).show();
    $('div.explorerPositionArrows div.pos' ).show();



    that.outerPanToIntervalometerValue = 3;
    $('div.explorerPositionArrows div.pos' + ' div.counter' ).text(that.outerPanToIntervalometerValue);
    that.outerPanToIntervalometer = setInterval( function(){
      that.outerPanToIntervalometerValue--;
      $('div.explorerPositionArrows div.pos' +  ' div.counter' ).text(that.outerPanToIntervalometerValue);

      if( that.outerPanToIntervalometerValue == 0){
        that.resetOuterPanTo();
        that.panTo( resource.get('id'), true );
      }
      $('div.explorerPositionArrows div.pos'   ).fadeOut(300).fadeIn(300);
    }, 700);


    var highestZindex = -999;

    $("*").each(function() {
        var current = parseInt($(this).css("z-index"), 10);
        if(current && highestZindex < current) highestZindex = current+1;
    });

    $('div.explorerPositionArrows div.pos' ).css('position', 'absolute');
    $('div.explorerPositionArrows div.pos'  ).css('z-index', highestZindex);

    var arrowDivSize = {
        w: $('div.explorerPositionArrows div.pos'  ).width(),
        h: $('div.explorerPositionArrows div.pos' ).height()
      };

    var mapTopLeft = $(that.map.getDiv()).offset();
    var mapWidth = $(that.map.getDiv()).width();
    var mapHeight = $(that.map.getDiv()).height();
    var mapCenterWidth = mapWidth / 2 - arrowDivSize.w;
    var mapCenterHeight =  mapHeight / 2 - arrowDivSize.h;

    $('div.explorerPositionArrows').css({top: mapTopLeft.top , left: mapCenterWidth });

  },

  resetOuterPanTo: function() {
    that = this;

    if( that.outerPanToIntervalometer  ) {
      clearInterval( that.outerPanToIntervalometer )
      that.outerPanToIntervalometer = false;
    }

    $('div.explorerPositionArrows' ).hide();
  },

  panToLastCenter: function() {
    var that = this;

    that.resetOuterPanTo();

    if( that.lastCenter ) {
      that.map.panTo( that.lastCenter );
      that.lastCenter = false;
    }

  },

  markerClick: function( id ){

    var that = this;

    that.parentExplorer.triggerEvent('mapResourceClick', {
      id: id
    });

    that.parentExplorer.triggerEvent('resourceClick', {
      id: id,
      section: 'Explorer: '+that.parentExplorer.options.explorerSectionName
    });
  },

  markerHover: function( id ){
    var that = this;

    that.parentExplorer.triggerEvent('resourceHover', { id: id, section: 'Explorer: '+that.parentExplorer.options.explorerSectionName});

  },
  markerOut: function( id ) {
    var that = this;

    if( that.parentExplorer.displays.activeList ) {
      that.panToLastCenter();
    }

    that.parentExplorer.triggerEvent('resourceMouseOut', {id:id});

  }

});
