var geozzy = geozzy || {};

/*
  HTML example to add a form map

*/

geozzy.rExtMapWidgetForm = function( segmentDIV ) {
  var that = this;
  var resourceLocation = __('Resource location');

  that.segmentDIV = segmentDIV;

  that.latInput = that.segmentDIV.find(".lat input");
  that.lonInput = that.segmentDIV.find(".lon input");
  that.defaultZoom = that.segmentDIV.find(".zoom input");
  that.mapContainer = that.segmentDIV.find(".mapContainer");
  that.addressInput = that.segmentDIV.find(".address");
  that.resourceMap = false;
  that.resourceMarker = false;
  that.blockMarker = false;
  that.mylocationMarker = false;

  that.initializeMap = function( ){

    // Location Map
    if(  that.segmentDIV.find(".lat input").length &&  that.segmentDIV.find(".lon input").length  ) {


      if( that.mapContainer.length && that.mapContainer.children('.resourceLocationMap').length < 1 ) {
        that.mapContainer.append('<div class="resourceLocationMap"></div>');

        var latValue = 0;
        var lonValue = 0;
        var zoomInit = 3;

        if ($('#createPoiModal').length > 0) {
          var latInit = Number($('.location .cgmMForm-field-locLat input').val());
          var lonInit = Number($('.location .cgmMForm-field-locLon input').val());
          zoomInit = Number($('.location .cgmMForm-field-defaultZoom input').val());
          var defaultMarker = cogumelo.publicConf.admin.adminMap.marker;
        }
        else{
          if (typeof cogumelo.publicConf.admin.adminMap === 'undefined') {
            cogumelo.log('cogumelo.publicConf.admin.adminMap is not defined in conf');
          }
          else {
            var latInit = Number(cogumelo.publicConf.admin.adminMap.defaultLat);
            var lonInit = Number(cogumelo.publicConf.admin.adminMap.defaultLon);
            zoomInit = Number(cogumelo.publicConf.admin.adminMap.defaultZoom);
            var defaultMarker = cogumelo.publicConf.admin.adminMap.marker;
          }
        }

        if( that.latInput.val() !== '' && that.latInput.val() !== '') {
          latValue = parseFloat( that.latInput.val() );
          lonValue = parseFloat( that.lonInput.val() );
          latInit = latValue;
          lonInit = lonValue;
        }

        if( that.defaultZoom.length > 0 &&  that.defaultZoom.val() != '') {
          zoomInit = parseInt( that.defaultZoom.val() );
        }


        // gmaps init
        var mapOptions = {
          center: { lat: latInit, lng: lonInit },
          zoom: zoomInit,
          scrollwheel: false
        };
        that.resourceMap = new google.maps.Map( that.segmentDIV.find('.resourceLocationMap')[0], mapOptions);

        // add marker

        var my_marker = {
          url: defaultMarker,
          // This marker is 20 pixels wide by 36 pixels high.
          size: new google.maps.Size(30, 36),
          // The origin for this image is (0, 0).
          origin: new google.maps.Point(0, 0),
          // The anchor for this image is the base of the flagpole at (0, 36).
          anchor: new google.maps.Point(13, 36)
        };

        that.resourceMarker = new google.maps.Marker({
          position: new google.maps.LatLng( latValue, lonValue ),
          title: resourceLocation,
          icon: my_marker,
          draggable: true
        });

        // Draggend event
        google.maps.event.addListener( that.resourceMarker,'dragend',function(e) {
          that.lonInput.val( that.resourceMarker.position.lng() );
          that.latInput.val( that.resourceMarker.position.lat() );
        });

        // Click map event
        google.maps.event.addListener(that.resourceMap, 'click', function(e) {
          if( that.blockMarker != true ) {
            that.resourceMarker.setPosition( e.latLng );
            that.resourceMarker.setMap( that.resourceMap );
            that.latInput.val( that.resourceMarker.position.lat() );
            that.lonInput.val( that.resourceMarker.position.lng() );
            that.defaultZoom.val( that.resourceMap.getZoom() );
          }
        });

        // map zoom changed
        google.maps.event.addListener(that.resourceMap, 'zoom_changed', function(e) {
          that.defaultZoom.val( that.resourceMap.getZoom() );
        });

        if( that.latInput.val() !== '') {
          that.resourceMarker.setMap( that.resourceMap);
        }
      }
    }

    $('.locationData .automaticBtn').click(function(){
      that.browserGeoLoc();
    });
  };


  that.placeSearch = false;
  that.autocomplete = false;
  that.componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
  };


  that.fillInAddress = function() {
    // Get the place details from the autocomplete object.
    var place = that.autocomplete.getPlace();
    cogumelo.log(place.geometry.location.lat(), place.geometry.location.lng() );

    var pos = new google.maps.LatLng(
      place.geometry.location.lat(),
      place.geometry.location.lng()
    );

    that.resourceMap.setCenter( pos );

    that.resourceMarker.setPosition( pos );
    that.resourceMarker.setMap( that.resourceMap );
    that.latInput.val( that.resourceMarker.position.lat() );
    that.lonInput.val( that.resourceMarker.position.lng() );
    that.resourceMap.setZoom(11);
  };

  // Bias the autocomplete object to the user's geographical location,
  // as supplied by the browser's 'navigator.geolocation' object.
  that.geolocate = function() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var geolocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        var circle = new google.maps.Circle({
          center: geolocation,
          radius: position.coords.accuracy
        });
        that.autocomplete.setBounds(circle.getBounds());
      });
    }
  };

  that.initAddressAutocompletion = function() {
    if( that.addressInput.length > 0 ) {
      // Create the autocomplete object, restricting the search to geographical
      // location types.

      that.autocomplete = new google.maps.places.Autocomplete(
          ( that.addressInput[0] ),
          {types: ['geocode','establishment']});

      // When the user selects an address from the dropdown, populate the address
      // fields in the form.
      that.autocomplete.addListener('place_changed', that.fillInAddress);

      that.addressInput.focus( function() {
        that.geolocate();
      });
    }
  };


  that.initializeMap();
  that.initAddressAutocompletion();

  that.browserGeoLoc = function() {
    var geoOptions = {
      timeout: 10 * 1000, // mSeg
      enableHighAccuracy: false,
      maximumAge: 2 * 60 * 1000 // mSeg
    }

    var geoSuccess = function(position) {
      console.log('geoSuccess',position);
      lat = Math.round(position.coords.latitude*10000)/10000;
      lon = Math.round(position.coords.longitude*10000)/10000;
      var mylocation = new google.maps.LatLng(
        parseFloat(lat),
        parseFloat(lon)
      );

      if(that.mylocationMarker===false){
        that.mylocationMarker = new google.maps.Marker({
          //position: new google.maps.LatLng( latValue, lonValue ),
          icon: {
            url: cogumelo.publicConf.rextMapConf.defaultMarker.marker,
            // This marker is 20 pixels wide by 36 pixels high.
            size: new google.maps.Size(30, 36),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 36).
            anchor: new google.maps.Point(13, 36)
          },
          draggable: true
        });
      }

      that.mylocationMarker.setPosition( mylocation );
      that.resourceMap.setCenter( mylocation );
      that.mylocationMarker.setMap(that.resourceMap);
      that.latInput.val( lat );
      that.lonInput.val( lon );
      that.resourceMap.setZoom(that.resourceMap.getZoom());
    };
    var geoError = function(error) {
      switch(cogumelo.publicConf.C_LANG){
        case 'es':
          var noLoc = 'Ha sido imposible obtener la geolocalización';
          break;
        case 'gl':
          var noLoc = 'Foi imposible obter a xeolocalización';
          break;
        case 'en':
          var noLoc = 'It has been impossible to get your geolocation';
          break;
      }
      alert(noLoc);
    };

    if( navigator.geolocation ) {
      // This feature is available only in secure contexts (HTTPS), in some or all browsers.
      navigator.geolocation.getCurrentPosition( geoSuccess, geoError, geoOptions );
    }
    else {
      geoError(-1);
    }
  }

};
