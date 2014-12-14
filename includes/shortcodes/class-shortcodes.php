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
 * @version     1.0.9.11
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
			 * @since    1.0
			 * @version  1.0.8
			 * @access   public
			 */
			public function __construct() {
				$this->setup_globals();
				$this->assets_register();
				$this->setup_filters();
				$this->add_shortcodes();

				//Visual Composer plugin integration
					if ( wma_is_active_vc() ) {
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
			 * @since    1.0
			 * @version  1.0.9.15
			 * @access   private
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
					$post_types = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'post_types', $post_types );
					asort( $post_types );

				//Shortcode generator user capability
					$this->generator_capability = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'generator_capability', 'edit_posts' );

				//Paths and URLs
					$this->assets_url      = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'assets_url',      WMAMP_ASSETS_URL );
					$this->definitions_dir = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'definitions_dir', trailingslashit( WMAMP_INCLUDES_DIR . 'shortcodes/definitions' ) );
					$this->renderers_dir   = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'renderers_dir',   trailingslashit( WMAMP_INCLUDES_DIR . 'shortcodes/renderers' ) );
					$this->vc_addons_dir   = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_addons_dir',   trailingslashit( WMAMP_INCLUDES_DIR . 'shortcodes/vc_addons' ) );

				//Visual Composer integration
					if ( ! ( wma_supports_subfeature( 'remove_vc_shortcodes' ) || wma_supports_subfeature( 'remove-vc-shortcodes' ) ) ) {
						$this->prefix_shortcode_name = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'prefix_name', 'WM ' );
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
					$definitions_file_path = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'definitions_path', $this->definitions_dir . 'definitions.php' );
					if ( file_exists( $definitions_file_path ) ) {
						include_once( $definitions_file_path );
					}

				//Define the shortcodes
					$codes = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'definitions', $shortcode_definitions );

				//Empty self::$codes variable before processing
					self::$codes = array(
							'generator'    => array(),
							'global'       => array(),
							'preprocess'   => array(),
							'renderer'     => array(),
							'styles'       => array(),
							'vc_generator' => array(),
							'vc_plugin'    => array()
						);

				//Separate shortcodes into groups
					foreach ( (array) $codes as $code => $definition ) {
						$prefix_shortcode = $this->prefix_shortcode;

						//Skip the shortcode if the required post type is not supported in the theme
							if ( isset( $definition['post_type_required'] ) && trim( $definition['post_type_required'] ) && ! in_array( $definition['post_type_required'], $postTypes ) ) {
								continue;
							}

						//Check if shortcode supported by the theme
							if (
									isset( $definition['since'] )
									&& $definition['since']
									&& version_compare( apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'supported_version', WMAMP_VERSION ), $definition['since'], '<' )
								) {
								continue;
							}

						//All shortcodes will be processed globally (except [pre] and [raw])
							if ( ! in_array( $code, array( 'pre', 'raw' ) ) ) {
								if (
										array_key_exists( 'custom_prefix', $definition )
										&& isset( $definition['custom_prefix'] )
									) {
									self::$codes['global'][] = array(
											'name'          => $code,
											'custom_prefix' => $definition['custom_prefix'],
										);
									$prefix_shortcode = $definition['custom_prefix'];
								} else {
									self::$codes['global'][] = $code;
								}
							}

						//Select only shortcodes that need preprocessing
							if ( isset( $definition['preprocess'] ) && $definition['preprocess'] ) {
								self::$codes['preprocess'][] = $code;
							}

						//Rendering overrides
							if ( isset( $definition['renderer'] ) && $definition['renderer'] ) {
								self::$codes['renderer'][ $code ] = $definition['renderer'];

								if (
										array_key_exists( 'custom_prefix', $definition )
										&& isset( $definition['custom_prefix'] )
									) {
									self::$codes['renderer'][ $code ]['custom_prefix'] = $definition['custom_prefix'];
								}
							}

						//Setting Shortcode Generator setup
							if (
									isset( $definition['generator'] ) && $definition['generator']
									&& isset( $definition['generator']['name'] )
									&& isset( $definition['generator']['code'] )
								) {
								$definition['generator']['class'] = 'generator_item_' . $code;
								$definition['generator']['code']  = str_replace( 'PREFIX_', $prefix_shortcode, $definition['generator']['code'] );
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
			 * @since    1.0
			 * @version  1.0.9.11
			 * @access   public
			 */
			public function assets_register() {
				//Helper variables
					$icon_font_url = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'iconfont_url', get_option( 'wmamp-icon-font' ) );
					$rtl           = ( is_rtl() ) ? ( '.rtl' ) : ( '' );

				//Styles
					wp_register_style( 'wm-shortcodes',               $this->assets_url . 'css/shortcodes.css',               array(), $this->version, 'screen' );
					wp_register_style( 'wm-shortcodes-generator',     $this->assets_url . 'css/shortcodes-generator.css',     array(), $this->version, 'screen' );
					wp_register_style( 'wm-shortcodes-generator-rtl', $this->assets_url . 'css/rtl-shortcodes-generator.css', array(), $this->version, 'screen' );
					wp_register_style( 'wm-shortcodes-rtl',           $this->assets_url . 'css/rtl-shortcodes.css',           array(), $this->version, 'screen' );
					wp_register_style( 'wm-shortcodes-vc-addon',      $this->assets_url . 'css/shortcodes-vc-addons.css',     array(), $this->version, 'screen' );
					wp_register_style( 'wm-shortcodes-vc-addon-old',  $this->assets_url . 'css/shortcodes-vc-addons-old.css', array(), $this->version, 'screen' );
					wp_register_style( 'wm-shortcodes-vc-addon-rtl',  $this->assets_url . 'css/rtl-shortcodes-vc-addons.css', array(), $this->version, 'screen' );
					if ( $icon_font_url ) {
						wp_register_style( 'wm-fonticons', $icon_font_url, array(), $this->version, 'screen' );
					}

				//Scripts
					wp_register_script( 'wm-imagesloaded',            $this->assets_url . 'js/plugins/imagesloaded.min.js',             array(),                                 $this->version, true );
					wp_register_script( 'wm-isotope',                 $this->assets_url . 'js/plugins/isotope.pkgd.min.js',             array(),                                 $this->version, true );
					wp_register_script( 'wm-jquery-bxslider',         $this->assets_url . 'js/plugins/jquery.bxslider.min.js',          array( 'jquery' ),                       $this->version, true );
					wp_register_script( 'wm-jquery-lwtCountdown',     $this->assets_url . 'js/plugins/jquery.lwtCountdown.min.js',      array( 'jquery' ),                       $this->version, true );
					wp_register_script( 'wm-jquery-owl-carousel',     $this->assets_url . 'js/plugins/owl.carousel' . $rtl . '.min.js', array( 'jquery' ),                       $this->version, true );
					wp_register_script( 'wm-jquery-parallax',         $this->assets_url . 'js/plugins/jquery.parallax.min.js',          array( 'jquery' ),                       $this->version, true );
					wp_register_script( 'wm-shortcodes-accordion',    $this->assets_url . 'js/shortcode-accordion.js',                  array( 'jquery' ),                       $this->version, true );
					wp_register_script( 'wm-shortcodes-ie',           $this->assets_url . 'js/shortcodes-ie.js',                        array( 'jquery' ),                       $this->version, true );
					wp_register_script( 'wm-shortcodes-parallax',     $this->assets_url . 'js/shortcode-parallax.js',                   array( 'jquery', 'wm-jquery-parallax' ), $this->version, true );
					wp_register_script( 'wm-shortcodes-posts',        $this->assets_url . 'js/shortcode-posts.js',                      array( 'jquery', 'wm-imagesloaded' ),    $this->version, true );
					wp_register_script( 'wm-shortcodes-slideshow',    $this->assets_url . 'js/shortcode-slideshow.js',                  array( 'jquery' ),                       $this->version, true );
					wp_register_script( 'wm-shortcodes-tabs',         $this->assets_url . 'js/shortcode-tabs.js',                       array( 'jquery' ),                       $this->version, true );
					wp_register_script( 'wm-shortcodes-vc-addon',     $this->assets_url . 'js/shortcodes-vc-addons.js',                 array( 'wpb_js_composer_js_atts', 'wpb_js_composer_js_custom_views', 'isotope' ), $this->version, true );
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
			 * @since    1.0
			 * @version  1.0.9.5
			 * @access   public
			 */
			public function assets_backend() {
				//Requirements check
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
					}

				//Visual Composer plugin integration
					$vc_supported_post_types = ( get_option( 'wpb_js_content_types' ) ) ? ( (array) get_option( 'wpb_js_content_types' ) ) : ( array( 'page' ) );
					if (
							in_array( $pagenow, apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'generator_vc_admin_pages', $admin_pages ) )
							&& wma_is_active_vc()
							&& in_array( $post_type, $vc_supported_post_types )
							&& ! empty( self::$codes['vc_generator'] )
							&& defined( 'WPB_VC_VERSION' )
						) {
						//Helper variables
							$shortcodes_js_array = (array) self::$codes['vc_generator'];
							ksort( $shortcodes_js_array );
							$shortcodes_js_array = array_values( $shortcodes_js_array );

						//Styles
							if ( version_compare( WPB_VC_VERSION, '4.3', '<' ) ) {
								wp_enqueue_style( 'wm-shortcodes-vc-addon-old' );
							} else {
								wp_enqueue_style( 'wm-shortcodes-vc-addon' );
							}
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
					add_filter( 'the_content',            array( $this, 'preprocess_shortcodes' ), 7 );
					add_filter( 'wmhook_content_filters', array( $this, 'preprocess_shortcodes' ), 7 );
					add_filter( 'widget_text',            array( $this, 'preprocess_shortcodes' ), 7 );

				//Fixes HTML issues created by wpautop
					add_filter( 'the_content', array( $this, 'fix_shortcodes' ) );

				//Shortcodes' $content variable filtering
					add_filter( WM_SHORTCODES_HOOK_PREFIX . '_content', array( $this, 'shortcodes_content' ), 20, 2 );
					add_filter( WM_SHORTCODES_HOOK_PREFIX . 'pre' . '_content', array( $this, 'shortcodes_content_pre' ), 10 );
					add_filter( WM_SHORTCODES_HOOK_PREFIX . 'list' . '_content', 'shortcode_unautop', 10 );

				//Shortcodes' output filtering
					add_filter( WM_SHORTCODES_HOOK_PREFIX . 'widget_area' . '_output', 'wma_minify_html', 10 );

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
			 * @param   array $shortcodes
			 */
			public function add_shortcodes( $shortcodes = array() ) {
				//If no shortcodes array set, use global shortcodes only
					if ( empty( $shortcodes ) ) {
						$shortcodes = (array) self::$codes['global'];
					}

				//If still no shortcodes to register, don't run the add_shortcode() function
					if ( ! empty( $shortcodes ) ) {
						foreach ( $shortcodes as $shortcode ) {
							//Helper variables
								$prefix_shortcode = $this->prefix_shortcode;

							//Modifying $prefix_shortcode if set
								if (
										is_array( $shortcode )
										&& array_key_exists( 'custom_prefix', $shortcode )
										&& isset( $shortcode['custom_prefix'] )
									) {
									$prefix_shortcode = $shortcode['custom_prefix'];
								}

							//Setting shortcode name
								if (
										is_array( $shortcode )
										&& array_key_exists( 'name', $shortcode )
										&& isset( $shortcode['name'] )
									) {
									$shortcode = $shortcode['name'];
								}

							add_shortcode( $prefix_shortcode . $shortcode, array( $this, 'shortcode_render' ) );
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
			 * @param   string $content Post/page content.
			 */
			public function fix_shortcodes( $content = '' ) {
				$fix = array(
					'<p>['    => '[',
					']</p>'   => ']',
					']<br />' => ']',
					']<br>'   => ']'
				);
				$content = strtr( $content, $fix );

				return apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'fix_shortcodes' . '_output', $content );
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
			 * @since    1.0
			 * @version  1.0.9.6
			 * @access   public
			 *
			 * @param   string $content Post/page content.
			 */
			public function preprocess_shortcodes( $content = '' ) {
				//Helper variables
					$codes = (array) apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'preprocess_shortcodes_array', self::$codes['preprocess'] );

				//If there is no shortcode to preprocess, do nothing
					if ( empty( $codes ) ) {
						return $content;
					}

				//Variables
					global $shortcode_tags;

				//Backup current registered shortcodes and clear them all out
					$shortcodesBackup = $shortcode_tags;
					remove_all_shortcodes();

				//Register shortcodes in preprocessing
					call_user_func_array( array( $this, 'add_shortcodes' ), array( $codes ) );

					do_action( WM_SHORTCODES_HOOK_PREFIX . 'preprocess_shortcodes', $codes, $content );

				//Do the preprocess shortcodes prematurely (in WordPress standards)
					$content = do_shortcode( $content );

				//Put the original shortcodes back
					$shortcode_tags = $shortcodesBackup;

				//Output
					return apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'preprocess_shortcodes' . '_output', $content );
			} // /preprocess_shortcodes





		/**
		 * SHORTCODES' $content VARIABLE PROCESSING
		 */

			/**
			 * General processing
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   string $content
			 * @param   string $shortcode
			 */
			public function shortcodes_content( $content = '', $shortcode = '' ) {
				//Requirements check
					if (
							empty( $content )
							|| empty( $shortcode )
						) {
						return $content;
					}

				//Helper variables
					$codes = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'shortcodes_content_codes', array(
							'do_shortcode'       => array(
									'accordion',
									'button',
									'call_to_action',
									'column',
									'content_module',
									'item',
									'list',
									'marker',
									'message',
									'posts',
									'price',
									'pricing_table',
									'progress',
									'pullquote',
									'row',
									'separator_heading',
									'tabs',
									//Visual Composer support
									'vc_row',
									'vc_row_inner',
									'vc_column',
									'vc_column_inner',
								),
							'strip_tags_inline'  => array(
									'progress',
								),
							'wpautop_no_br'      => array(
									'item',
								),
							'wpautop_shortcodes' => array(
									'text_block',
								),
						) );

				//Preparing output
					//do_shortcode()
						if (
								isset( $codes['do_shortcode'] )
								&& is_array( $codes['do_shortcode'] )
								&& ! empty( $codes['do_shortcode'] )
								&& in_array( $shortcode, $codes['do_shortcode'] )
							) {
							$content = do_shortcode( $content );
						}

					//strip_tags() (allow inline tags)
						if (
								isset( $codes['strip_tags_inline'] )
								&& is_array( $codes['strip_tags_inline'] )
								&& ! empty( $codes['strip_tags_inline'] )
								&& in_array( $shortcode, $codes['strip_tags_inline'] )
							) {
							$content = strip_tags( $content, $this->inline_tags );
						}

					//wpautop() (no <br /> tags)
						if (
								isset( $codes['wpautop_no_br'] )
								&& is_array( $codes['wpautop_no_br'] )
								&& ! empty( $codes['wpautop_no_br'] )
								&& in_array( $shortcode, $codes['wpautop_no_br'] )
							) {
							$content = wpautop( $content, false );
						}

					//correct shortcodes in wpautop()
						if (
								isset( $codes['wpautop_shortcodes'] )
								&& is_array( $codes['wpautop_shortcodes'] )
								&& ! empty( $codes['wpautop_shortcodes'] )
								&& in_array( $shortcode, $codes['wpautop_shortcodes'] )
							) {
							$content = wpautop( preg_replace( '/<\/?p\>/', "\r\n", $content ) . "\r\n" );
							$content = do_shortcode( shortcode_unautop( $content ) );
						}

				//Output
					return $content;
			} // /shortcodes_content



			/**
			 * [pre] shortcode
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   string $content
			 */
			public function shortcodes_content_pre( $content = '' ) {
				//Requirements check
					if ( empty( $content ) ) {
						return $content;
					}

				//Preparing output
						$content = str_replace( '[', '&#91;', $content );
						$content = str_replace( array( '<p>', '</p>', '<br />' ), '', $content );
						$content = esc_html( shortcode_unautop( $content ) );

				//Output
					return $content;
			} // /shortcodes_content_pre





		/**
		 * WORDPRESS VISUAL EDITOR INTEGRATION
		 */

			/**
			 * Register custom visual editor styles (for "Style" dropdown button)
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array $init TiniMCE initialization settings.
			 */
			public function custom_mce_styles( $init = array() ) {
				$init['style_formats'] = json_encode( (array) self::$codes['styles'] );

				return apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'custom_mce_styles' . '_output', $init );
			} // /custom_mce_styles



			/**
			 * Register custom visual editor plugin
			 *
			 * Creates Shortcode Generator dropdown button.
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array $plugin_array TiniMCE plugins array.
			 */
			public function add_mce_plugin( $plugin_array = array() ) {
				global $wp_version;

				$wp_ver_suffix = ( version_compare( (float) $wp_version, '3.8', '<=' ) ) ? ( '.old' ) : ( '' );

				$plugin_array['wmShortcodes'] =	$this->assets_url . 'js/shortcodes-button' . $wp_ver_suffix . '.js';

				return apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'add_mce_plugin' . '_output', $plugin_array );
			} // /add_mce_plugin



			/**
			 * Add visual editor buttons to 1st row
			 *
			 * Adds Shortcode Generator dropdown button into first row
			 * of visual editor buttons.
			 *
			 * @since    1.0
			 * @version  1.0.8
			 * @access   public
			 *
			 * @param    array $buttons TinyMCE array of first row of buttons.
			 */
			public function mce_buttons_row1( $buttons = array() ) {
				//Inserting buttons before "content_wp_adv" button
					$pos = array_search( 'wp_adv', $buttons, true );
					if ( false !== $pos ) {
						$add = array_slice( $buttons, 0, $pos );
						$add[] = '|';
						$add[] = 'wm_shortcodes_list';
						if ( wma_is_active_vc() ) {
							$add[] = 'wm_shortcodes_list_vc';
						}
						$add[] = '|';
						$add[] = 'wp_adv';
						$buttons = array_merge( $add, array_slice( $buttons, $pos + 1 ) );
					}

				return apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'mce_buttons_row1' . '_output', $buttons );
			} // /mce_buttons_row1



			/**
			 * Add visual editor buttons to 2nd row
			 *
			 * Adds Styles dropdown button into second row of visual editor buttons.
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array $buttons TinyMCE array of second row of buttons.
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

				return apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'mce_buttons_row2' . '_output', $buttons );
			} // /mce_buttons_row2





		/**
		 * VISUAL COMPOSER PLUGIN ADDONS
		 */

			/**
			 * Add Visual Composer plugin support
			 *
			 * http://vc.wpbakery.com/
			 *
			 * @todo     Support for Frontend Editor (VC4+)
			 *
			 * @since    1.0
			 * @version  1.0.9.15
			 * @access   public
			 */
			public function visual_composer_support() {
				//VC 4+ disabling Frontend Editor
					if ( function_exists( 'vc_disable_frontend' ) ) {
						vc_disable_frontend();
					}

				//VC additional shortcodes admin interface
					$vc_shortcodes_admin_tweaks = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_shortcodes_admin_tweaks_file', $this->vc_addons_dir . 'shortcodes-admin.php' );
					require_once( $vc_shortcodes_admin_tweaks );

				//VC setup screen modifications
					add_filter( 'vc_settings_tabs', array( $this, 'visual_composer_setup' ) );
					delete_option( 'wpb_js_use_custom' );

				//VC extending shortcode parameters
					add_shortcode_param( 'wm_radio',  array( $this, 'visual_composer_custom_parameter_radio' ), $this->assets_url . 'js/shortcodes-vc-addons-attscripts.js' );
					add_shortcode_param( 'wm_hidden', array( $this, 'visual_composer_custom_parameter_hidden' ) );
					add_shortcode_param( 'wm_html',   array( $this, 'visual_composer_custom_parameter_html' ) );

				//Remove default VC elements (only if current theme supports this)
					if (
							function_exists( 'vc_remove_element' )
							&& ( wma_supports_subfeature( 'remove_vc_shortcodes' ) || wma_supports_subfeature( 'remove-vc-shortcodes' ) )
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
					if (
							function_exists( 'vc_map' )
							&& ! empty( self::$codes['vc_plugin'] )
						) {
						ksort( self::$codes['vc_plugin'] );
						foreach ( self::$codes['vc_plugin'] as $shortcode ) {
							//simple validation (as of http://kb.wpbakery.com/index.php?title=Vc_map, the below 2 parameters are required)
								if ( ! isset( $shortcode['name'] ) || ! isset( $shortcode['base'] ) ) {
									continue;
								}
							//sort shortcode parameters array
								if ( isset( $shortcode['params'] ) ) {
									ksort( $shortcode['params'] );
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
			 * @param   array $tabs
			 */
			public function visual_composer_setup( $tabs = array() ) {
				$tabs_original = $tabs;

				unset( $tabs['color'] );
				unset( $tabs['element_css'] );
				unset( $tabs['custom_css'] );

				return apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_setup' . '_output', $tabs, $tabs_original );
			} // /visual_composer_setup



			/**
			 * Visual Composer custom shortcode parameter - radio buttons
			 *
			 * @link    http://kb.wpbakery.com/index.php?title=Visual_Composer_Tutorial_Create_New_Param
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array  $settings Array of settings parameters
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
					return apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_custom_parameter_radio' . '_output', $output );
			} // /visual_composer_custom_parameter_radio



			/**
			 * Visual Composer custom shortcode parameter - hidden field
			 *
			 * @link    http://kb.wpbakery.com/index.php?title=Visual_Composer_Tutorial_Create_New_Param
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array  $settings Array of settings parameters
			 * @param   string $value
			 */
			function visual_composer_custom_parameter_hidden( $settings, $value ) {
				//Helper variables
					$dependency = vc_generate_dependencies_attributes( $settings );

				//Output
					return apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_custom_parameter_hidden' . '_output', '<input type="hidden" name="' . $settings['param_name'] . '" value="' . $settings['value'] . '" class="wpb_vc_param_value fieldtype-hidden wm-hidden ' . $settings['type'] . '_field" />' );
			} // /visual_composer_custom_parameter_hidden



			/**
			 * Visual Composer custom shortcode parameter - custom HTML field
			 *
			 * @link    http://kb.wpbakery.com/index.php?title=Visual_Composer_Tutorial_Create_New_Param
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array  $settings Array of settings parameters
			 * @param   string $value
			 */
			function visual_composer_custom_parameter_html( $settings, $value ) {
				//Helper variables
					$dependency = vc_generate_dependencies_attributes( $settings );

				//Output
					return apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'vc_custom_parameter_html' . '_output', $settings['value'] );
			} // /visual_composer_custom_parameter_html





		/**
		 * SHORTCODES RENDERER
		 */

			/**
			 * Shortcode renderer method
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @param   array  $atts      Shortcode attributes.
			 * @param   string $content   Content of the shortcode.
			 * @param   string $shortcode WordPress passes also the name of the shortcode here.
			 *
			 * @return  string HTML output of the shortcode.
			 */
			public function shortcode_render( $atts = array(), $content = '', $shortcode = '' ) {
				$prefix_shortcode = $this->prefix_shortcode;

				$shortcode = trim( str_replace( $prefix_shortcode, '', $shortcode ) );
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
					//Renderer overrides (can be used for shortcode aliases -> see definitions.php)
						$renderer_file_path = $this->renderers_dir . $shortcode . '.php';

						if (
								isset( self::$codes['renderer'][ $shortcode ] )
								&& self::$codes['renderer'][ $shortcode ]
							) {

							//Setting alias shortcode renderer
								if (
									isset( self::$codes['renderer'][ $shortcode ]['alias'] )
									&& self::$codes['renderer'][ $shortcode ]['alias']
								) {
									$renderer_file_path = $this->renderers_dir . trim( self::$codes['renderer'][ $shortcode ]['alias'] ) . '.php';
								}

							//Setting custom renderer file
								if (
									isset( self::$codes['renderer'][ $shortcode ]['path'] )
									&& self::$codes['renderer'][ $shortcode ]['path']
								) {
									$renderer_file_path = trim( self::$codes['renderer'][ $shortcode ]['path'] );
								}

							//Setting custom shortcode prefix
								if (
										array_key_exists( 'custom_prefix', self::$codes['renderer'][ $shortcode ] )
										&& isset( self::$codes['renderer'][ $shortcode ]['custom_prefix'] )
									) {
									$prefix_shortcode = trim( self::$codes['renderer'][ $shortcode ]['custom_prefix'] );
								}
						}

					$renderer_file_path = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'renderer_path', $renderer_file_path, $shortcode );

					if ( file_exists( $renderer_file_path ) ) {
						include( $renderer_file_path );
					}

				//Output
					//filter to process the output of shortcodes
					$output = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'output', $output, $shortcode, $atts );
					//filter to process the specific shortcode output ($atts are validated already)
					return apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_output', $output, $atts );
			} // /shortcode_render

	} // /WM_Shortcodes

} // /class WM_Shortcodes check





/**
 * Alter Visual Composer shortcodes output
 *
 * @since    1.0
 * @version  1.0.8
 */
if ( wma_is_active_vc() ) {

		/**
		 * Customize vc_row shortcode output
		 *
		 * Making the output the same as wm_row shortcode.
		 *
		 * @link  http://kb.wpbakery.com/index.php?title=Extend_Visual_Composer
		 *
		 * @param  array  $atts
		 * @param  string $content
		 */
		function vc_theme_vc_row( $atts, $content = '', $shortcode = '' ) {
			//Helper variables
				if ( ! $shortcode ) {
					$shortcode = 'vc_row';
				}

			//Allow plugins/themes to override the default shortcode template
				$output = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode, '', $atts, $content );
				if ( $output ) {
					return $output;
				}

			//Render the shortcode
				$renderer_file_dir  = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'renderers_dir', trailingslashit( WMAMP_INCLUDES_DIR . 'shortcodes/renderers' ) );
				$renderer_file_path = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'renderer_path', $renderer_file_dir . 'row.php', $shortcode );
				if ( file_exists( $renderer_file_path ) ) {
					$prefix_shortcode = 'wm_';
					include( $renderer_file_path );
				}

			//Output
				//general filter to process the output of all shortcodes
				$output = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'output', $output, $shortcode, $atts );
				//filter to process the specific shortcode output ($atts are validated already)
				return apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_output', $output, $atts );
		} // /vc_theme_vc_row



		/**
		 * Customize vc_row_inner shortcode output
		 *
		 * Making the output the same as wm_row shortcode.
		 *
		 * @link  http://kb.wpbakery.com/index.php?title=Extend_Visual_Composer
		 *
		 * @param  array  $atts
		 * @param  string $content
		 */
		function vc_theme_vc_row_inner( $atts, $content = '' ) {
			return vc_theme_vc_row( $atts, $content, 'vc_row_inner' );
		} // /vc_theme_vc_row_inner



		/**
		 * Customize vc_column shortcode output
		 *
		 * Making the output the same as wm_column shortcode.
		 *
		 * @link  http://kb.wpbakery.com/index.php?title=Extend_Visual_Composer
		 *
		 * @param  array  $atts
		 * @param  string $content
		 */
		function vc_theme_vc_column( $atts, $content = '', $shortcode = '' ) {
			//Helper variables
				if ( ! $shortcode ) {
					$shortcode = 'vc_column';
				}

			//Allow plugins/themes to override the default shortcode template
				$output = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode, '', $atts, $content );
				if ( $output ) {
					return $output;
				}

			//Render the shortcode
				$renderer_file_dir  = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'renderers_dir', trailingslashit( WMAMP_INCLUDES_DIR . 'shortcodes/renderers' ) );
				$renderer_file_path = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'renderer_path', $renderer_file_dir . 'column.php', $shortcode );
				if ( file_exists( $renderer_file_path ) ) {
					$prefix_shortcode = 'wm_';
					include( $renderer_file_path );
				}

			//Output
				//general filter to process the output of all shortcodes
				$output = apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'output', $output, $shortcode, $atts );
				//filter to process the specific shortcode output ($atts are validated already)
				return apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_output', $output, $atts );
		} // /vc_theme_vc_column



		/**
		 * Customize vc_column_inner shortcode output
		 *
		 * Making the output the same as wm_column shortcode.
		 *
		 * @link  http://kb.wpbakery.com/index.php?title=Extend_Visual_Composer
		 *
		 * @param  array  $atts
		 * @param  string $content
		 */
		function vc_theme_vc_column_inner( $atts, $content = '' ) {
			return vc_theme_vc_column( $atts, $content, 'vc_column_inner' );
		} // /vc_theme_vc_column_inner

} // /wma_is_active_vc() check





/**
 * WM_Shortcodes helper functions
 *
 * @since  1.0.9.8
 */

	/**
	 * Shortcode enqueue scripts
	 *
	 * @since  1.0.9.8
	 *
	 * @param  string $shortcode
	 * @param  array  $enqueue_scripts
	 * @param  array  $atts
	 */
	if ( ! function_exists( 'wma_shortcode_enqueue_scripts' ) ) {
		function wma_shortcode_enqueue_scripts( $shortcode = '', $enqueue_scripts = array(), $atts = array() ) {
			//Helper variables
				$enqueue_scripts = array_filter( (array) apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_enqueue_scripts', $enqueue_scripts, $atts ) );

			//Requirements check
				if (
						! $shortcode
						|| empty( $enqueue_scripts )
					) {
					return;
				}

			//Process
				foreach ( $enqueue_scripts as $script_name ) {
					wp_enqueue_script( $script_name );
				}

			/**
			 * Using this action hook will remove all the previously added shortcode scripts
			 * @todo  Find out why this happens
			 */
			do_action( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_do_enqueue_scripts', $atts );
		}
	} // /wma_shortcode_enqueue_scripts

?>