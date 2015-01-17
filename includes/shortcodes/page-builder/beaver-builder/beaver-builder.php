<?php
/**
 * Beaver Builder plugin support
 *
 * @link  https://www.wpbeaverbuilder.com/
 *
 * @since    1.1
 * @version  1.1
 *
 * @package     WebMan Amplifier
 * @subpackage  Shortcodes
 *
 * @todo  Shortcode generator button in page builder.
 * @todo  Load on page builder pages only - needed Beaver Builder update to do so.
 * @todo  Beaver Builder contains a bug: you can not set up a form field ID to `type`.
 *        Reported this bug already. Applied a workaraound with a new shortcode parameter
 *        named `type_bb` (specifically in Divider, Price and Table shortcode).
 *
 * CONTENT:
 * -  10) Actions and filters
 * -  20) Helpers
 * -  30) Assets
 * -  40) Modules
 * - 100) Other functions
 */





/**
 * 10) Actions and filters
 */

	/**
	 * Actions
	 */

		add_action( 'init', 'wma_bb_custom_modules', 8 );
		add_action( 'init', 'wma_bb_shortcode_def',  9 );

		add_action( 'wp_enqueue_scripts', 'wma_bb_assets' );

		add_action( 'fl_builder_control_' . 'wm_radio', 'wma_bb_custom_field_wm_radio', 10, 3 );

		add_action( WM_SHORTCODES_HOOK_PREFIX . 'bb_module_output', 'wma_bb_custom_module_output', 10, 2 );





/**
 * 20) Helpers
 */

	/**
	 * Get Beaver Builder shortcode definitions
	 *
	 * @param  string $shortcode
	 * @param  string $property
	 */
	if ( ! function_exists( 'wma_bb_shortcode_def' ) ) {
		function wma_bb_shortcode_def( $shortcode, $property = '' ) {
			//Helper variables
				$output = '';

				$def = wma_shortcodes()->get_definitions()['bb_plugin'];

			//Preparing output
				if ( 'all' === $shortcode ) {

					return $def;

				} elseif ( isset( $def[ $shortcode ] ) ) {

					$output = wp_parse_args( wma_shortcodes()->get_definitions()['bb_plugin'][ $shortcode ], array(
							'params'          => array(),
							'name'            => '-',
							'description'     => '',
							'category'        => _x( 'WM Modules', 'Page builder modules category name.', 'wm_domain' ),
							'enabled'         => true,
							'editor_export'   => true, //Export content to WP editor?
							'dir'             => trailingslashit( WMAMP_INCLUDES_DIR ) . 'shortcodes/page-builder/beaver-builder/modules/',
							'url'             => trailingslashit( WMAMP_INCLUDES_URL ) . 'shortcodes/page-builder/beaver-builder/modules/',
							'output'          => '',
							'output_children' => '',
							'params'          => array(),
							'params_children' => array(),
							'form'            => array(),
							'form_children'   => array(),
						) );

					if ( $property && isset( $output[ $property ] ) ) {
						$output = $output[ $property ];
					} elseif ( $property && ! isset( $output[ $property ] ) ) {
						$output = '';
					}

				}

			//Output
				return $output;
		}
	} // /wma_bb_shortcode_def





/**
 * 30) Assets
 */

	/**
	 * Styles and scripts
	 */
	if ( ! function_exists( 'wma_bb_assets' ) ) {
		function wma_bb_assets() {
			//Styles
				if ( isset( $_GET['fl_builder'] ) ) {
					wp_enqueue_style( 'wm-radio' );
					wp_enqueue_style( 'wm-shortcodes-bb-addon' );
				}
		}
	} // /wma_bb_assets





/**
 * 40) Modules
 */

	/**
	 * Add custom modules
	 */
	if ( ! function_exists( 'wma_bb_custom_modules' ) ) {
		function wma_bb_custom_modules() {
			//Helper variables
				$defs = wma_bb_shortcode_def( 'all' );

			//Include files
				if ( ! empty( $defs ) ) {
					foreach ( $defs as $module => $def ) {
						$module_file_path = trailingslashit( WMAMP_INCLUDES_DIR ) .'shortcodes/page-builder/beaver-builder/modules/' . $module . '.php';

						if ( file_exists( $module_file_path ) ) {
							require_once( $module_file_path );
						}
					}
				}
		}
	} // /wma_bb_custom_modules



	/**
	 * Module output
	 *
	 * @param  obj    $module   Page builder's current module object
	 * @param  array  $settings Settings passed from page builder form
	 */
	if ( ! function_exists( 'wma_bb_custom_module_output' ) ) {
		function wma_bb_custom_module_output( $module, $settings = array() ) {
			//Requirements check
				if ( ! is_object( $module ) || ! isset( $module->slug ) ) {
					return;
				}

			//Helper variables
				$shortcode_output = $replace_children = '';

				$module = $module->slug;

				$output   = array(
						'parent' => (string) wma_bb_shortcode_def( $module, 'output' ),
						'child'  => (string) wma_bb_shortcode_def( $module, 'output_children' )
					);
				$params   = array(
						'parent' => (array) wma_bb_shortcode_def( $module, 'params' ),
						'child'  => (array) wma_bb_shortcode_def( $module, 'params_children' )
					);
				$children = ( isset( $settings->children ) ) ? ( array_filter( $settings->children ) ) : ( false );

			//Preparing output

				/**
				 * Basic form output (parent)
				 */

					foreach ( $params['parent'] as $param ) {

						$replace = '';
						$param   = trim( $param );

						if ( $param ) {
							if (
									isset( $settings->$param )
									&& ! empty( $settings->$param )
								) {

								$value = $settings->$param;

								//Convert the array shortcode parameter to string
									if ( is_array( $value ) ) {
										$value = implode( ',', $value );
									}

								$replace = ( 'content' === $param ) ? ( $value ) : ( ' ' . $param . '="' . $value . '"' );
								$replace = apply_filters( 'wma_bb_custom_module_output_parent_replace', $replace, $module, $param, $settings );

							}

							$output['parent'] = str_replace( '{{' . $param . '}}', $replace, $output['parent'] );
						}

					} // /foreach

				/**
				 * Items form output (children)
				 */

					if (
							is_array( $children )
							&& ! empty( $children )
							&& ! empty( $params['child'] )
						) {

						foreach ( $children as $child ) {

							//Requirements check
								if ( ! is_object( $child ) || empty( $child ) ) {
									continue;
								}

							$replace_child = $output['child'];

							foreach ( $params['child'] as $param ) {

								$replace = '';
								$param   = trim( $param );

								if ( $param ) {
									if (
											isset( $child->$param )
											&& ! empty( $child->$param )
										) {

										$value = $child->$param;

										//Convert the array shortcode parameter to string
											if ( is_array( $value ) ) {
												$value = implode( ',', $value );
											}

										$replace = ( 'content' === $param ) ? ( $value ) : ( ' ' . $param . '="' . $value . '"' );
										$replace = apply_filters( 'wma_bb_custom_module_output_child_replace', $replace, $module, $param, $child, $settings );

									}

									$replace_child = str_replace( '{{' . $param . '}}', $replace, $replace_child );
								}

							} // /foreach

							$replace_children .= $replace_child;

						} // /foreach

					}

				/**
				 * Actual outputed shortcode
				 */

					$shortcode_output = str_replace( array( '{{children}}', '{{items}}' ), $replace_children, $output['parent'] );

					$shortcode_output = apply_filters( 'wma_bb_custom_module_output', $shortcode_output, $module, $settings );

			//Output
				echo $shortcode_output;
		}
	} // /wma_bb_custom_module_output





/**
 * 100) Other functions
 */

	/**
	 * Custom page builder input field: wm_radio
	 *
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $field
	 */
	if ( ! function_exists( 'wma_bb_custom_field_wm_radio' ) ) {
		function wma_bb_custom_field_wm_radio( $name, $value, $field ) {
			//Output
				echo apply_filters( WM_SHORTCODES_HOOK_PREFIX . 'wma_bb_custom_field_' . 'wm_radio' . '_output', wma_custom_field_wm_radio( $name, $value, $field ), $name, $value, $field );
		}
	} // /wma_bb_custom_field_wm_radio

?>