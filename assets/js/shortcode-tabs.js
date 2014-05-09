/**
 * WebMan Accordion shortcode scripts
 *
 * @package     WebMan Amplifier
 * @subpackage  Shortcodes
 *
 * @since       1.0
 */

jQuery(function(){var e=jQuery(".wm-tabs");e.find(".wm-item").hide();e.each(function(){var e=jQuery(this),t=e.find(".wm-item").length,n=0<e.data("active")?e.data("active"):1;e.find(".wm-item").eq(n-1).toggleClass("active").show();e.find(".wm-tab-links li").eq(n-1).addClass("active")});e.on("click",".wm-tab-links a",function(e){e.preventDefault();var t=jQuery(this);t.parent().addClass("active").siblings().removeClass("active");jQuery(t.data("tab")).show().siblings(".wm-item").hide()});var t=jQuery(".wm-tabs.tour-tabs");jQuery('<div class="wm-tour-nav top"><span class="prev"></span><span class="next"></span></div>').prependTo(".wm-tabs.tour-tabs .wm-tabs-items");jQuery('<div class="wm-tour-nav bottom"><span class="prev"></span><span class="next"></span></div>').appendTo(".wm-tabs.tour-tabs .wm-tabs-items");t.each(function(){var e=jQuery(this),t=e.find(".wm-tab-links li.active").prev("li").html(),n=e.find(".wm-tab-links li.active").next("li").html();if("undefined"!=typeof t&&t.length){e.find(".wm-tour-nav .prev").html(t)}if("undefined"!=typeof n&&n.length){e.find(".wm-tour-nav .next").html(n)}});t.on("click",".wm-tab-links a",function(){var e=jQuery(this),t=e.closest(".wm-tabs"),n=e.parent().prev("li"),r=e.parent().next("li");if(n.length){t.find(".wm-tour-nav .prev").html(n.html())}else{t.find(".wm-tour-nav .prev").html("")}if(r.length){t.find(".wm-tour-nav .next").html(r.html())}else{t.find(".wm-tour-nav .next").html("")}});jQuery(".wm-tour-nav").on("click","a",function(e){e.preventDefault();var t=jQuery(this),n=t.closest(".wm-tabs"),r=t.data("tab"),i=n.find(".wm-tab-items-"+r.substring(1)).prev("li"),s=n.find(".wm-tab-items-"+r.substring(1)).next("li");jQuery(".wm-tab-items-"+r.substring(1)).addClass("active").siblings("li").removeClass("active");jQuery(r).show().siblings(".wm-item").hide();if(i.length){n.find(".wm-tour-nav .prev").html(i.html())}else{n.find(".wm-tour-nav .prev").html("")}if(s.length){n.find(".wm-tour-nav .next").html(s.html())}else{n.find(".wm-tour-nav .next").html("")}})})