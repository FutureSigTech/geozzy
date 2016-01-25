var capaFussion = false;

mapControlsUtils = function(){
  var that = this;

  that.controlMapSat = '';
  that.controlMapRel = '';
  that.controlMapMap = '';

  that.changeMapControls = function (mapa) {
    var controlsMapsContainer = document.createElement('div');
    // Set CSS for the control border.
    that.controlMapSat = document.createElement('div');
    $(that.controlMapSat).addClass('controlMapSat controlMapCustom');
    //controlMapSat.title = 'SAT';
    controlsMapsContainer.appendChild(that.controlMapSat);

    that.controlMapMap = document.createElement('div');
    $(that.controlMapMap).addClass('controlMapMap controlMapCustom');
    controlsMapsContainer.appendChild(that.controlMapMap);


    that.controlMapRel = document.createElement('div');
    $(that.controlMapRel).addClass('controlMapRel controlMapCustom');
    controlsMapsContainer.appendChild(that.controlMapRel);

    //
    that.controlMapSat.addEventListener('click', function() {
      mapa.setMapTypeId(google.maps.MapTypeId.SATELLITE);
      that.controlMapShow('satellite');
    });

    that.controlMapMap.addEventListener('click', function() {
      mapa.setMapTypeId(google.maps.MapTypeId.ROADMAP);
      that.controlMapShow('roadmap');
    });

    that.controlMapRel.addEventListener('click', function() {
      mapa.setMapTypeId(google.maps.MapTypeId.TERRAIN);
      that.controlMapShow('terrain');
    });

    mapa.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(controlsMapsContainer);
    that.controlMapShow(mapa.getMapTypeId());
  }

  that.controlMapShow = function(activeClass){

    $('.controlMapCustom').removeClass('active');
    if( activeClass === 'satellite' ){
      $(that.controlMapSat).addClass('active');
    }
    if( activeClass === 'roadmap' ){
      $(that.controlMapMap).addClass('active');
    }
    if( activeClass === 'terrain' ){
      $(that.controlMapRel).addClass('active');
    }
  }

}

function chooseFTLayer( val, zonaCategories, mapa, mapOptions ) {
  var capa = false;


  if(val != '*') {
    capa =  zonaCategories.get( val ).get('idName');
  }


  if( capaFussion) {
    capaFussion.setMap(null);
  }

  capaFussion = new google.maps.FusionTablesLayer({
    query: {
      select: 'geometry',
      from: '1LpVhgltBt03Egd5pJR2oxvLinrsEVqRcG_eA1sV4',
      where: " id = '"+capa+"'"
    },
    styles: [{
        polygonOptions: {
        fillColor: '#000000',
        fillOpacity: 0.2
      }
     }]

  });

  if( capa ){
    capaFussion.setMap(mapa);
  }




  switch(capa) {

    case 'baixominoVigo':
       mapa.setZoom( 10 );
       mapa.setCenter( new google.maps.LatLng(42.20,-8.55) );
       break;

    case 'terrasDePontevedra':
      mapa.setZoom( 11 );
      mapa.setCenter( new google.maps.LatLng(42.45,-8.55) );
      break;

    case 'arousa':
       mapa.setZoom( 10 );
       mapa.setCenter( new google.maps.LatLng(42.60,-8.83) );
       break;

    case 'costaDaMorte':
      mapa.setZoom( 10 );
      mapa.setCenter( new google.maps.LatLng(43.15,-8.95) );
      break;


    case 'aMarinaLucense':
      mapa.setZoom( 11 );
      mapa.setCenter( new google.maps.LatLng(43.53,-7.35) );
      break;

    case 'ancaresCourel':
       mapa.setZoom( 10 );
       mapa.setCenter( new google.maps.LatLng(42.85,-7.10) );
       break;


    case 'manzanedaTrevinca':
      mapa.setZoom( 10 );
      mapa.setCenter( new google.maps.LatLng(42.35,-7.05) );
      break;

    case 'ribeiraSacra':
       mapa.setZoom( 10 );
       mapa.setCenter( new google.maps.LatLng(42.50,-7.55) );
       break;

    case 'verinViana':
      mapa.setZoom( 10 );
      mapa.setCenter( new google.maps.LatLng(42.05,-7.30) );
      break;


    case 'celanovaAlimia':
      mapa.setZoom( 11 );
      mapa.setCenter( new google.maps.LatLng(42.05,-7.90) );
      break;

    case 'terrasDeOurenseAllariz':
      mapa.setZoom( 11 );
      mapa.setCenter( new google.maps.LatLng(42.28,-7.80) );
      break;

    case 'oRibeiro':
      mapa.setZoom( 11 );
      mapa.setCenter( new google.maps.LatLng(42.410,-8.140) );
      break;

    case 'ancaresCourel':
       mapa.setZoom( 10 );
       mapa.setCenter( new google.maps.LatLng(42.410,-8.140) );
       break;

    case 'dezaTabeiros':
      mapa.setZoom( 11 );
      mapa.setCenter( new google.maps.LatLng(42.686,-8.215) );
      break;

    case 'lugoTerraCha':
       mapa.setZoom( 10 );
       mapa.setCenter( new google.maps.LatLng(43.10,-7.55) );
       break;

    case 'terrasDeSantiago':
      mapa.setZoom( 11 );
      mapa.setCenter( new google.maps.LatLng(42.96,-8.46) );
      break;

    case 'murosNoia':
      mapa.setZoom( 11 );
      mapa.setCenter( new google.maps.LatLng(42.79,-8.94) );
      break;

    case 'ferrolTerra':
      mapa.setZoom( 10 );
      mapa.setCenter( new google.maps.LatLng(43.525,-8.00) );
      break;

    case 'aCorunaAsMarinas':
      mapa.setZoom( 11 );
      mapa.setCenter( new google.maps.LatLng(43.29,-8.20) );
      break;

    default:

      mapa.setZoom( mapOptions.zoom );
      mapa.setCenter( new google.maps.LatLng( mapOptions.center.lat , mapOptions.center.lng ) );
      break;
  }




}