# WebMan Amplifier Changelog

## 1.2.2

* **Add**: Visual Composer 4.6+ support
* **Add**: Ability to filter icons in metaboxes and page builders
* **Add**: Applied SASS for all CSS stylesheets
* **Update**: Scripts updates: Isotope 2.2.1, bxSlider 4.2.5
* **Update**: Retina ready admin interface, removed support for Internet Explorer 8
* **Update**: Removed front-end shortcodes sample CSS stylesheet
* **Update**: Removed obsolete jQuery plugins CSS stylesheets
* **Update**: Supplying unpacked, non-minified styles and JavaScript
* **Update**: Metabox icons selector (custom radio buttons control) styles and functionality
* **Update**: Sample plugin setup file for usage in themes
* **Update**: Improved and optimized code
* **Update**: Localization

#### Files changed:

	class-wm-amplifier.php
	webman-amplifier-setup.php
	webman-amplifier.php
	assets/css/admin-addons.css
	assets/css/input-wm-radio.css
	assets/css/metabox.css
	assets/css/rtl-metabox.css
	assets/css/rtl-shortcodes-generator.css
	assets/css/rtl-shortcodes-vc-addons.css
	assets/css/shortcodes-bb-addons.css
	assets/css/shortcodes-vc-addons.css
	assets/js/metabox.js
	assets/js/shortcode-accordion.js
	assets/js/shortcode-parallax.js
	assets/js/shortcode-posts.js
	assets/js/shortcode-slideshow.js
	assets/js/shortcode-tabs.js
	assets/js/shortcodes-button.js
	assets/js/shortcodes-ie.js
	assets/js/shortcodes-vc-addons.js
	assets/sass/admin-addons.scss
	assets/sass/input-wm-radio.scss
	assets/sass/metabox.scss
	assets/sass/rtl-metabox.scss
	assets/sass/rtl-shortcodes-generator.scss
	assets/sass/rtl-shortcodes-vc-addons.scss
	assets/sass/shortcodes-bb-addons.scss
	assets/sass/shortcodes-vc-addons.scss
	includes/functions.php
	includes/custom-posts/modules.php
	includes/metabox/fields/radio.php
	includes/metabox/fields/slider.php
	includes/shortcodes/class-shortcodes.php
	includes/shortcodes/definitions/definitions.php
	includes/visual-editor/visual-editor.php
	includes/widgets/w-twitter.php
	languages/sk_SK.mo
	languages/sk_SK.po
	languages/xx_XX.pot


## 1.2.1

* **Update**: Font Awesome 4.3
* **Fix**: Hashtag links in Twitter widget

#### Files changed:

	assets/font/*.*
	includes/widgets/w-twitter.php


## 1.2

* **Add**: Visual Composer 4.5.2 compatibility (fixed the `content` parameter not being parsed)
* **Update**: Using `tag_escape()` where needed
* **Update**: Rehooked the custom widgets to early `init` action
* **Update**: WP_Query optimalizations
* **Update**: Generalized script handler names
* **Update**: Removing obsolete theme constants (making theme library independent)
* **Update**: Beaver Builder affiliate link updated
* **Fix**: Filter hook names for Beaver Builder page builder integration

#### Files changed:

	class-wm-amplifier.php
	includes/functions.php
	includes/shortcodes/class-shortcodes.php
	includes/shortcodes/page-builder/beaver-builder/beaver-builder.php
	includes/shortcodes/renderers/call_to_action.php
	includes/shortcodes/renderers/content_module.php
	includes/shortcodes/renderers/countdown_timer.php
	includes/shortcodes/renderers/item.php
	includes/shortcodes/renderers/message.php
	includes/shortcodes/renderers/posts.php
	includes/shortcodes/renderers/price.php
	includes/shortcodes/renderers/row.php
	includes/shortcodes/renderers/separator_heading.php
	includes/shortcodes/renderers/slideshow.php
	includes/shortcodes/renderers/testimonials.php
	includes/widgets/w-contact.php
	includes/widgets/w-module.php
	includes/widgets/w-posts.php
	includes/widgets/w-subnav.php
	includes/widgets/w-tabbed-widgets.php
	includes/widgets/w-twitter.php


## 1.1.7.6

* **Update**: Removing `uninstall.php` file to prevent possible issues when deleting the plugin via WordPress dashboard

#### Files removed:

	uninstall.php


## 1.1.7.5

* **Update**: Contact Widget anti-spam protection

#### Files changed:

	includes/widgets/w-contact.php


## 1.1.7

* **Update**: Compatibility with Visual Composer 4.5

#### Files changed:

	assets/css/shortcodes-vc-addons.css
	assets/css/dev/shortcodes-vc-addons.dev.css
	includes/shortcodes/class-shortcodes.php


## 1.1.6

* **Update**: Security tightening
* **Update**: Improved support with Beaver Builder page builder plugin
* **Update**: Improved shortcodes filters and escaping
* **Update**: Removed `wptexturize()` from preformated text shortcode
* **Update**: Removed obsolete constants
* **Update**: Metabox fields improvements
* **Update**: Localization
* **Update**: Update scripts: Isotope v2.2.0, BxSlider v4.2.3

#### Files changed:

	includes/functions.php
	includes/metabox/fields/checkbox.php
	includes/metabox/fields/images.php
	includes/metabox/fields/radio.php
	includes/metabox/fields/repeater.php
	includes/metabox/fields/sections.php
	includes/metabox/fields/select.php
	includes/metabox/fields/slider.php
	includes/metabox/fields/texts.php
	includes/shortcodes/class-shortcodes.php
	includes/shortcodes/definitions/definitions.php
	includes/shortcodes/page-builder/beaver-builder/beaver-builder.php
	includes/shortcodes/renderers/accordion.php
	includes/shortcodes/renderers/audio.php
	includes/shortcodes/renderers/button.php
	includes/shortcodes/renderers/call_to_action.php
	includes/shortcodes/renderers/column.php
	includes/shortcodes/renderers/content_module.php
	includes/shortcodes/renderers/countdown_timer.php
	includes/shortcodes/renderers/divider.php
	includes/shortcodes/renderers/dropcap.php
	includes/shortcodes/renderers/icon.php
	includes/shortcodes/renderers/image.php
	includes/shortcodes/renderers/item.php
	includes/shortcodes/renderers/last_update.php
	includes/shortcodes/renderers/list.php
	includes/shortcodes/renderers/marker.php
	includes/shortcodes/renderers/message.php
	includes/shortcodes/renderers/posts.php
	includes/shortcodes/renderers/pre.php
	includes/shortcodes/renderers/price.php
	includes/shortcodes/renderers/pricing_table.php
	includes/shortcodes/renderers/progress.php
	includes/shortcodes/renderers/row.php
	includes/shortcodes/renderers/separator_heading.php
	includes/shortcodes/renderers/slideshow.php
	includes/shortcodes/renderers/table.php
	includes/shortcodes/renderers/tabs.php
	includes/shortcodes/renderers/testimonials.php
	includes/shortcodes/renderers/text_block.php
	includes/shortcodes/renderers/video.php
	includes/shortcodes/renderers/widget_area.php
	includes/visual-editor/visual-editor.php


## 1.1.5

* **Update**: Improved support with Beaver Builder (unfortunatelly, not backwards compatible as custom modules file names have been renamed)

#### Files changed:

	includes/shortcodes/page-builder/beaver-builder/beaver-builder.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_accordion.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_button.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_call_to_action.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_content_module.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_countdown_timer.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_divider.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_icon.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_list.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_message.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_posts.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_pricing_table.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_progress.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_separator_heading.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_slideshow.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_table.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_tabs.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_testimonials.php
	includes/shortcodes/page-builder/beaver-builder/modules/wm_widget_area.php


## 1.1.4

* **Fix**: Custom posts issue introduced in update 1.1.3

#### Files changed:

	class-wm-amplifier.php


## 1.1.3

* **Update**: Icon font updated to Font Awesome v4.1 (resave the font to regenerate)
* **Update**: Isotope license notification moved to WordPress pointer
* **Update**: Localization function
* **Update**: Removed deprecated action hooks
* **Fix**: Scripts and conditions for including Shortcode Generator button
* **Fix**: Localization texts

#### Files changed:

	class-wm-amplifier.php
	assets/font/config.php
	assets/font/fontello.dev.css
	assets/js/shortcodes-button.js
	assets/js/dev/shortcodes-button.dev.js
	includes/visual-editor/visual-editor.php
	languages/sk_SK.po
	languages/xx_XX.pot


## 1.1.2

* **Update**: Function for color format change
* **Update**: Functions for folder and file creation
* **Update**: Example setup file
* **Fix**: Removing Visual Composer front end styles

#### Files changed:

	webman-amplifier-setup.php
	includes/functions.php
	includes/shortcodes/class-shortcodes.php


## 1.1.1

* **Update**: Improved support for Beaver Builder plugin
* **Update**: Schema.org markup generator function
* **Update**: Hook names
* **Fix**: PHP error when using the Beaver Builder plugin

#### Files changed:

	class-wm-amplifier.php
	includes/functions.php
	includes/shortcodes/page-builder/beaver-builder/beaver-builder.php


## 1.1

* **Add**: Visual Composer 4.4 support
* **Add**: Beaver Builder plugin support
* **Add**: Plugin action links plugins admin page
* **Add**: Slovak localization file
* **Update**: Optimized and improved code
* **Update**: Removed obsolete files
* **Update**: Better file organization
* **Update**: Scripts updated: Isotope 2.1.0
* **Update**: Removed support for old WordPress versions
* **Update**: Renamed `type` shortcode attribute to `appearance` (keeping backwards compatibility)
* **Update**: Localization functions and files
* **Fix**: Fixed hook names
* **Fix**: Vertical tabs min tab content height

#### Files changed:

	class-wm-amplifier.php
	uninstall.php
	webman-amplifier-setup.php
	assets/css/admin-addons.css
	assets/css/input-wm-radio.css
	assets/css/metabox.css
	assets/css/rtl-shortcodes-generator.css
	assets/css/rtl-shortcodes-vc-addons.css
	assets/css/shortcodes-bb-addons.css
	assets/css/shortcodes-vc-addons.css
	assets/css/dev/admin-addons.dev.css
	assets/css/dev/input-wm-radio.dev.css
	assets/css/dev/metabox.dev.css
	assets/css/dev/rtl-shortcodes-generator.dev.css
	assets/css/dev/rtl-shortcodes-vc-addons.dev.css
	assets/css/dev/shortcodes-bb-addons.dev.css
	assets/css/dev/shortcodes-vc-addons.dev.css
	assets/js/shortcode-tabs.js
	assets/js/shortcodes-button.js
	assets/js/dev/shortcode-tabs.dev.js
	assets/js/dev/shortcodes-button.dev.js
	includes/class-icon-font.php
	includes/functions.php
	includes/custom-posts/logos.php
	includes/custom-posts/modules.php
	includes/custom-posts/projects.php
	includes/custom-posts/staff.php
	includes/custom-posts/testimonials.php
	includes/metabox/class-metabox.php
	includes/metabox/fields/radio.php
	includes/metabox/fields/select.php
	includes/metabox/fields/slider.php
	includes/metabox/fields/texts.php
	includes/shortcodes/class-shortcodes.php
	includes/shortcodes/definitions/definitions.php
	includes/shortcodes/page-builder/beaver-builder/beaver-builder.php
	includes/shortcodes/page-builder/beaver-builder/modules/accordion.php
	includes/shortcodes/page-builder/beaver-builder/modules/button.php
	includes/shortcodes/page-builder/beaver-builder/modules/call_to_action.php
	includes/shortcodes/page-builder/beaver-builder/modules/content_module.php
	includes/shortcodes/page-builder/beaver-builder/modules/countdown_timer.php
	includes/shortcodes/page-builder/beaver-builder/modules/divider.php
	includes/shortcodes/page-builder/beaver-builder/modules/icon.php
	includes/shortcodes/page-builder/beaver-builder/modules/list.php
	includes/shortcodes/page-builder/beaver-builder/modules/message.php
	includes/shortcodes/page-builder/beaver-builder/modules/posts.php
	includes/shortcodes/page-builder/beaver-builder/modules/pricing_table.php
	includes/shortcodes/page-builder/beaver-builder/modules/progress.php
	includes/shortcodes/page-builder/beaver-builder/modules/separator_heading.php
	includes/shortcodes/page-builder/beaver-builder/modules/slideshow.php
	includes/shortcodes/page-builder/beaver-builder/modules/table.php
	includes/shortcodes/page-builder/beaver-builder/modules/tabs.php
	includes/shortcodes/page-builder/beaver-builder/modules/testimonials.php
	includes/shortcodes/page-builder/beaver-builder/modules/widget_area.php
	includes/shortcodes/page-builder/beaver-builder/modules/css/settings.css
	includes/shortcodes/page-builder/beaver-builder/modules/includes/frontend.php
	includes/shortcodes/page-builder/beaver-builder/modules/js/settings.js
	includes/shortcodes/renderers/button.php
	includes/shortcodes/renderers/call_to_action.php
	includes/shortcodes/renderers/content_module.php
	includes/shortcodes/renderers/countdown_timer.php
	includes/shortcodes/renderers/divider.php
	includes/shortcodes/renderers/icon.php
	includes/shortcodes/renderers/message.php
	includes/shortcodes/renderers/price.php
	includes/shortcodes/renderers/table.php
	includes/visual-editor/visual-editor.php
	includes/widgets/w-contact.php
	includes/widgets/w-module.php
	includes/widgets/w-posts.php
	includes/widgets/w-subnav.php
	includes/widgets/w-tabbed-widgets.php
	includes/widgets/w-twitter.php
	templates/content-shortcode-posts-post.php
	templates/content-shortcode-posts.php


## 1.0.9.15

* **Add**: Function to register additional custom taxonomies
* **Add**: 'wma_supports_subfeature( $subfeature )' function
* **Update**: Function to check if the theme supports plugin's features
* **Update**: Improved disabling shortcodes and icon font classes
* **Update**: Improved performance of the code
* **Update**: Metabox styles
* **Update**: Renamed 'remove_vc_shortcodes' to 'remove-vc-shortcodes' (while keeping backwards compatibility)
* **Update**: WebMan Amplifier setup file
* **Fix**: Function name for 'WMAMP_LATE_LOAD' feature

#### Files changed:

	class-wm-amplifier.php
	webman-amplifier.php
	webman-amplifier-setup.php
	assets/css/metabox.css
	assets/css/dev/metabox.dev.css
	includes/functions.php
	includes/shortcodes/class-shortcodes.php


## 1.0.9.14

* **Update**: Allow disabling shortcodes, icon font and metaboxes classes

#### Files changed:

	class-wm-amplifier.php


## 1.0.9.13

* **Update**: Posts shortcode filter name

#### Files changed:

	includes/shortcodes/renderers/posts.php


## 1.0.9.12

* **Update**: Updated Twitter OAuth library

#### Files changed:

	includes/twitter-api/OAuth.php
	includes/twitter-api/twitteroauth.php


## 1.0.9.11

* **Update**: Optimized code
* **Update**: Including Twitter OAuth library conditionally to prevent issues with other plugins

#### Files changed:

	class-wm-amplifier.php
	includes/functions.php
	includes/shortcodes/class-shortcodes.php
	includes/widgets/w-twitter.php


## 1.0.9.10

* **Update**: Widgets class names
* **Update**: Admin styles
* **Fix**: Post thumbnails size in WordPress admin
* **Fix**: Visual Composer Accordions and Tabs shortcode issue

#### Files changed:

	assets/css/admin-addons.css
	assets/css/rtl-shortcodes-vc-addons.css
	assets/css/shortcodes-vc-addons.css
	assets/css/shortcodes-vc-addons-old.css
	assets/css/dev/admin-addons.css
	assets/css/dev/rtl-shortcodes-vc-addons.css
	assets/css/dev/shortcodes-vc-addons.css
	assets/css/dev/shortcodes-vc-addons-old.css
	assets/js/shortcodes-vc-addons.js
	assets/js/dev/shortcodes-vc-addons.js
	includes/shortcodes/definitions/definitions.php
	includes/widgets/w-contact.php
	includes/widgets/w-module.php
	includes/widgets/w-posts.php
	includes/widgets/w-subnav.php
	includes/widgets/w-tabbed-widgets.php
	includes/widgets/w-twitter.php


## 1.0.9.9

* **Add**: Widgets
* **Add**: Option to deactivate the plugin after theme change
* **Update**: Code effectivity
* **Update**: Scripts: imagesLoaded 3.1.8, Isotope 2.0.1, jQuery OwlCarousel 1.3.3
* **Update**: Admin notice function
* **Update**: Localization file

#### Files added:

	includes/widgets/w-contact.php
	includes/widgets/w-module.php
	includes/widgets/w-posts.php
	includes/widgets/w-subnav.php
	includes/widgets/w-tabbed-widgets.php
	includes/widgets/w-twitter.php

#### Files changed:

	class-wm-amplifier.php
	webman-amplifier.php
	assets/js/plugins/imagesloaded.min.js
	assets/js/plugins/isotope.pkgd.min.js
	assets/js/plugins/owl.carousel.min.js
	includes/functions.php
	includes/functions.php
	languages/wm_domain.pot


## 1.0.9.8

* **Update**: Improved Tabs shortcode
* **Fix**: Shortcode Generator shortcode attributes
* **Fix**: Improved shortcodes scripts enqueuing

#### Files changed:

	assets/css/shortcodes-vc-addons.css
	assets/css/dev/shortcodes-vc-addons.dev.css
	includes/shortcodes/class-shortcodes.php
	includes/shortcodes/definitions/definitions.php
	includes/shortcodes/renderers/accordion.php
	includes/shortcodes/renderers/content_module.php
	includes/shortcodes/renderers/countdown_timer.php
	includes/shortcodes/renderers/posts.php
	includes/shortcodes/renderers/row.php
	includes/shortcodes/renderers/slideshow.php
	includes/shortcodes/renderers/tabs.php
	includes/shortcodes/renderers/testimonials.php


## 1.0.9.7

* **Update**: WordPress 4.0 support
* **Update**: Improved shortcodes scripts enqueuing

#### Files changed:

	assets/metabox.css
	assets/dev/metabox.dev.css
	includes/shortcodes/renderers/accordion.php
	includes/shortcodes/renderers/content_module.php
	includes/shortcodes/renderers/countdown_timer.php
	includes/shortcodes/renderers/posts.php
	includes/shortcodes/renderers/row.php
	includes/shortcodes/renderers/slideshow.php
	includes/shortcodes/renderers/tabs.php
	includes/shortcodes/renderers/testimonials.php


## 1.0.9.6

* **Add**: Additional arguments for `do_action()` for better flexibility.

#### Files changed:

	includes/shortcodes/class-shortcodes.php
	includes/shortcodes/renderers/accordion.php
	includes/shortcodes/renderers/content_module.php
	includes/shortcodes/renderers/countdown_timer.php
	includes/shortcodes/renderers/posts.php
	includes/shortcodes/renderers/row.php
	includes/shortcodes/renderers/slideshow.php
	includes/shortcodes/renderers/tabs.php
	includes/shortcodes/renderers/testimonials.php


## 1.0.9.5

* **Add**: Compatibility with Visual Composer 4.3+

#### Files changed:

	webman-amplifier-setup.php
	assets/css/shortcodes-vc-addons.css
	assets/css/rtl-shortcodes-vc-addons.css
	assets/css/dev/rtl-shortcodes-vc-addons.dev.css
	assets/css/shortcodes-vc-addons-old.css
	assets/css/dev/shortcodes-vc-addons.dev.css
	assets/css/dev/shortcodes-vc-addons-old.dev.css
	assets/js/shortcodes-vc-addons.js
	assets/js/dev/shortcodes-vc-addons.dev.js
	includes/shortcodes/class-shortcodes.php
	includes/shortcodes/vc_addons/shortcodes-admin.php


## 1.0.9.4

* **Add**: `style` attribute to Icon shortcode
* **Update**: Visual Composer element screen tabs redesigned
* **Update**: Setup actions updated
* **Fix**: Applying `active` class on active tab content

#### Files changed:

	class-wm-amplifier.php
	assets/css/shortcodes-vc-addons.css
	assets/css/dev/shortcodes-vc-addons.dev.css
	assets/js/shortcode-tabs.js
	assets/js/dev/shortcode-tabs.dev.js
	includes/shortcodes/definitions/definitions.php
	includes/shortcodes/renderes/icon.php


## 1.0.9.3

* **Fix**: Non-admin user lockout

#### Files changed:

	includes/class-icon-font.php


## 1.0.9.2

* **Fix**: The Shortcode Generator issue in Firefox browser

#### Files changed:

	assets/js/dev/shortcodes-button.dev.js
	assets/js/shortcodes-button.js


## 1.0.9.1

* **Fix**: The issue with admin notice

#### Files changed:

	class-wm-amplifier.php


## 1.0.9

* **Add**: Master Slider shortcode support for Visual Composer
* **Add**: Shortcode to display a custom post meta field value `[wm_meta field="wmamp-meta-field" custom"1/0" /]`
* **Add**: Option to define the supported version of plugin for themes
* **Update**: Column shortcode styling improvements
* **Update**: Divider shortcode styling option added for Visual Composer
* **Update**: Taxonomies list sorted by name in shortcodes descriptions in Visual Composer
* **Update**: Visual Composer inner column support improved
* **Update**: Visual Composer Image shortcode styling options added
* **Update**: Localization texts changed

#### Files changed:

	webman-amplifier.php
	webman-amplifier-setup.php
	assets/js/shortcode-accordion.js
	assets/js/dev/shortcode-accordion.dev.js
	includes/functions.php
	includes/shortcodes/class-shortcodes.php
	includes/shortcodes/definitions/definitions.php
	includes/shortcodes/renderers/accordion.php
	includes/shortcodes/renderers/column.php
	includes/shortcodes/renderers/image.php
	includes/shortcodes/renderers/meta.php
	includes/shortcodes/renderers/row.php
	languages/wm_domain.php


## 1.0.8

* **Add**: Support for Visual Composer v4.2
* **Update**: Sample setup file

#### Files changed:

	webman-amplifier-setup.php
	includes/functions.php
	includes/metabox/class-metabox.php
	includes/shortcodes/class-shortcodes.php
	includes/shortcodes/definitions/definitions.php
	includes/shortcodes/vc_addons/shortcodes-admin.php


## 1.0.7

* **Update**: Metabox class improved (not to throw out PHP warning)
* **Update**: Metabox function name fixed in `webman-amplifier-setup.php`

#### Files changed:

	includes/metabox/class-metabox.php
	webman-amplifier-setup.php


## 1.0.6

* **Update**: Sorting font icons preview alphabetically
* **Fix**: Isotope filter fixed for RTL languages

#### Files changed:

	includes/class-icon-font.php
	assets/js/dev/shortcode-posts.dev.js
	assets/js/shortcode-posts.js


## 1.0.5

* **Add**: plugin deactivation hook
* **Update**: Better hooking into `wma_meta_option()` function
* **Update**: Sorting outputs of `wma_pages_array()` and `wma_taxonomy_array()` functions
* **Update**: Filter names fixed in `wma_posts_array()`, `wma_pages_array()` and `wma_widget_areas_array()` functions

#### Files changed:

	webman-amplifier.php
	includes/functions.php


## 1.0

* Initial release.