var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterComboView = geozzy.filterView.extend({



  isTaxonomyFilter: true,

  selectedTerms: false,

  initialize: function( opts ) {
    var that = this;

    var options = {
      title: false,
      elSummaryContainer:false,
      titleSummary: false,
      defaultOption: false,
      data: false,

      template: geozzy.explorerComponents.filterComboViewTemplate,
      templateOption: geozzy.explorerComponents.filterComboViewOptionT,
      templateSummary: geozzy.explorerComponents.filterComboViewSummaryT,
      onChange: function() {}
    };

    that.options = $.extend(true, {}, options, opts);


    that.template = _.template( that.options.template );
    that.templateOption = _.template( that.options.templateOption );
    that.templateSummary = _.template( that.options.templateSummary );
    that.$elSummaryContainer = $(that.options.elSummaryContainer);
  },

  filterAction: function( model ) {
    var ret = false;

    if( this.selectedTerms != false ) {

      var terms =  model.get('terms');
      if( typeof terms != "undefined") {
        var diff = $( terms ).not( this.selectedTerms );
        //cogumelo.log(diff.length, terms.length);
        ret = (diff.length != terms.length );
      }
    }
    else {
      ret = true;
    }

    return ret;
  },

  render: function() {
    var that = this;

    var filterOptions = '';

    $.each(that.options.data.toJSON(), function(i,e){
      filterOptions += that.templateOption(e);
    });

    var filterHtml = that.template( { title: that.options.title, defaultOption: that.options.defaultOption, options: filterOptions } );

    that.$el.html( filterHtml );


    that.$el.find( 'select').bind('change', function(el) {
      var val = $(el.target).val();
      if( val == '*' ) {
        that.selectedTerms = false;
      }
      else {
        //that.selectedTerms = false;
        that.selectedTerms = [ parseInt( $(el.target).val() ) ];
      }

      that.parentExplorer.applyFilters();
      that.options.onChange();

      // Filter summaries
      if(that.options.elSummaryContainer) {
        var selectedOption =  false;

        if(typeof that.selectedTerms[0] != 'undefined') {
          selectedOption = that.options.data.get( that.selectedTerms[0] ).toJSON();
        }

        that.renderSummary( selectedOption );
      }
      console.log('manual',that.selectedTerms)
    });




  },

  renderSummary: function( selectedOption ) {
    var that = this;

    if( selectedOption ) {
      var summaryHtml = that.templateSummary( { title: that.options.titleSummary, option: selectedOption  } );
      that.$elSummaryContainer.html( summaryHtml );
    }
    else {
      that.$elSummaryContainer.html( "" );
    }


  },

  reset: function() {
    //cogumelo.log('COMBO');
    var that = this;

    $select = that.$el.find('select');

    $select.val( "*" );

    that.selectedTerms = false;
    if(that.options.elSummaryContainer) {
      that.renderSummary( false );
    }

  },
  
  getSelectedTerms: function() {
    var that = this;
    return that.selectedTerms;
  },

  extenalSelectTerms: function( selectedTerm ) {
    var that = this;
    
    if( selectedTerm == false ) {
      that.$el.value = '*';
      that.selectedTerms = false;
    }
    else {
      that.$el.value = selectedTerm;
      that.selectedTerms = [parseInt(selectedTerm)];
    }

    console.log(that.selectedTerms)
    that.parentExplorer.applyFilters();
  }
});
