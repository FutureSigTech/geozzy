

  var poisResourceExplorerInfowindow = ''+
  // '<div class="poiInfoWindow" <% if( isNormalResource == 1 ) { %> onclick="window.open(\'/'+ cogumelo.publicConf.C_LANG +'/resource/<%- id %>\')"<%}%> >'+
  '<div class="poiInfoWindow" <% if( isNormalResource == 1 ) { %> onclick="window.open(\'/'+ cogumelo.publicConf.C_LANG +'<%- urlAlias %>\')"<%}%> >'+
    '<div class="poiImg">'+
      '<img class="img-fluid" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-image%>-a<%-imageAKey%>/squareCut/image.jpg" />'+
    '</div>'+
    '<div class="poiInfo">'+
      '<div class="poiTitle"><p><%-title%></p></div>'+
      '<div class="poiDescription"><%=description%></div>'+
      //'<% if( isNormalResource == 1 ) { %> <a target="_blank" href="/resource/<%-id%>" ><button class="btn btn-primary accessButton">' + __('Discover') + '</button> </a><% }%>'
    '</div>'+
  '</div>';


  /*
  * POIS resource explorer
  */
  var poisResourceExplorer = function(){
    var that = this;
    that.explorerId = 'pois';
    that.categorias = false;


    that.setExplorer =function(categorias) {
      that.categorias = categorias;

      /* EXPLORER MAIN CLASS DECLARATION */
      that.ex = new geozzy.explorer({
        explorerId: that.explorerId,
        explorerSectionName:'Puntos de interese',
        debug:false,
        aditionalParameters:geozzy.rExtPOIOptions, // aquí cárgase ResourceID
        resetLocalStorage: true
      });


      that.ex.bindEvent('mapResourceClick', function(o) {
        //alert(o.id);
        //cogumelo.log(that.ex.resourceMinimalList.get(o.id).toJSON());


        if( that.ex.resourceMinimalList.get(o.id).get('isNormalResource') == 1 ) {
          that.resourceAccess(o.id);
        }

        //cogumelo.log(o.id, that.ex.resourceMinimalList.get(o.id) );
      });



      /* EXPLORER DISPLAY DECLARATION  (SET CUSTOM ICON TOO)  */
      that.explorerMapa = new geozzy.explorerComponents.mapView({
          map: geozzy.rExtMapInstance.resourceMap,
          clusterize:false,
          chooseMarkerIcon: function( markerData ) {
            //return cogumelo.publicConf.media+'/module/rextPoiCollection/img/poi.png';


            var retMarker = {
              url: cogumelo.publicConf.media+'/module/rextPoiCollection/img/chapaPOIS.png',
              // This marker is 20 pixels wide by 36 pixels high.
              size: new google.maps.Size(20, 20),
              // The origin for this image is (0, 0).
              origin: new google.maps.Point(0, 0),
              // The anchor for this image is the base of the flagpole at (0, 36).
              anchor: new google.maps.Point(10, 10)
            };


            var resTerms = markerData.get('terms');
            var resRtype = markerData.get('rType');

            if(  that.categorias.hasOwnProperty(resRtype) ) {

              eval('var taxgroup = that.categorias.' + resRtype );
              taxgroup.each( function(e){
                if( $.inArray(e.get('id'), resTerms ) > -1 ) {
                  if( jQuery.isNumeric( e.get('icon') )  ){
                    retMarker.url = cogumelo.publicConf.mediaHost+'cgmlImg/'+e.get('icon')+'-a'+e.get('iconAKey')+'/resourcePoisCollection/marker.png';
                    //retMarker.url = cogumelo.publicConf.mediaHost+'cgmlImg/'+e.get('icon')+'/resourcePoisCollection/marker.png';
                    retMarker.size =  new google.maps.Size(20, 20);
                    return false;
                  }
                }
              });

            }



            return retMarker;
          }
      });

      if( geozzy.rExtPOIOptions.showPanorama != false) {
        var panoramaView = new geozzy.explorerComponents.panoramaView({
          panoramaImage: geozzy.rExtPOIOptions.panoramaImg
        });
        that.ex.addDisplay( panoramaView );
      }


      /* ADD DISPLAY TO EXPLORER */

      that.ex.addDisplay( that.explorerMapa );

      var infowindow = new geozzy.explorerComponents.mapInfoBubbleView({
        tpl:poisResourceExplorerInfowindow,
        width: 350,
        max_height:170,
        marker_distance: [20,20]
      });
      that.ex.addDisplay( infowindow );
      that.ex.exec();
    };


    that.resourceAccess = function(resourceId) {
      var win = window.open( '/'+cogumelo.publicConf.C_LANG+that.ex.resourceMinimalList.get(resourceId).get('urlAlias'), '_self' );
      win.focus();
    }

  }


  var poisResourceExplorerInstance = new poisResourceExplorer();
  $(document).ready(function(){
    //cogumelo.log('LOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO' , geozzy.collection.CategorytermCollection );
    var categorias = {};
    var multifetchStr = 'categorias.rtypePoi.fetch()';

    categorias.rtypePoi = new geozzy.collection.CategorytermCollection();
    categorias.rtypePoi.setUrlByIdName('rextPoiType');


    if( typeof cogumelo.publicConf.rtypeTaxonomygroup != 'undefined') {

      $( Object.keys(cogumelo.publicConf.rtypeTaxonomygroup) ).each( function(i, key){
        eval( 'var e = cogumelo.publicConf.rtypeTaxonomygroup.'+ key);

        eval( 'categorias.' + key + ' = new geozzy.collection.CategorytermCollection();'  );
        eval( 'categorias.' + key + '.setUrlByIdName("'+e+'");'  );
        multifetchStr += ', '+ 'categorias.' + key + '.fetch()';
      });
    }

    geozzy.rExtMapInstance.onLoad(function(){
      eval('$.when( '+ multifetchStr+' ).done(function() {  poisResourceExplorerInstance.setExplorer(categorias) });');
    });

  });
