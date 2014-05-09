/**
 * WebMan Metabox Generator scripts
 *
 * @package     WebMan Amplifier
 * @subpackage  Metabox
 *
 * @since       1.0
 */

jQuery(function(){function b(a,b){return b.replace(/(\[\d+\])/,function(a,b){return b=b.substr(1,b.length-2),"["+(Number(b)+1)+"]"})}function c(){jQuery("#wpb_visual_composer").is(":visible")?jQuery("body").addClass("wm-visual-composer-on"):jQuery("body").removeClass("wm-visual-composer-on")}if(jQuery(".no-js").removeClass("no-js"),jQuery().wpColorPicker){var a="undefined"==typeof wmColorPickerOptions?null:wmColorPickerOptions;jQuery(".wm-meta-wrap .color-wrap .fieldtype-text").wpColorPicker(a)}jQuery(".button-default-value").on("click",function(){var a=jQuery(this),b=a.data("option"),c=a.find("span").text();if(a.hasClass("default-gallery"))jQuery("#"+b).attr("value",c),jQuery(".gallery-"+b).html("");else if(a.hasClass("default-slider")&&jQuery().slider)jQuery("#"+b+"-slider").slider("option","value",c),jQuery("#"+b).attr("value",c);else{var d='.wm-meta-wrap [name="'+b+'"]';"radio"===jQuery(d).attr("type")?jQuery(d+'[value="'+c+'"]').attr("checked","checked"):jQuery(d).val(c).change()}}),wmFeaturedImage={get:function(){return wp.media.view.settings.post.featuredImageId},set:function(a){var b=wp.media.view.settings;b.post.featuredImageId=a,wp.media.post("set-post-thumbnail",{json:!0,post_id:b.post.id,thumbnail_id:b.post.featuredImageId,_wpnonce:b.post.nonce}).done(function(a){jQuery(".inside","#postimagediv").html(a)})},frame:function(){return this._frame?this._frame:(this._frame=wp.media({state:"featured-image",states:[new wp.media.controller.FeaturedImage]}),this._frame.on("toolbar:create:featured-image",function(a){this.createSelectToolbar(a,{text:wp.media.view.l10n.setFeaturedImage})},this._frame),this._frame.state("featured-image").on("select",this.select),this._frame)},select:function(){var a=wp.media.view.settings,b=this.get("selection").single();a.post.featuredImageId&&wmFeaturedImage.set(b?b.id:-1)},init:function(){jQuery(".button-set-featured-image").on("click",function(a){a.preventDefault(),a.stopPropagation(),wmFeaturedImage.frame().open()})}},wmFeaturedImage.init(),jQuery(".wm-meta-wrap .gallery-wrap").on("click",".button-set-gallery",function(a){if("undefined"!=typeof wp&&wp.media&&wp.media.gallery){var b=jQuery(this).data("id"),c="undefined"==typeof wmGalleryPreviewNonce?"":wmGalleryPreviewNonce,d=jQuery("#"+b),e=wp.media.gallery,f=e.edit('[gallery ids="'+d.val()+'"]'),g=f.el.getAttribute("class");f.el.setAttribute("class",g+" wmamp-gallery"),f.state("gallery-edit").on("update",function(a){var f=e.shortcode(a).attrs.named.ids,g=f.join(",");jQuery(".gallery-"+b).find("a").hide(),jQuery(".gallery-loading-"+b).show(),f.length&&jQuery.post(ajaxurl,{action:"wm-gallery-preview-refresh",fieldID:b,images:f,wmGalleryNonce:c},function(a){jQuery(".gallery-"+b).html(a),jQuery(".gallery-loading-"+b).hide()}),d.val(g)}),a.preventDefault()}}),jQuery(".wm-meta-wrap .image-wrap label, .wm-meta-wrap .button-set-image").on("click",function(a){if("undefined"!=typeof wp&&wp.media&&wp.media.editor){var b=jQuery(this).data("id"),c=wp.media.editor.send.attachment;wp.media.editor.send.attachment=function(a,d){jQuery('input[name="'+b+'[url]"]').val(d.url),jQuery('input[name="'+b+'[url]"]').attr("data-preview",d.sizes.thumbnail.url),jQuery('input[name="'+b+'[id]"]').val(d.id),jQuery("div.image-"+b).removeClass("hide").find("img").attr("src",d.sizes.thumbnail.url),wp.media.editor.send.attachment=c},wp.media.editor.open(),a.preventDefault()}}),jQuery(".wm-meta-wrap .preview-enabled .fieldtype-image").on("change",function(){var a=jQuery(this),b=a.val(),c=a.attr("id"),d=a.data("nothumb");b?(b=a.data("preview")?a.data("preview"):a.val(),jQuery("div.image-"+c).find("img").attr("src",b)):(a.data("preview",""),jQuery("div.image-"+c).find("img").attr("src",d))}),jQuery(".wm-meta-wrap .fieldtype-image").change(),jQuery(".wm-meta-wrap .confirm").on("click",function(a){var b=jQuery(this).attr("href"),c=jQuery(".wm-meta-wrap .modal-box").fadeIn();c.find("a").on("click",function(){var a=jQuery(this).data("action");"stay"===a?c.fadeOut():window.location=b}),a.preventDefault()}),jQuery().slider&&jQuery(".wm-meta-wrap .slider-wrap").each(function(){var a="#"+jQuery(this).data("option"),b=jQuery(a+"-slider").data("value"),c=jQuery(a+"-slider").data("min"),d=jQuery(a+"-slider").data("max"),e=jQuery(a+"-slider").data("step");jQuery(a).attr("readonly","readonly"),jQuery(a+"-slider").slider({value:b,min:c,max:d,step:e,slide:function(b,c){jQuery(a).val(c.value)}})}),jQuery(".wm-meta-wrap .radio-wrap.custom-label input:checked").parent(".inline-radio").addClass("active"),jQuery(".wm-meta-wrap .radio-wrap.custom-label input").on("change",function(){jQuery(this).parent(".inline-radio").addClass("active").siblings(".inline-radio").removeClass("active")}),jQuery(".wm-meta-wrap .button-add-cell").on("click",function(a){a.preventDefault();var c=jQuery(this),d=c.parent("td").find(".repeater-cells"),e=c.parent("td").find(".repeater-cell:last"),f=e.clone(!0).off();e.length<1&&alert("Please, reload the page"),jQuery('[class*="fieldtype-"]',f).val("").attr("name",function(a,c){return b(a,c)}).attr("id",function(a,c){return b(a,c)}),jQuery("label",f).attr("for",function(a,c){return b(a,c)}),jQuery("[data-option]",f).attr("data-option",function(a,c){return b(a,c)}),f.insertAfter(e,c.parent("td").find(".repeater-cells")),d.children().length>1&&d.find(".button-remove-cell").removeClass("button-disabled")}),jQuery(".wm-meta-wrap .button-remove-cell").on("click",function(a){var b=jQuery(this),c=b.closest(".repeater-cells");b.closest(".repeater-cells").children().length>1&&b.closest(".repeater-cell").remove(),c.children().length<=1&&c.find(".button-remove-cell").addClass("button-disabled"),a.preventDefault()}),jQuery().sortable&&jQuery(".wm-meta-wrap .repeater-cells").sortable({cursor:"move",handle:".button-move-cell",items:"> .repeater-cell",opacity:.66,revert:!0}),jQuery().tabs&&jQuery(".wm-meta-wrap.jquery-ui-tabs").tabs(),jQuery(".wm-meta-wrap .option-heading.toggle").not(".open").closest("tbody").next("tbody").hide(),jQuery(".wm-meta-wrap .option-heading.toggle").on("click",function(){jQuery(this).closest("tbody").next("tbody").toggle()});var d=jQuery(".composer-switch a");c(),d.live("click",function(){c()}),jQuery(".wm-meta-wrap .zip-wrap label, .wm-meta-wrap .zip-wrap .fieldtype-zip, .wm-meta-wrap .button-set-zip").on("click",function(a){if("undefined"!=typeof wp&&wp.media&&wp.media.editor){var b=jQuery(this).data("id"),c=wp.media.editor.send.attachment;wp.media.editor.send.attachment=function(a,d){1<d.url.search(".zip")||1<d.url.search(".ZIP")?(jQuery('input[name="'+b+'[url]"]').val(d.url),jQuery('input[name="'+b+'[id]"]').val(d.id)):(alert("No ZIP file selected"),jQuery('input[name="'+b+'[url]"]').val(""),jQuery('input[name="'+b+'[id]"]').val("")),wp.media.editor.send.attachment=c},wp.media.editor.open(),a.preventDefault()}}),jQuery('select[name="page_template"], .wm-meta-wrap select').change()});