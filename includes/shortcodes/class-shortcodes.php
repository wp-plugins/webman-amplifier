<?php
/**
 * WebMan Shortcodes
 *
 * @package     WebMan Amplifier
 * @subpackage  Shortcodes
 */



//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * WebMan Shortcodes Class
 *
 * @since       1.0
 * @package     WebMan Amplifier
 * @subpackage  Shortcodes
 * @author      WebMan
 * @version     1.0
 */
if ( ! class_exists( 'WM_Shortcodes' ) ) {

	class WM_Shortcodes {

		/**
		 * VARIABLES DEFINITION
		 */

			/**
			 * @var  array
			 */
			private static $codes = array();

			/**
			 * @var  array Various attribute defaults used across multiple shortcodes
			 */
			private static $codes_globals = array();

			/**
			 * @var  string Allowed inline HTML tags (in Accordion title for example)
			 */
			private $inline_tags = '';

			/**
			 * @var  string URL to styles and scripts location
			 */
			private $assets_url = '';

			/**
			 * @var  string Path to shortcode renderers
			 */
			private $renderers_dir = '';

			/**
			 * @var  string Shortcode name prefix (if required, for example inside Visual Composer plugin)
			 */
			private $prefix_shortcode_name = '';

			/**
			 * @var  string Shortcode code prefix
			 */
			private $prefix_shortcode = 'wm_';

			/**
			 * @var  string Shortcodes subpackage version
			 */
			private $version = '1.0';

			/**
			 * @var  string Shortcodes generator user capability
			 */
			private $generator_capability = '';

			/**
			 * @var  object
			 */
			protected static $instance;





		/**
		 * INITIALIZATION FUNCTIONS
		 */

			/**
			 * Constructor
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function __construct() {
				$this->setup_globals();
				$this->assets_register();
				$this->setup_filters();
				$this->add_shortcodes();

				//Visual Composer plugin integration
					if ( class_exists( 'WPBakeryVisualComposer' ) ) {
						$this->visual_composer_support();
					}
			} // /__construct



			/**
			 * Return an instance of the class
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @return  object A single instance of this class.
			 */
			public static function instance() {
				if ( ! isset( self::$instance ) ) {
					self::$instance = new WM_Shortcodes;
				}
				return self::$instance;
			} // /instance



			/**
			 * Shortcodes globals setup
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function setup_globals() {
				//Helper variables
					$postTypes = get_post_types(); //get all post types to check if the shortcode should be added
					$fonticons = get_option( 'wmamp-icons' );
					if ( isset( $fonticons['icons_select'] ) ) {
						$fonticons = array_merge( array( '' => '' ), $fonticons['icons_select'] );
					} else {
						$fonticons = array();
					}
					$post_types = array( 'post' => __( 'Posts', 'wm_domain' ) );
					if ( in_array( 'wm_logos', $postTypes ) ) {
						$post_types['wm_logos'] = __( 'Logos', 'wm_domain' );
					}
					if ( in_array( 'wm_projects', $postTypes ) ) {
						$post_types['wm_projects'] = __( 'Projects', 'wm_domain' );
					}
					if ( in_array( 'wm_staff', $postTypes ) ) {
						$post_types['wm_staff'] = __( 'Staff', 'wm_domain' );
					}

				//Shortcode generator user capability
					$this->generator_capability = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'generator_capability', 'edit_posts' );

				//Paths and URLs
					$this->assets_url      = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'assets_url',      WMAMP_ASSETS_URL );
					$this->definitions_dir = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'definitions_dir', trailingslashit( WMAMP_INCLUDES_DIR . 'shortcodes/definitions' ) );
					$this->renderers_dir   = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'renderers_dir',   trailingslashit( WMAMP_INCLUDES_DIR . 'shortcodes/renderers' ) );
					$this->vc_addons_dir   = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_addons_dir',   trailingslashit( WMAMP_INCLUDES_DIR . 'shortcodes/vc_addons' ) );

				//Visual Composer integration
					if ( ! in_array( 'remove_vc_buttons', wm_current_theme_supports_subfeatures( 'webman-shortcodes' ) ) ) {
						$this->prefix_shortcode_name = apply_filters( WM_SHORTCODES_HOOK_PREFIX . '_prefix_name', 'WM ' );
					}

				//Shortcodes globals (variables used across multiple shortcodes)
					$this->inline_tags   = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'inline_tags',   '<a><abbr><b><br><code><em><i><img><mark><small><span><strong><u>' );
					self::$codes_globals = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'codes_globals', array(
							'align'          => array(
									'left'   => __( 'Left', 'wm_domain' ),
									'center' => __( 'Center', 'wm_domain' ),
									'right'  => __( 'Right', 'wm_domain' ),
								),
							'colors'         => array(
									'blue'   => __( 'Blue', 'wm_domain' ),
									'gray'   => __( 'Gray', 'wm_domain' ),
									'green'  => __( 'Green', 'wm_domain' ),
									'orange' => __( 'Orange', 'wm_domain' ),
									'red'    => __( 'Red', 'wm_domain' ),
								),
							'column_widths'  => array( '1/2', '1/3', '2/3', '1/4', '3/4', '1/5', '2/5', '3/5', '4/5' ),
							'divider_types'  => array(
									'line'        => __( 'Line', 'wm_domain' ),
									'dotted'      => __( 'Dotted', 'wm_domain' ),
									'dashed'      => __( 'Dashed', 'wm_domain' ),
									'double-line' => __( 'Double line', 'wm_domain' ),
									'whitespace'  => __( 'Whitespace', 'wm_domain' ),
								),
							'dropcap_shapes' => array(
									'circle'         => __( 'Circle', 'wm_domain' ),
									'square'         => __( 'Square', 'wm_domain' ),
									'rounded-square' => __( 'Rounded square', 'wm_domain' ),
									'leaf-left'      => __( 'Leaf left', 'wm_domain' ),
									'leaf-right'     => __( 'Leaf right', 'wm_domain' ),
									'half-circle'    => __( 'Half circle', 'wm_domain' ),
								),
							'font_icons'     => $fonticons,
							'post_types'     => $post_types,
							'sizes'          => array(
									's'  => 'small',
									'm'  => 'medium',
									'l'  => 'large',
									'xl' => 'extra-large',
								),
							'social_icons'  => array( 'Behance', 'Blogger', 'Delicious', 'DeviantART', 'Digg', 'Dribbble', 'Facebook', 'Flickr', 'Forrst', 'Github', 'Google+', 'Instagram', 'LinkedIn', 'MySpace', 'Pinterest', 'Reddit', 'RSS', 'Skype', 'SoundCloud', 'StumbleUpon', 'Tumblr', 'Twitter', 'Vimeo', 'WordPress', 'YouTube' ),
							'table_types'   => array(
									'basic'            => __( 'Basic', 'wm_domain' ),
									'bordered'         => __( 'Bordered', 'wm_domain' ),
									'striped'          => __( 'Zebra striping', 'wm_domain' ),
									'bordered-striped' => __( 'Bordered zebra striping', 'wm_domain' ),
								),
						) );

				//Get shortcodes definitions array
					$definitions_file_path = apply_filters( WM_SHORTCODES_HOOK_PREFIX . '_definitions_path', $this->definitions_dir . 'definitions.php' );
					if ( file_exists( $definitions_file_path ) ) {
						include_once( $definitions_file_path );
					}

				//Define the shortcodes
					$codes = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'definitions', $shortcode_definitions );

				//Empty self::$codes variable before processing
					self::$codes = array(
							'global'       => array(),
							'preprocess'   => array(),
							'styles'       => array(),
							'generator'    => array(),
							'vc_generator' => array(),
							'vc_plugin'    => array()
						);

				//Separate shortcodes into groups
					foreach ( (array) $codes as $code => $definition ) {
						//Skip the shortcode if the required post type is not supported in the theme
							if ( isset( $definition['post_type_required'] ) && trim( $definition['post_type_required'] ) && ! in_array( $definition['post_type_required'], $postTypes ) ) {
								continue;
							}

						//All shortcodes will be processed globally (except [pre] and [raw])
							if ( ! in_array( $code, array( 'pre', 'raw' ) ) ) {
								self::$codes['global'][] = $code;
							}

						//Select only shortcodes that need preprocessing
							if ( isset( $definition['preprocess'] ) && $definition['preprocess'] ) {
								self::$codes['preprocess'][] = $code;
							}

						//Setting Shortcode Generator setup
							if (
									isset( $definition['generator'] ) && $definition['generator']
									&& isset( $definition['generator']['name'] )
									&& isset( $definition['generator']['code'] )
								) {
								$definition['generator']['class'] = 'generator_item_' . $code;
								$definition['generator']['code']  = str_replace( 'PREFIX_', $this->prefix_shortcode, $definition['generator']['code'] );
								self::$codes['generator'][$code]  = $definition['generator'];
								//Shortcodes in Shortcode Generator displayed in Visual Composer plugin
								if ( isset( $definition['generator']['vc_enabled'] ) && $definition['generator']['vc_enabled'] ) {
									self::$codes['vc_generator'][$code] = $definition['generator'];
								}
							}

						//Set the setup array for shortcodes that can be replaced with "Style" button in visual editor
							if (
									isset( $definition['style'] )
									&& is_array( $definition['style'] ) && ! empty( $definition['style'] )
								) {
								self::$codes['styles'] = array_merge( self::$codes['styles'], (array) $definition['style'] );
							}

						//Visual Composer plugin integration
							if (
									isset( $definition['vc_plugin'] )
									&& is_array( $definition['vc_plugin'] ) && ! empty( $definition['vc_plugin'] )
									&& isset( $definition['vc_plugin']['name'] ) && trim( $definition['vc_plugin']['name'] ) //"name" is required parameter by the Visual Composer plugin
									&& isset( $definition['vc_plugin']['base'] ) && trim( $definition['vc_plugin']['base'] ) //"base" is required parameter by the Visual Composer plugin
								) {
								self::$codes['vc_plugin'][$code] = $definition['vc_plugin'];
							}
					}

				//Postprocess filters
					self::$codes = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'definitions_processed', self::$codes );
			} // /setup_globals



			/**
			 * Register styles and scripts
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function assets_register() {
				//Helper variables
					$icon_font_url = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'iconfont_url', get_option( 'wmamp-icon-font' ) );
					$protocol      = ( is_ssl() ) ? ( 'https' ) : ( 'http' );

				//Styles
					wp_register_style( 'wm-shortcodes',               $this->assets_url . 'css/shortcodes.css',           array(), $this->version, 'screen' );
					wp_register_style( 'wm-shortcodes-rtl',           $this->assets_url . 'css/rtl-shortcodes.css',           array(), $this->version, 'screen' );
					wp_register_style( 'wm-shortcodes-generator',     $this->assets_url . 'css/shortcodes-generator.css', array(), $this->version, 'screen' );
					wp_register_style( 'wm-shortcodes-generator-rtl', $this->assets_url . 'css/rtl-shortcodes-generator.css', array(), $this->version, 'screen' );
					wp_register_style( 'wm-shortcodes-vc-addon',      $this->assets_url . 'css/shortcodes-vc-addons.css', array(), $this->version, 'screen' );
					wp_register_style( 'wm-shortcodes-vc-addon-rtl',  $this->assets_url . 'css/rtl-shortcodes-vc-addons.css', array(), $this->version, 'screen' );
					if ( $icon_font_url ) {
						wp_register_style( 'wm-fonticons', $icon_font_url, array(), $this->version, 'screen' );
					}

				//Scripts
					wp_register_script( 'wm-shortcodes-ie',           $this->assets_url . 'js/shortcodes-ie.js', array( 'jquery' ), $this->version, true );
					wp_register_script( 'wm-shortcodes-vc-addon',     $this->assets_url . 'js/shortcodes-vc-addons.js', array( 'wpb_js_composer_js_atts', 'wpb_js_composer_js_custom_views', 'isotope' ), $this->version, true );

					wp_register_script( 'wm-shortcodes-accordion',    $this->assets_url . 'js/shortcode-accordion.js', array( 'jquery' ), $this->version, true );
					wp_register_script( 'wm-shortcodes-posts',        $this->assets_url . 'js/shortcode-posts.js', array( 'jquery' ), $this->version, true );
					wp_register_script( 'wm-shortcodes-tabs',         $this->assets_url . 'js/shortcode-tabs.js', array( 'jquery' ), $this->version, true );
					wp_register_script( 'wm-shortcodes-slideshow',    $this->assets_url . 'js/shortcode-slideshow.js', array( 'jquery' ), $this->version, true );
					wp_register_script( 'wm-shortcodes-isotope',      $this->assets_url . 'js/plugins/jquery.isotope.min.js', array( 'jquery' ), $this->version, true );
					wp_register_script( 'wm-shortcodes-bxslider',     $this->assets_url . 'js/plugins/jquery.bxslider.min.js', array( 'jquery' ), $this->version, true );
					wp_register_script( 'wm-shortcodes-lwtCountdown', $this->assets_url . 'js/plugins/jquery.lwtCountdown.min.js', array( 'jquery' ), $this->version, true );
			} // /assets_register



			/**
			 * Enqueue frontend styles and scripts
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function assets_frontend() {
				//Helper variables
					global $is_IE;
					$icon_font_url = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'iconfont_url', get_option( 'wmamp-icon-font' ) );

				//Styles
					if ( $icon_font_url ) {
						wp_enqueue_style( 'wm-fonticons' );
					}
					if ( apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'enqueue_shortcode_css', true ) ) {
						wp_enqueue_style( 'wm-shortcodes' );
						if ( is_rtl() ) {
							wp_enqueue_style( 'wm-shortcodes-rtl' );
						}
					}

				//Scripts
					if ( $is_IE && apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'enqueue_shortcode_ie_script', true ) ) {
						wp_enqueue_script( 'wm-shortcodes-ie' );
					}
			} // /assets_frontend



			/**
			 * Enqueue backend (admin) styles and scripts
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function assets_backend() {
				if ( ! current_user_can( $this->generator_capability ) ) {
					return;
				}
				//Helper variables
					global $pagenow, $post_type;
					$icon_font_url = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'iconfont_url', get_option( 'wmamp-icon-font' ) );

				//Styles
					if ( $icon_font_url ) {
						wp_enqueue_style( 'wm-fonticons' );
					}

				//Shortcode generator
				$admin_pages = array( 'post.php', 'post-new.php' );
				if (
						in_array( $pagenow, apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'generator_admin_pages', $admin_pages ) )
						&& ! empty( self::$codes['generator'] )
					) {
					//Helper variables
						$shortcodes_js_array = (array) self::$codes['generator'];
						ksort( $shortcodes_js_array );
						$shortcodes_js_array = array_values( $shortcodes_js_array );

					//Styles
						wp_enqueue_style( 'wm-shortcodes-generator' );
						if ( is_rtl() ) {
							wp_enqueue_style( 'wm-shortcodes-generator-rtl' );
						}

					//Scripts
						wp_localize_script( 'jquery', 'wmShortcodesArray', $shortcodes_js_array );
						wp_localize_script( 'jquery', 'wmShortcodesAssetsURL', $this->assets_url );
				}

				//Visual Composer plugin integration
				$vc_supported_post_types = ( get_option( 'wpb_js_content_types' ) ) ? ( (array) get_option( 'wpb_js_content_types' ) ) : ( array( 'page' ) );
				if (
						in_array( $pagenow, apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'generator_vc_admin_pages', $admin_pages ) )
						&& class_exists( 'WPBakeryVisualComposer' )
						&& in_array( $post_type, $vc_supported_post_types )
						&& ! empty( self::$codes['vc_generator'] )
					) {
					//Helper variables
						$shortcodes_js_array = (array) self::$codes['vc_generator'];
						ksort( $shortcodes_js_array );
						$shortcodes_js_array = array_values( $shortcodes_js_array );

					//Styles
						wp_enqueue_style( 'wm-shortcodes-vc-addon' );
						if ( is_rtl() ) {
							wp_enqueue_style( 'wm-shortcodes-vc-addon-rtl' );
						}

					//Scripts
						wp_enqueue_script( 'wm-shortcodes-vc-addon' );
						wp_localize_script( 'jquery', 'wmShortcodesArrayVC', $shortcodes_js_array );
				}
			} // /assets_backend



			/**
			 * Setup filter hooks
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function setup_filters() {
				//Assets
					add_filter( 'wp_enqueue_scripts', array( $this, 'assets_frontend' ) );
					add_filter( 'admin_enqueue_scripts', array( $this, 'assets_backend' ) );

				//Shortcodes in text widget
					add_filter( 'widget_text', 'do_shortcode' );

				//Preprocess certain shortcodes
					add_filter( 'the_content', array( $this, 'preprocess_shortcodes' ), 7 );
					add_filter( 'wmhook_content_filters', array( $this, 'preprocess_shortcodes' ), 7 );
					add_filter( 'widget_text', array( $this, 'preprocess_shortcodes' ), 7 );

				//Fixes HTML issues created by wpautop
					add_filter( 'the_content', array( $this, 'fix_shortcodes' ) );

				//TinyMCE customization
					if (
							is_admin()
							&& 'true' == get_user_option( 'rich_editing' )
							&& current_user_can( $this->generator_capability )
						) {
						//Shortcode Generator
							if ( ! empty( self::$codes['generator'] ) ) {
								add_filter( 'mce_external_plugins', array( $this, 'add_mce_plugin' ) );
								add_filter( 'mce_buttons', array( $this, 'mce_buttons_row1' ) );
							}
						//Styles dropdown button
							if ( ! empty( self::$codes['styles'] ) ) {
								add_filter( 'tiny_mce_before_init', array( $this, 'custom_mce_styles' ) );
								add_filter( 'mce_buttons_2', array( $this, 'mce_buttons_row2' ) );
							}
					}
			} // /setup_filters



			/**
			 * Register shortcodes
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array shortcodes
			 */
			public function add_shortcodes( $shortcodes = array() ) {
				//If no shortcodes array set, use global shortcodes only
					if ( empty( $shortcodes ) ) {
						$shortcodes = (array) self::$codes['global'];
					}

				//If still no shortcodes to register, don't run the add_shortcode() function
					if ( ! empty( $shortcodes ) ) {
						foreach ( $shortcodes as $shortcode ) {
							add_shortcode( $this->prefix_shortcode . $shortcode, array( $this, 'shortcode_render' ) );
						}
					}
			} // /add_shortcodes





		/**
		 * SHORTCODES OUTPUT MANIPULATION (FIXES)
		 */

			/**
			 * Content fixes for shortcodes
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   string content Post/page content.
			 */
			public function fix_shortcodes( $content = '' ) {
				$fix = array(
					'<p>['    => '[',
					']</p>'   => ']',
					']<br />' => ']',
					']<br>'   => ']'
				);
				$content = strtr( $content, $fix );

				return $content;
			} // /fix_shortcodes



			/**
			 * Preprocess shortcodes to prevent HTML mismatch
			 *
			 * Preprocessing shortcodes that use inline HTML tags prevent mess
			 * with <p> tags openings and closings.
			 * These shortcodes will be processed also normally (outside preprocessing)
			 * to retain compatibility with do_shortcode() (in sliders for example).
			 * Surely, if the shortcode was applied in preprocess, it shouldn't appear
			 * again in the content when processing shortcodes normally.
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   string content Post/page content.
			 */
			public function preprocess_shortcodes( $content = '' ) {
				//If there is no shortcode to preprocess, do nothing
					if ( empty( self::$codes['preprocess'] ) ) {
						return $content;
					}

				//Variables
					global $shortcode_tags;
					$codes = (array) self::$codes['preprocess'];

				//Backup current registered shortcodes and clear them all out
					$shortcodesBackup = $shortcode_tags;
					remove_all_shortcodes();

				//Register shortcodes in preprocessing
					call_user_func_array( array( $this, 'add_shortcodes' ), array( $codes ) );

				//Do the preprocess shortcodes prematurely (in WordPress standards)
					$content = do_shortcode( $content );

				//Put the original shortcodes back
					$shortcode_tags = $shortcodesBackup;

				//Output
					return $content;
			} // /preprocess_shortcodes





		/**
		 * WORDPRESS VISUAL EDITOR INTEGRATION
		 */

			/**
			 * Register custom visual editor styles (for "Style" dropdown button)
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array init TiniMCE initialization settings.
			 */
			public function custom_mce_styles( $init = array() ) {
				$init['style_formats'] = json_encode( (array) self::$codes['styles'] );

				return $init;
			} // /custom_mce_styles



			/**
			 * Register custom visual editor plugin
			 *
			 * Creates Shortcode Generator dropdown button.
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array plugin_array TiniMCE plugins array.
			 */
			public function add_mce_plugin( $plugin_array = array() ) {
				$plugin_array['wmShortcodes'] =	$this->assets_url . 'js/shortcodes-button.js';

				return $plugin_array;
			} // /add_mce_plugin



			/**
			 * Add visual editor buttons to 1st row
			 *
			 * Adds Shortcode Generator dropdown button into first row
			 * of visual editor buttons.
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array buttons TinyMCE array of first row of buttons.
			 */
			public function mce_buttons_row1( $buttons = array() ) {
				//Inserting buttons before "content_wp_adv" button
					$pos = array_search( 'wp_adv', $buttons, true );
					if ( $pos != false ) {
						$add = array_slice( $buttons, 0, $pos );
						$add[] = '|';
						$add[] = 'wm_shortcodes_list';
						if ( class_exists( 'WPBakeryVisualComposer' ) ) {
							$add[] = 'wm_shortcodes_list_vc';
						}
						$add[] = '|';
						$add[] = 'wp_adv';
						$buttons = array_merge( $add, array_slice( $buttons, $pos + 1 ) );
					}

				return $buttons;
			} // /mce_buttons_row1



			/**
			 * Add visual editor buttons to 2nd row
			 *
			 * Adds Styles dropdown button into second row of visual editor buttons.
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array buttons TinyMCE array of second row of buttons.
			 */
			public function mce_buttons_row2( $buttons = array() ) {
				//Inserting buttons before "underline" button
					$pos = array_search( 'underline', $buttons, true );
					if ( $pos != false ) {
						$add = array_slice( $buttons, 0, $pos );
						$add[] = 'styleselect';
						$add[] = '|';
						$add[] = 'removeformat';
						$add[] = '|';
						$buttons = array_merge( $add, array_slice( $buttons, $pos + 1 ) );
					}

				return $buttons;
			} // /mce_buttons_row2





		/**
		 * VISUAL COMPOSER PLUGIN ADDONS
		 */

			/**
			 * Add Visual Composer plugin support
			 *
			 * http://vc.wpbakery.com/
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function visual_composer_support() {
				//VC additional shortcodes admin interface
					$vc_shortcodes_admin_tweaks = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_shortcodes_admin_tweaks_file', $this->vc_addons_dir . 'shortcodes_admin.php' );
					require_once( $vc_shortcodes_admin_tweaks );

				//VC setup screen modifications
					add_filter( 'vc_settings_tabs', array( $this, 'visual_composer_setup' ) );
					delete_option( 'wpb_js_use_custom' );

				//Filter to replace default css class for vc_row shortcode and vc_column
					add_filter( 'vc_shortcodes_css_class', array( $this, 'visual_composer_classes' ), 10, 2 );

				//VC extending shortcode parameters
					add_shortcode_param( 'wm_radio', array( $this, 'visual_composer_custom_parameter_radio' ), $this->assets_url . 'js/shortcodes-vc-addons-attscripts.js' );
					add_shortcode_param( 'wm_hidden', array( $this, 'visual_composer_custom_parameter_hidden' ) );

				//Remove default VC elements (only if current theme supports this)
					if (
							function_exists( 'vc_remove_element' )
							&& in_array( 'remove_vc_buttons', wm_current_theme_supports_subfeatures( 'webman-shortcodes' ) )
							&& class_exists( 'WPBMap' )
						) {
						$vc_shortcodes_all    = array_keys( WPBMap::getShortCodes() );
						$vc_shortcodes_keep   = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_keep', array(
								//rows
									'vc_row',
									'vc_row_inner',
								//columns
									'vc_column',
									'vc_column_inner',
								//others
									'vc_raw_html',
									'vc_raw_js',
								//3rd party plugins support (check http://vc.wpbakery.com/features/content-elements/)
									'contact-form-7',
									'gravityform',
									'layerslider_vc',
									'rev_slider_vc',
							) );
						$vc_shortcodes_remove = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_remove', array_diff( $vc_shortcodes_all, $vc_shortcodes_keep ) );

						//Array check required due to filter applied above
							if ( is_array( $vc_shortcodes_remove ) && ! empty( $vc_shortcodes_remove ) ) {
								foreach ( $vc_shortcodes_remove as $shortcode ) {
									vc_remove_element( $shortcode );
								}
							}
					}

				//Add custom VC elements
					if ( function_exists( 'vc_map' ) && ! empty( self::$codes['vc_plugin'] ) ) {
						ksort( self::$codes['vc_plugin'] );
						foreach ( self::$codes['vc_plugin'] as $shortcode ) {
							//simple validation (as of http://kb.wpbakery.com/index.php?title=Vc_map, the below 2 parameters are required)
								if ( ! isset( $shortcode['name'] ) || ! isset( $shortcode['base'] ) ) {
									continue;
								}
							vc_map( $shortcode );
						}
					}
			} // /visual_composer_support



			/**
			 * Modify Visual Composer plugin settings page
			 *
			 * http://vc.wpbakery.com/
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array tabs
			 */
			public function visual_composer_setup( $tabs = array() ) {
				unset( $tabs['color'] );
				unset( $tabs['element_css'] );
				unset( $tabs['custom_css'] );

				return $tabs;
			} // /visual_composer_setup



			/**
			 * Visual Composer plugin column classes (to match the "column" shortcode)
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   string classes
			 * @param   string shortcode
			 */
			function visual_composer_classes( $classes, $shortcode ) {
				//Helper variables
					$replacements = false;

				//Row shortcode
					if ( 'vc_row' == $shortcode || 'vc_row_inner' == $shortcode ) {
						$replacements = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_row_classes', array(
								'wpb_row'      => 'wm-row clearfix',
								'vc_row-fluid' => 'fluid',
							) );
					}

				//Column shortcode
					if ( 'vc_column' == $shortcode || 'vc_column_inner' == $shortcode ) {
						$replacements = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_column_classes', array(
								'wpb_column'       => 'wm-column',
								'column_container' => '',
								'vc_span2'         => 'width-1-6',
								'vc_span3'         => 'width-1-4',
								'vc_span4'         => 'width-1-3',
								'vc_span6'         => 'width-1-2',
								'vc_span8'         => 'width-2-3',
								'vc_span9'         => 'width-3-4',
								'vc_span10'        => 'width-5-6',
								'vc_span12'        => 'width-1-1',
							) );
					}

				//Edit classes
					if ( is_array( $replacements ) && ! empty( $replacements ) ) {
						$classes = str_replace( array_keys( $replacements ), $replacements, $classes );
					}

				//Return edited classes
					return $classes;
			} // /visual_composer_classes



			/**
			 * Visual Composer custom shortcode parameter - radio buttons
			 *
			 * @link    http://kb.wpbakery.com/index.php?title=Visual_Composer_Tutorial_Create_New_Param
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array $settings Array of settings parameters
			 * @param   string $value
			 */
			function visual_composer_custom_parameter_radio( $settings, $value ) {
				//Helper variables
					$block_class = '';
					$dependency  = vc_generate_dependencies_attributes( $settings );

				//Set $settings defaults
					if ( ! isset( $settings['custom'] ) || ! $settings['custom'] ) {
						$settings['custom'] = '';
					} else {
						$block_class .= ' custom-label';
					}
					$settings['hide-radio'] = ( isset( $settings['hide-radio'] ) && $settings['hide-radio'] ) ? ( ' hide' ) : ( '' );
					$settings['inline'] = ( isset( $settings['inline'] ) && $settings['inline'] ) ? ( true ) : ( false );

				//Prepare output
					$output = '<div class="wm-radio-block' . $block_class . '">';
						$i = 0;
						foreach ( $settings['value'] as $option_value => $option ) {
							$i++;
							$checked = trim( checked( $value, $option_value, false ) . ' /' );

							$output .= ( ! $settings['inline'] ) ? ( '<p class="input-item">' ) : ( '<span class="inline-radio input-item">' );
							if ( ! trim( $settings['custom'] ) ) {
								$output .= '<input type="radio" name="' . $settings['param_name'] . '" id="' . $settings['param_name'] . '-' . $i . '" value="' . $option_value . '" title="' . esc_attr( $option ) . '" class="wpb_vc_param_value fieldtype-radio wm-radio ' . $settings['type'] . '_field" ' . $checked . '> ';
								$output .= '<label for="' . $settings['param_name'] . '-' . $i . '">' . $option . '</label>';
							} else {
								$output .= '<label for="' . $settings['param_name'] . '-' . $i . '">' . trim( str_replace( array( '{{value}}', '{{name}}' ), array( $option_value, $option ), $settings['custom'] ) ) . '</label>';
								$output .= '<input type="radio" name="' . $settings['param_name'] . '" id="' . $settings['param_name'] . '-' . $i . '" value="' . $option_value . '" title="' . esc_attr( $option ) . '" class="wpb_vc_param_value fieldtype-radio wm-radio ' . $settings['type'] . '_field' . $settings['hide-radio'] . '" ' . $checked . '>';
							}
							$output .= ( ! $settings['inline'] ) ? ( '</p>' ) : ( '</span> ' );
						}
					$output .= '</div>';

				//Output
					return $output;
			} // /visual_composer_custom_parameter_radio



			/**
			 * Visual Composer custom shortcode parameter - hidden field
			 *
			 * @link    http://kb.wpbakery.com/index.php?title=Visual_Composer_Tutorial_Create_New_Param
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array $settings Array of settings parameters
			 * @param   string $value
			 */
			function visual_composer_custom_parameter_hidden( $settings, $value ) {
				//Helper variables
					$dependency = vc_generate_dependencies_attributes( $settings );

				//Output
					return '<input type="hidden" name="' . $settings['param_name'] . '" value="' . $settings['value'] . '" class="wpb_vc_param_value fieldtype-hidden wm-hidden ' . $settings['type'] . '_field" />';
			} // /visual_composer_custom_parameter_hidden





		/**
		 * SHORTCODES RENDERER
		 */

			/**
			 * Shortcode renderer method
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array atts Shortcode attributes.
			 * @param   string content Content of the shortcode.
			 * @param   string shortcode WordPress passes also the name of the shortcode here.
			 *
			 * @return  string HTML output of the shortcode.
			 */
			public function shortcode_render( $atts = array(), $content = '', $shortcode = '' ) {
				$shortcode = trim( str_replace( $this->prefix_shortcode, '', $shortcode ) );
				if ( ! $shortcode ) {
					return;
				}

				//Allow plugins/themes to override the default shortcode template
					$codes_globals = self::$codes_globals;
					$output        = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode, '', $atts, $content, $codes_globals );
					if ( $output ) {
						return $output;
					}

				//Render the shortcode
					$renderer_file_path = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_renderer_path', $this->renderers_dir . $shortcode . '.php' );
					if ( file_exists( $renderer_file_path ) ) {
						include( $renderer_file_path );
					}

				//Output
					//general filter to process the output of all shortcodes
					$output = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'output', $output );
					//filter to process the specific shortcode output ($atts are validated already)
					return apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_output', $output, $atts );
			} // /shortcode_render

	} // /WM_Shortcodes

} // /class WM_Shortcodes check

?>