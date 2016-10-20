var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryListView = Backbone.View.extend({
  displayType: 'list',
  parentStory: false,
  stepsDOM: false,
  stepsDOMEquivalences: false,
  currentDOMStep: false,
  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      container: false,
      topMargin: 100,
      bottomMargin:300,
      leftMargin:30,
      rightMargin:30,
      steepMarginsDifference: 160,

      //tpl: geozzy.storyComponents.listViewTemplate,
      tplElement: geozzy.storyComponents.listElementTemplate
    });

    that.options = $.extend(true, {}, options, opts);


    that.tplElement = _.template(that.options.tplElement);

    that.el = that.options.container;
    that.$el = $(that.el);

    $(window).on('scroll', function(){ that.updateVisibleStep()} );
    $(window).on('resize', function(){ that.caculatePositions()} );

  },

  setParentStory: function( obj ) {
    var that = this;

    that.parentStory = obj;
  },

  render: function() {
    var that = this;

    // Calculate distances
    if( that.options.steepMargins ) {

    }
    else
    if( typeof that.parentStory.displays.background != 'undefined') {

    }
    that.stepsDOMEquivalences = [];
    that.$el.html('');
    that.parentStory.storySteps.each( function( step , i ) {
      var d = step.toJSON();
      that.$el.append( that.tplElement( d ) );
      that.stepsDOMEquivalences.push( d.id );
    });

    that.stepsDOM= that.$el.find('.storyStep').toArray();
    that.caculatePositions();

    that.parentStory.bindEvent('storyReady', function() {
      that.parentStory.triggerEvent('stepChange', {id: that.stepsDOMEquivalences[0] , domElement: that.stepsDOM[0] });
    });

    that.parentStory.bindEvent( 'forceStep', function(obj){
        that.forceStep(obj.id);
    });

  },

  caculatePositions: function() {
    var that = this;

    $( that.stepsDOM ).each( function(i,e) {
      // if is first
      if( i === 0 ) {
        var topPosition = $(that.options.container).offset().top + 100;
      }
      else {
        var previousDiv = $(that.stepsDOM[ i - 1 ]);
        var previousHeight = parseInt( previousDiv.css('top'), 10 ) + parseInt( previousDiv.css('height'), 10 );
        var topPosition = previousHeight + that.getVisibleHeight() - that.options.steepMarginsDifference ;
      }

      $(e).css('top', topPosition);
      $(e).css('left', that.options.leftMargin);
    });

  },

  getVisibleHeight: function() {
    return parseInt( $(window).height(), 10);
  },

  updateVisibleStep: function() {


    //console.log(mathjs)
    //console.log( math.intersect( [0, 0], [10, 10], [10, 0], [0, 10]) );


    var that = this;

    var maxVisible = 0;
    var maxVisibleKey = false;

    $( that.stepsDOM ).each( function(i,e) {
      if( that.howMuchVisibleFromDiv(e) > maxVisible ) {
        maxVisible = that.howMuchVisibleFromDiv(e);
        maxVisibleKey = i;
      }
    });


    if( that.currentDOMStep != maxVisibleKey ) {
      that.currentDOMStep = maxVisibleKey;
      that.parentStory.triggerEvent('stepChange', {id: that.stepsDOMEquivalences[maxVisibleKey] , domElement: that.stepsDOM[maxVisibleKey] });
      //console.log('stepChange', {id: that.stepsDOMEquivalences[maxVisibleKey] , domElement: that.stepsDOM[maxVisibleKey] });
    }





  },

  howMuchVisibleFromDiv: function(elem){

    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    var visibleHeight = 0;

    // is fully visible
    if( (elemBottom <= docViewBottom) && (elemTop >= docViewTop) ) {
      visibleHeight = $(elem).height();
    }
    else
    // intersects with bottom of page
    if( (elemTop < docViewBottom) && (elemBottom > docViewBottom) ){
      visibleHeight = docViewBottom - elemTop ;
    }
    else
    // intersects top of page
    if( (elemTop < docViewTop) && (elemBottom > docViewTop) ){
      visibleHeight =  elemBottom - docViewTop;
    }

    return visibleHeight;
  },

  forceStep: function( step ){
    var that = this;
    var domElementId = false;
    $.each( that.stepsDOMEquivalences, function(i,e) {
      if( e == step){
        domElement = that.stepsDOM[i];
      }
    });

    if( domElementId != false) {
      consokle.log(domElement);
    }

  }

});
