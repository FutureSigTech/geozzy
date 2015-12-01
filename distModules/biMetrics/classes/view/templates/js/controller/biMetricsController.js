var geozzy = geozzy || {};
if(!geozzy.biMetrics) geozzy.biMetrics={};
if(!geozzy.biMetrics.conroller) geozzy.biMetrics.controller={};

geozzy.biMetrics.controller.biMetricsController = _.extend({


  options: false;
  pendingMetrics: [],
  syncInterval: false,



  packageTemplate: function( ) {

    var that = this;

    return {
       "user_ID":283,
       "language":"es_ES",
       "session_ID":"0Eca798C0EfD46CA3de2827B8ed6DA",
       "observationTime":"Mon Nov 16 2015 11:33:46 GMT+0200 (Hora de verano romance)",
       "device":{
          "type":"mob",
          "device_ID":0
       }
       "metrics": that.pendingMetrics

    };
  },

  metricTemplate: function() {
    console.log('biMetrics: template Metric must be declared in "metricTemplate" Method');

    return false;
  },


  defaults: function( options ) {

    alert('CONSTRUCTOR');
/*
    var opts = {
      url: false,
      syncPeriod: 4000 // in miliseconds
    }

    $.extend(true, this.options, opts);
*/
  },








  addMetric: function( data ) {
    var that = this;

    if( var metric = that.metricTemplate( data ) ) {
      that.pendingMetrics.push(metric);
    }
  },

  reset: function() {
    var that = this;

    this.pendingMetrics = [];
  },

  sync: function() {
    var that = this;

    $.ajax({
      type: "POST",
      url: that.options.url,
      data: that.metricTemplate,
      success: function( res ) {
        console.log(res);
      },
      dataType: 'application/json'
    });

    that.reset();



  },

  syncDisable: function() {
    var that = this;

    if( that.syncInterval != false ) {
      clearInterval(  that.syncInterval );
    }
    else {
      console.log('biMetrics: that.syncInterval already defined');
    }

  },

  syncEnable: function() {
    var that = this;

    if( that.syncInterval == false ) {
      setInterval( function(){
        // syncrhonization
        that.sync();
        console.log('biMetrics: that.syncInterval already defined');

      }, that.options.syncPeriod);
    }
    else {
      console.log('biMetrics: that.syncInterval already defined');
    }


  },

  getTimesTamp: function() {
    var that = this;

    var data = new Date();

    return Date.UTC(
      data.getUTCFullYear(),
      data.getUTCMonth(),
      data.getUTCDate() ,
      data.getUTCHours(),
      data.getUTCMinutes(),
      data.getUTCSeconds(),
      data.getUTCMilliseconds()
    )

  }

});
