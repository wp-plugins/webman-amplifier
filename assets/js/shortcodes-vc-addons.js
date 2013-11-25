/**
 * WebMan Visual Composer plugin additional scripts
 *
 * @package     WebMan Amplifier
 * @subpackage  Shortcodes
 *
 * @since       1.0
 */

(function(e){_.extend(vc.atts,{wm_radio:{parse:function(t){var n=[],r="";e("input[name="+t.param_name+"]",this.$content).each(function(t){var r=e(this);if(r.is(":checked")){n.push(r.attr("value"));r.parent(".input-item").addClass("active")}});if(n.length>0){r=n.join(",")}return r}}});window.VcCustomPricingTableView=vc.shortcode_view.extend({adding_new_tab:false,events:{"click .add_tab":"addTab","click > .controls .column_delete":"deleteShortcode","click > .controls .column_edit":"editElement","click > .controls .column_clone":"clone"},render:function(){window.VcCustomPricingTableView.__super__.render.call(this);this.$content.sortable({axis:"y",handle:".wpb_element_wrapper",stop:function(t,n){n.item.prev().triggerHandler("focusout");e(this).find("> .wpb_sortable").each(function(){var t=e(this).data("model");t.save({order:e(this).index()})})}});return this},addTab:function(e){this.adding_new_tab=true;var t=jQuery(e.currentTarget).data("item"),n=jQuery(e.currentTarget).data("item-title");e.preventDefault();vc.shortcodes.create({shortcode:t,params:{caption:n},parent_id:this.model.id})},_loadDefaults:function(){window.VcCustomPricingTableView.__super__._loadDefaults.call(this)}});window.VcCustomAccordionView=vc.shortcode_view.extend({adding_new_tab:false,events:{"click .add_tab":"addTab","click > .controls .column_delete":"deleteShortcode","click > .controls .column_edit":"editElement","click > .controls .column_clone":"clone"},render:function(){window.VcCustomAccordionView.__super__.render.call(this);this.$content.sortable({axis:"y",handle:"h3",stop:function(t,n){n.item.prev().triggerHandler("focusout");e(this).find("> .wpb_sortable").each(function(){var t=e(this).data("model");t.save({order:e(this).index()})})}});return this},changeShortcodeParams:function(e){window.VcCustomAccordionView.__super__.changeShortcodeParams.call(this,e);var t=_.isString(this.model.get("params").collapsible)&&this.model.get("params").collapsible==="yes"?true:false;if(this.$content.hasClass("ui-accordion")){this.$content.accordion("option","collapsible",t)}},changedContent:function(e){if(this.$content.hasClass("ui-accordion"))this.$content.accordion("destroy");var t=true;this.$content.accordion({header:"h3",navigation:false,autoHeight:true,heightStyle:"content",collapsible:t,active:this.adding_new_tab===false&&e.model.get("cloned")!==true?0:e.$el.index()});this.adding_new_tab=false},addTab:function(e){this.adding_new_tab=true;var t=jQuery(e.currentTarget).data("item"),n=jQuery(e.currentTarget).data("item-title");e.preventDefault();vc.shortcodes.create({shortcode:t,params:{title:n},parent_id:this.model.id})},_loadDefaults:function(){window.VcCustomAccordionView.__super__._loadDefaults.call(this)}});window.VcCustomAccordionTabView=window.VcColumnView.extend({events:{"click > [data-element_type] > .controls .column_delete":"deleteShortcode","click > [data-element_type] > .controls .column_add":"addElement","click > [data-element_type] > .controls .column_edit":"editElement","click > [data-element_type] > .controls .column_clone":"clone","click > [data-element_type] > .wpb_element_wrapper > .empty_container":"addToEmpty"},setContent:function(){this.$content=this.$el.find("> [data-element_type] > .wpb_element_wrapper > .vc_container_for_children")},changeShortcodeParams:function(e){var t=e.get("params");window.VcCustomAccordionTabView.__super__.changeShortcodeParams.call(this,e);if(_.isObject(t)&&_.isString(t.title)){this.$el.find("> h3 .tab-label").text(t.title)}},setEmpty:function(){e("> [data-element_type]",this.$el).addClass("empty_column");this.$content.addClass("empty_container")},unsetEmpty:function(){e("> [data-element_type]",this.$el).removeClass("empty_column");this.$content.removeClass("empty_container")}})})(window.jQuery)