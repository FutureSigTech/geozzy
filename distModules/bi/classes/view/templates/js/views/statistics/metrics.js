define(["jquery","underscore","backbone","mustache","text!templates/statistics/metrics.html"],function(a,b,c,d,e){var f=c.View.extend({events:{"change #metrics":"changeMetric"},defaults:{selectedMetric:"",selectedFilterID:""},render:function(){var a=d.render(e,this.model.toJSON());return this.$el.html(a),this},changeMetric:function(c){var d=c.target.value,e=a("option:selected",a(c.target)).attr("filterID");b.isUndefined(d)||this.model.set("selectedMetric",d),this.model.set("selectedFilterID",e)}});return f});