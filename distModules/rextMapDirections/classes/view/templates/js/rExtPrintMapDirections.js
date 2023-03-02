var geozzy = geozzy || {};

$(document).ready( function() {

  rextPrintMapDirectionsJs.initMap( geozzy.rextMapDirections.directions );

} );


var rextPrintMapDirectionsJs = {
  initMap: function( directions ) {
    var that = this;
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;

    var map = new google.maps.Map( document.getElementById( 'map' ) );
    var gtagInfo = { 'jsClass': 'rextPrintMapDirectionsJs' };
    gtag('event', 'bw_new_map', gtagInfo); // Registramos el instanciado del mapa en GA
    // that.usageRecord('newMap', 'gzRxPMapD'); // Registramos el instanciado del mapa en UER
    var uer = { ev: 'newMap', lb: 'gzRxPMapD', h: window.location.host, p: window.location.pathname };
    $.ajax({ type: 'get', url: 'https://uer.boldest.io/explorer', data: uer, cache: false });

    directionsDisplay.setMap( map );
    directionsDisplay.setPanel( document.getElementById( 'routeDirectionList' ) );

    that.calculateAndDisplayRoute( directionsService, directionsDisplay, directions );
    google.maps.event.addListenerOnce( map, 'tilesloaded', function() {
      setTimeout( function() { window.print(); }, 1000 );
    } );
  },

  calculateAndDisplayRoute: function( directionsService, directionsDisplay, directions ) {
    var that = this;
    directionsService.route( {
      origin: directions.origin,
      destination: directions.destination,
      travelMode: google.maps.TravelMode[ directions.travelMode ]
    }, function( response, status ) {
      if( status === 'OK' ) {
        directionsDisplay.setDirections( response );
      }
      else {
        window.alert( __('Directions request failed due to ') + status );
      }
    } );
  }
};
