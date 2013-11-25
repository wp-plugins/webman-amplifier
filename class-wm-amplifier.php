<?php
/**
 * WebMan Amplifier
 *
 * @package    WebMan Amplifier
 * @author     WebMan
 * @license    GPL-2.0+
 * @link       http://www.webmandesign.eu
 * @copyright  2013 WebMan - Oliver Juhas
 */



//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * Main WM Apmlifier Class
 *
 * Contains the main functions for WebMan Amplifier.
 *
 * @version	 1.0
 * @since    1.0
 * @package	 WebMan Amplifier
 * @author   WebMan
 */
if ( ! class_exists( 'WM_Amplifier' ) ) {
	class WM_Amplifier {

		/**
		 * VARIABLES DEFINITION
		 */

			/**
			 * @var  string
			 */
			public $version;

			/**
			 * @var  array
			 */
			public $errors = array();





		/**
		 * SINGLETON
		 */

			/**
			 * Main WebMan Amplifier Instance
			 *
			 * Please load it only one time.
			 *
			 * Insures that only one instance of WebMan Amplifier exists in memory
			 * at any one time.
			 * Also prevents needing to define globals all over the place.
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @return  The one true WebMan Amplifier
			 */
			public static function instance() {

				//Store the instance locally to avoid private static replication
					static $instance = null;

				//Only run these methods if they haven't been ran previously
					if ( null === $instance ) {
						$instance = new WM_Amplifier;

						$instance->setup_globals();
						$instance->includes();
						$instance->setup_actions();
						$instance->setup_features();
					}

				//Always return the instance
					return $instance;
			} // /instance





		/**
		 * MAGIC METHODS (more in http://php.net/manual/en/language.oop5.magic.php)
		 */

			/**
			 * Constructor
			 *
			 * A dummy constructor to prevent class from being loaded more than once.
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function __construct() {
				/* Do nothing here */
			} // /__construct



			/**
			 * Magic method to prevent notices and errors from invalid method calls
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function __call( $name = '', $args = array() ) {
				unset( $name, $args );
				return null;
			} // /__call



			/**
			 * A dummy magic method to prevent class from being cloned
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function __clone() {
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wm_domain' ), '2.1' );
			} // /__clone



			/**
			 * A dummy magic method to prevent class from being unserialized
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function __wakeup() {
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wm_domain' ), '2.1' );
			} // /__wakeup





		/**
		 * PRIVATE METHODS
		 */

			/**
			 * Set some smart defaults to class variables.
			 * Allow some of them to be filtered to allow for early overriding.
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function setup_globals() {
				//Versions
					$this->version = WMAMP_VERSION;

				//Paths, URLs
					$this->includes_dir = apply_filters( WMAMP_HOOK_PREFIX . 'includes_dir', WMAMP_INCLUDES_DIR );
					$this->assets_url   = apply_filters( WMAMP_HOOK_PREFIX . 'assets_url', WMAMP_ASSETS_URL );
					$this->lang_dir     = apply_filters( WMAMP_HOOK_PREFIX . 'lang_dir', trailingslashit( WMAMP_PLUGIN_DIR . 'languages' ) );

				//Misc
					$this->domain = 'wm_domain';
					// $this->errors = new WP_Error(); //Feedback
			} // /setup_globals



			/**
			 * Include required files
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function includes() {
				//Shortcodes
					require( $this->includes_dir . 'shortcodes/class-shortcodes.php' );

				//Icon font
					require( $this->includes_dir . 'class-icon-font.php' );

				//Admin
					if ( is_admin() ) {
						//Meta boxes
							require( $this->includes_dir . 'metabox/class-metabox.php' );
					}
			} // /includes



			/**
			 * Setup the default hooks and actions
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function setup_actions() {
				//Array of bbPress core actions
					$actions = array(
						'save_permalinks',     //Setup the default theme compat
						'register_post_types', //Register post types
						'load_textdomain',     //Load textdomain
						'register_shortcodes', //Register shortcodes
						'register_icons',      //Register icon font
					);

				//Add the actions
					foreach( $actions as $class_action ) {
						add_action( 'init', array( $this, $class_action ), 10 );
					}

				//Loaded action
					do_action( WMAMP_HOOK_PREFIX . 'loaded' );
			} // /setup_actions



			/**
			 * Setup WordPress features
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function setup_features() {
				//Cropped squared image used in admin post tables
					add_image_size( 'admin-thumbnail', 100, 100, true );

				//Load assets (JS and CSS)
					add_action( 'admin_enqueue_scripts', array( $this, 'assets' ), 998 );
			} // /setup_features





		/**
		 * PUBLIC METHODS
		 */

			/**
			 * Register (and include) styles and scripts
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function assets() {
				//Helper variables
					global $current_screen, $wp_version;
					$protocol = ( is_ssl() ) ? ( 'https' ) : ( 'http' );

				//Register
					//Styles
						wp_register_style( 'wmamp-admin-styles',    $this->assets_url . 'css/admin-addons.css',    false, $this->version, 'screen' );
						wp_register_style( 'wmamp-admin-styles-38', $this->assets_url . 'css/admin-addons-38.css', false, $this->version, 'screen' );

				//Enqueue (only on specific admin pages)
				if ( in_array( $current_screen->base, array( 'edit', 'post' ) ) ) {
					//Styles
						wp_enqueue_style( 'wmamp-admin-styles' );
				}

				//WordPress 3.8+ styles
				if ( version_compare( (float) $wp_version, '3.8', '>=' ) ) {
					wp_enqueue_style( 'wmamp-admin-styles-38' );
				}
			} // /assets



			/**
			 * Save permalinks
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function save_permalinks() {
				if ( ! is_admin() ) {
					return;
				}

				//We need to save the options ourselves.
				//Settings api does not trigger save for the permalinks page.
					if ( isset( $_POST['permalink_structure'] ) && isset( $_POST['wmamp-permalinks'] ) ) {
						$permalinks = $_POST['wmamp-permalinks'];

						//Validation
							if ( ! $permalinks || ! is_array( $permalinks ) ) {
								$permalinks = array();
							} else {
								foreach ( $permalinks as $key => $permalink ) {
									$permalinks[$key] = urlencode( untrailingslashit( trim( $permalink ) ) );
								}
							}

						//Save permalinks
							update_option( 'wmamp-permalinks', $permalinks );
					}
			} // /save_permalinks



			/**
			 * Setup the post types
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function register_post_types() {
				//Content Modules
					if ( in_array( 'cp-modules', wm_current_theme_supports_subfeatures( 'webman-amplifier' ) ) ) {
						include_once( WMAMP_INCLUDES_DIR . 'custom-posts/modules.php' );
					}

				//Logos
					if ( in_array( 'cp-logos', wm_current_theme_supports_subfeatures( 'webman-amplifier' ) ) ) {
						include_once( WMAMP_INCLUDES_DIR . 'custom-posts/logos.php' );
					}

				//Projects
					if ( in_array( 'cp-projects', wm_current_theme_supports_subfeatures( 'webman-amplifier' ) ) ) {
						include_once( WMAMP_INCLUDES_DIR . 'custom-posts/projects.php' );
					}

				//Staff
					if ( in_array( 'cp-staff', wm_current_theme_supports_subfeatures( 'webman-amplifier' ) ) ) {
						include_once( WMAMP_INCLUDES_DIR . 'custom-posts/staff.php' );
					}

				//Testimonials
					if ( in_array( 'cp-testimonials', wm_current_theme_supports_subfeatures( 'webman-amplifier' ) ) ) {
						include_once( WMAMP_INCLUDES_DIR . 'custom-posts/testimonials.php' );
					}

				//Plugin register custom posts action
					do_action( WMAMP_HOOK_PREFIX . 'register_post_types' );
			} // /register_post_types



			/**
			 * Register the shortcodes
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @uses    WM_Shortcodes
			 */
			public function register_shortcodes() {
				return WM_Shortcodes::instance();
			} // /register_shortcodes



			/**
			 * Register icon font file
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @uses    WM_Icons
			 */
			public function register_icons() {
				return WM_Icons::instance();
			} // /register_icons



			/**
			 * Load the translation file for current language. Checks the languages
			 * folder inside the plugin first, and then the default WordPress
			 * languages folder.
			 *
			 * Note that custom translation files inside the plugin folder
			 * will be removed on plugin updates. If you're creating custom
			 * translation files, please use the global language folder.
			 *
			 * @since   1.0
			 * @access  public
			 * @return  boolean
			 */
			public function load_textdomain() {
				//Traditional WordPress plugin locale filter
					$locale = apply_filters( 'plugin_locale', get_locale(), $this->domain );
					$mofile = sprintf( '%1$s-%2$s.mo', $this->domain, $locale );

				//Setup paths to current locale file
					$mofile_local  = $this->lang_dir . $mofile;
					$mofile_global = WP_LANG_DIR . '/wm-amplifier/' . $mofile;

					if ( file_exists( $mofile_global ) ) {
						//Look in global /wp-content/languages/wm-amplifier folder
							return load_textdomain( $this->domain, $mofile_global );
					} elseif ( file_exists( $mofile_local ) ) {
						//Look in local /wp-content/plugins/wm-amplifier/languages/ folder
							return load_textdomain( $this->domain, $mofile_local );
					}

				//Nothing found
					return false;
			} // /load_textdomain

	} // /WM_Amplifier
} // /class WM_Amplifier check





/**
 * HELPER GLOBAL FUNCTIONS
 */

	require_once( 'includes/functions.php' );

?>