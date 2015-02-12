# WebMan Amplifier Changelog

## 1.1.5

* Updated: Improved support with Beaver Builder (unfortunatelly, not backwards compatible as custom modules file names have been renamed)

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

* Fixed: Custom posts issue introduced in update 1.1.3

#### Files changed:

	class-wm-amplifier.php

## 1.1.3

* Updated: Icon font updated to Font Awesome v4.1 (resave the font to regenerate)
* Updated: Isotope license notification moved to WordPress pointer
* Updated: Localization function
* Updated: Removed deprecated action hooks
* Fixed: Scripts and conditions for including Shortcode Generator button
* Fixed: Localization texts

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

* Updated: Function for color format change
* Updated: Functions for folder and file creation
* Updated: Example setup file
* Fixed: Removing Visual Composer front end styles

#### Files changed:

	webman-amplifier-setup.php
	includes/functions.php
	includes/shortcodes/class-shortcodes.php


## 1.1.1

* Updated: Improved support for Beaver Builder plugin
* Updated: Schema.org markup generator function
* Updated: Hook names
* Fixed: PHP error when using the Beaver Builder plugin

#### Files changed:

	class-wm-amplifier.php
	includes/functions.php
	includes/shortcodes/page-builder/beaver-builder/beaver-builder.php


## 1.1

* Added: Visual Composer 4.4 support
* Added: Beaver Builder plugin support
* Added: Plugin action links plugins admin page
* Added: Slovak localization file
* Updated: Optimized and improved code
* Updated: Removed obsolete files
* Updated: Better file organization
* Updated: Scripts updated: Isotope 2.1.0
* Updated: Removed support for old WordPress versions
* Updated: Renamed `type` shortcode attribute to `appearance` (keeping backwards compatibility)
* Updated: Localization functions and files
* Fixed: Fixed hook names
* Fixed: Vertical tabs min tab content height

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

* Added: Function to register additional custom taxonomies
* Added: 'wma_supports_subfeature( $subfeature )' function
* Updated: Function to check if the theme supports plugin's features
* Updated: Improved disabling shortcodes and icon font classes
* Updated: Improved performance of the code
* Updated: Metabox styles
* Updated: Renamed 'remove_vc_shortcodes' to 'remove-vc-shortcodes' (while keeping backwards compatibility)
* Updated: WebMan Amplifier setup file
* Fixed: Function name for 'WMAMP_LATE_LOAD' feature

#### Files changed:

	class-wm-amplifier.php
	webman-amplifier.php
	webman-amplifier-setup.php
	assets/css/metabox.css
	assets/css/dev/metabox.dev.css
	includes/functions.php
	includes/shortcodes/class-shortcodes.php


## 1.0.9.14

* Updated: Allow disabling shortcodes, icon font and metaboxes classes

#### Files changed:

	class-wm-amplifier.php


## 1.0.9.13

* Updated: Posts shortcode filter name

#### Files changed:

	includes/shortcodes/renderers/posts.php


## 1.0.9.12

* Updated: Updated Twitter OAuth library

#### Files changed:

	includes/twitter-api/OAuth.php
	includes/twitter-api/twitteroauth.php


## 1.0.9.11

* Updated: Optimized code
* Updated: Including Twitter OAuth library conditionally to prevent issues with other plugins

#### Files changed:

	class-wm-amplifier.php
	includes/functions.php
	includes/shortcodes/class-shortcodes.php
	includes/widgets/w-twitter.php


## 1.0.9.10

* Updated: Widgets class names
* Updated: Admin styles
* Fixed: Post thumbnails size in WordPress admin
* Fixed: Visual Composer Accordions and Tabs shortcode issue

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

* Added: Widgets
* Added: Option to deactivate the plugin after theme change
* Updated: Code effectivity
* Updated: Scripts: imagesLoaded 3.1.8, Isotope 2.0.1, jQuery OwlCarousel 1.3.3
* Updated: Admin notice function
* Updated: Localization file

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

* Improved Tabs shortcode
* Fixed Shortcode Generator shortcode attributes
* Fixed and improved shortcodes scripts enqueuing

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

* WordPress 4.0 support
* Improved shortcodes scripts enqueuing

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

* Added additional arguments for `do_action()` for better flexibility.

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

* Added compatibility with Visual Composer 4.3+

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

* Added `style` attribute to Icon shortcode
* Fixed applying `active` class on active tab content
* Visual Composer element screen tabs redesigned
* Setup actions updated

#### Files changed:

	class-wm-amplifier.php
	assets/css/shortcodes-vc-addons.css
	assets/css/dev/shortcodes-vc-addons.dev.css
	assets/js/shortcode-tabs.js
	assets/js/dev/shortcode-tabs.dev.js
	includes/shortcodes/definitions/definitions.php
	includes/shortcodes/renderes/icon.php


## 1.0.9.3

* Fixed the non-admin user lockout

#### Files changed:

	includes/class-icon-font.php


## 1.0.9.2

* Fix the Shortcode Generator issue in Firefox browser

#### Files changed:

	assets/js/dev/shortcodes-button.dev.js
	assets/js/shortcodes-button.js


## 1.0.9.1

* Fix the issue with admin notice

#### Files changed:

	class-wm-amplifier.php


## 1.0.9

* Added Master Slider shortcode support for Visual Composer
* Added a shortcode to display a custom post meta field value `[wm_meta field="wmamp-meta-field" custom"1/0" /]`
* Added option to define the supported version of plugin for themes
* Column shortcode styling improvements
* Divider shortcode styling option added for Visual Composer
* Taxonomies list sorted by name in shortcodes descriptions in Visual Composer
* Visual Composer inner column support improved
* Visual Composer Image shortcode styling options added
* Localization texts changed

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

* Added support for Visual Composer v4.2
* Updated sample setup file

#### Files changed:

	webman-amplifier-setup.php
	includes/functions.php
	includes/metabox/class-metabox.php
	includes/shortcodes/class-shortcodes.php
	includes/shortcodes/definitions/definitions.php
	includes/shortcodes/vc_addons/shortcodes-admin.php


## 1.0.7

* Metabox class improved (not to throw out PHP warning)
* Metabox function name fixed in `webman-amplifier-setup.php`

#### Files changed:

	includes/metabox/class-metabox.php
	webman-amplifier-setup.php


## 1.0.6

* Isotope filter fixed for RTL languages
* Sorting font icons preview alphabetically

#### Files changed:

	includes/class-icon-font.php
	assets/js/dev/shortcode-posts.dev.js
	assets/js/shortcode-posts.js


## 1.0.5

* Added plugin deactivation hook
* Better hooking into `wma_meta_option()` function
* Sorting outputs of `wma_pages_array()` and `wma_taxonomy_array()` functions
* Filter names fixed in `wma_posts_array()`, `wma_pages_array()` and `wma_widget_areas_array()` functions

#### Files changed:

	webman-amplifier.php
	includes/functions.php


## 1.0

* Initial release.