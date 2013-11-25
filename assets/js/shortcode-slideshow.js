/**
 * WebMan Slideshow shortcode scripts
 *
 * @package     WebMan Amplifier
 * @subpackage  Shortcodes
 *
 * @since       1.0
 */

jQuery(function(){if(jQuery().bxSlider){if("rtl"!=jQuery("html").attr("dir")){var e="horizontal"}else{var e="fade"}jQuery(".wm-slideshow").each(function(t){var n=jQuery(this).find(".wm-slideshow-container"),r=n.parent().data("speed")?n.parent().data("speed")+500:3500,i=n.parent().data("nav")?true:false,s=n.data("pager")?n.data("pager"):null;n.bxSlider({mode:e,pause:r,auto:true,autoHover:true,controls:true,pager:i,pagerCustom:s,adaptiveHeight:true,useCSS:false})})}})