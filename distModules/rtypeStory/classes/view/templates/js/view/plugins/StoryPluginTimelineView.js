var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};


geozzy.storyComponents.StoryPluginTimelineView = Backbone.View.extend({
  displayType: 'plugin',
  parentStory: false,
  tplElement: false,
  timeline :false,
  timelineIndex:[],
  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      container: false,
      //tplElement: '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-id%>/storyLegend/<%-id%>.png" />'
      //tplElement: '<div class==> LOL </div>'
    });
    that.options = $.extend(true, {}, options, opts);


  },

  setParentStory: function( obj ) {
    var that = this;
    that.parentStory = obj;
  },

  render: function() {
    var that = this;

    if( that.options.container !== false) {

      // Create a JSON data table

      // specify options
      var options = {
          /*'width':  '100%',
          'height': '300px',*/
          'editable': false,   // enable dragging and editing events
          'style': 'box',
          /*'locale':'es',*/
          'zoomable': false,
          'unselectable':false,
          'cluster':true
      };

      // Instantiate our timeline object.
      that.timeline = new links.Timeline( $(that.options.container)[0] , options);

  /*
      function onRangeChanged(properties) {
          document.getElementById('info').innerHTML += 'rangechanged ' +
                  properties.start + ' - ' + properties.end + '<br>';
      }
  */
      // attach an event listener using the links events handler
      links.events.addListener(that.timeline, 'select', function( propiedades ) {
        var selection = that.timeline.getSelection();

        console.log(selection[0].row);
        //console.log(that.timeline.getSelection()[0].row);
      });

      // Draw our timeline with the created data and options
      that.timeline.draw(that.getData());
    }

    that.parentStory.bindEvent('stepChange', function(obj){
      that.setStep(obj);
    });

  },

  setStep: function( step ) {
    var that = this;


    var showTimeline = that.parentStory.storySteps.get(step.id).get('showTimeline');

    if( showTimeline != null && showTimeline == 1 ) {
      //$(that.options.container).html(that.tplElement({id:legend}));
      /*console.log(that.timeline)
      console.log(that.timeline.getData());
      that.timeline.selectItem(0)*/
      //that.timeline.setSelection([{row: 0}])

      $.each( that.timelineIndex, function(i,e){
        if( e == step.id) {
          that.timeline.selectItem(i);
        }
      });

      //console.log(that.timeline.getSelection())
      $(that.options.container).fadeIn();
    }
    else {
      $(that.options.container).fadeOut();
    }



  },

  getData: function( ) {
    var that = this;

    var timeLineData = [];
    that.timelineIndex = [];

    that.parentStory.storySteps.each( function(e,i){

      if( e.get('initDate') != null ) {
        that.timelineIndex.push(e.get('id'));
        timeLineData.push({
          'start': new Date(e.get('initDate')),
          'end': new Date(e.get('endDate')),
          'content': e.get('title')
        });
      }
    });

    return timeLineData;
  }

});
