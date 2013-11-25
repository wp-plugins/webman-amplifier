/**
 * WebMan Metabox Generator scripts
 *
 * @package     WebMan Amplifier
 * @subpackage  Metabox
 *
 * @since       1.0
 */

jQuery(function(){function e(e,t){return t.replace(/(\[\d+\])/,function(e,t){t=t.substr(1,t.length-2);return"["+(Number(t)+1)+"]"})}function t(){if(jQuery("#wpb_visual_composer").is(":visible")){jQuery("body").addClass("wm-visual-composer-on")}else{jQuery("body").removeClass("wm-visual-composer-on")}}jQuery(".no-js").removeClass("no-js");if(jQuery().wpColorPicker){jQuery(".wm-meta-wrap .color-wrap .fieldtype-text").wpColorPicker()}jQuery(".button-default-value").on("click",function(){var e=jQuery(this),t=e.data("option"),n=e.find("span").text();if(e.hasClass("default-gallery")){jQuery("#"+t).attr("value",n);jQuery(".gallery-"+t).html("")}else if(e.hasClass("default-slider")&&jQuery().slider){jQuery("#"+t+"-slider").slider("option","value",n);jQuery("#"+t).attr("value",n)}else{var r='.wm-meta-wrap [name="'+t+'"]';if("radio"===jQuery(r).attr("type")){jQuery(r+'[value="'+n+'"]').attr("checked","checked")}else{jQuery(r).val(n).change()}}});wmFeaturedImage={get:function(){return wp.media.view.settings.post.featuredImageId},set:function(e){var t=wp.media.view.settings;t.post.featuredImageId=e;wp.media.post("set-post-thumbnail",{json:true,post_id:t.post.id,thumbnail_id:t.post.featuredImageId,_wpnonce:t.post.nonce}).done(function(e){jQuery(".inside","#postimagediv").html(e)})},frame:function(){if(this._frame){return this._frame}this._frame=wp.media({state:"featured-image",states:[new wp.media.controller.FeaturedImage]});this._frame.on("toolbar:create:featured-image",function(e){this.createSelectToolbar(e,{text:wp.media.view.l10n.setFeaturedImage})},this._frame);this._frame.state("featured-image").on("select",this.select);return this._frame},select:function(){var e=wp.media.view.settings,t=this.get("selection").single();if(!e.post.featuredImageId){return}wmFeaturedImage.set(t?t.id:-1)},init:function(){jQuery(".button-set-featured-image").on("click",function(e){e.preventDefault();e.stopPropagation();wmFeaturedImage.frame().open()})}};wmFeaturedImage.init();jQuery(".wm-meta-wrap .gallery-wrap").on("click",".button-set-gallery",function(){if(typeof wp==="undefined"||!wp.media||!wp.media.gallery){return}var e=jQuery(this).data("id"),t=typeof wmGalleryPreviewNonce=="undefined"?"":wmGalleryPreviewNonce,n=jQuery("#"+e),r=wp.media.gallery,i=r.edit('[gallery ids="'+n.val()+'"]'),s=i.el.getAttribute("class");i.el.setAttribute("class",s+" wmamp-gallery");i.state("gallery-edit").on("update",function(i){var s=r.shortcode(i).attrs.named.ids,o=s.join(",");jQuery(".gallery-"+e).find("a").hide();jQuery(".gallery-loading-"+e).show();if(s.length){jQuery.post(ajaxurl,{action:"wm-gallery-preview-refresh",fieldID:e,images:s,wmGalleryNonce:t},function(t){jQuery(".gallery-"+e).html(t);jQuery(".gallery-loading-"+e).hide()})}n.val(o)});return false});jQuery(".wm-meta-wrap .image-wrap label, .wm-meta-wrap .button-set-image").on("click",function(){if(typeof wp==="undefined"||!wp.media||!wp.media.editor){return}var e=jQuery(this).data("id"),t=wp.media.editor.send.attachment;wp.media.editor.send.attachment=function(n,r){jQuery('input[name="'+e+'[url]"]').val(r.url);jQuery('input[name="'+e+'[url]"]').attr("data-preview",r.sizes.thumbnail.url);jQuery('input[name="'+e+'[id]"]').val(r.id);jQuery("div.image-"+e).removeClass("hide").find("img").attr("src",r.sizes.thumbnail.url);wp.media.editor.send.attachment=t};wp.media.editor.open();return false});jQuery(".wm-meta-wrap .preview-enabled .fieldtype-image").on("change",function(){var e=jQuery(this),t=e.val(),n=e.attr("id"),r=e.data("nothumb");if(t){t=e.data("preview")?e.data("preview"):e.val(),jQuery("div.image-"+n).find("img").attr("src",t)}else{e.data("preview","");jQuery("div.image-"+n).find("img").attr("src",r)}});jQuery(".wm-meta-wrap .fieldtype-image").change();jQuery(".wm-meta-wrap .confirm").on("click",function(){var e=jQuery(this).attr("href"),t=jQuery(".wm-meta-wrap .modal-box").fadeIn();t.find("a").on("click",function(){var n=jQuery(this).data("action");if("stay"===n)t.fadeOut();else window.location=e});return false});if(jQuery().slider){jQuery(".wm-meta-wrap .slider-wrap").each(function(){var e="#"+jQuery(this).data("option"),t=jQuery(e+"-slider").data("value"),n=jQuery(e+"-slider").data("min"),r=jQuery(e+"-slider").data("max"),i=jQuery(e+"-slider").data("step");jQuery(e).attr("readonly","readonly");jQuery(e+"-slider").slider({value:t,min:n,max:r,step:i,slide:function(t,n){jQuery(e).val(n.value)}})})}jQuery(".wm-meta-wrap .radio-wrap.custom-label input:checked").parent(".inline-radio").addClass("active");jQuery(".wm-meta-wrap .radio-wrap.custom-label input").on("change",function(){jQuery(this).parent(".inline-radio").addClass("active").siblings(".inline-radio").removeClass("active")});jQuery(".wm-meta-wrap .button-add-cell").on("click",function(){var t=jQuery(this),n=t.parent("td").find(".repeater-cells"),r=t.parent("td").find(".repeater-cell:last"),i=r.clone(true).off();if(r.length<1){alert("Please, reload the page");return false}jQuery('[class*="fieldtype-"]',i).val("").attr("name",function(t,n){return e(t,n)}).attr("id",function(t,n){return e(t,n)});jQuery("label",i).attr("for",function(t,n){return e(t,n)});jQuery("[data-option]",i).attr("data-option",function(t,n){return e(t,n)});i.insertAfter(r,t.parent("td").find(".repeater-cells"));if(n.children().length>1){n.find(".button-remove-cell").removeClass("button-disabled")}return false});jQuery(".wm-meta-wrap .button-remove-cell").on("click",function(){var e=jQuery(this),t=e.closest(".repeater-cells");if(e.closest(".repeater-cells").children().length>1){e.closest(".repeater-cell").remove()}if(t.children().length<=1){t.find(".button-remove-cell").addClass("button-disabled")}return false});if(jQuery().sortable){jQuery(".wm-meta-wrap .repeater-cells").sortable({cursor:"move",handle:".button-move-cell",items:"> .repeater-cell",opacity:.66,revert:true})}if(jQuery().tabs){jQuery(".wm-meta-wrap.jquery-ui-tabs").tabs()}jQuery(".wm-meta-wrap .option-heading.toggle").not(".open").closest("tbody").next("tbody").hide();jQuery(".wm-meta-wrap .option-heading.toggle").on("click",function(){jQuery(this).closest("tbody").next("tbody").toggle()});var n=jQuery(".composer-switch a");t();n.live("click",function(){t()});jQuery(".wm-meta-wrap .zip-wrap label, .wm-meta-wrap .zip-wrap .fieldtype-zip, .wm-meta-wrap .button-set-zip").on("click",function(){if(typeof wp==="undefined"||!wp.media||!wp.media.editor){return}var e=jQuery(this).data("id"),t=wp.media.editor.send.attachment;wp.media.editor.send.attachment=function(n,r){if(1<r.url.search(".zip")||1<r.url.search(".ZIP")){jQuery('input[name="'+e+'[url]"]').val(r.url);jQuery('input[name="'+e+'[id]"]').val(r.id)}else{alert("No ZIP file selected");jQuery('input[name="'+e+'[url]"]').val("");jQuery('input[name="'+e+'[id]"]').val("")}wp.media.editor.send.attachment=t};wp.media.editor.open();return false});jQuery('select[name="page_template"], .wm-meta-wrap select').change()})