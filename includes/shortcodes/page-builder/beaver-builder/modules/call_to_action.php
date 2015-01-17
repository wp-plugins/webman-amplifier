<?php
/**
 * Custom Beaver Builder module init
 *
 * Call to action
 *
 * @package     WebMan Amplifier
 * @subpackage  Shortcodes
 *
 * @since    1.1
 * @version  1.1
 *
 * CONTENT:
 * - 10) Helper variables
 * - 20) Registration
 * - 30) Forms
 */





/**
 * 10) Helper variables
 */

	$module               = basename( __FILE__, '.php' );
	$module_form          = wma_bb_shortcode_def( $module, 'form' );
	$module_form_children = wma_bb_shortcode_def( $module, 'form_children' );





/**
 * 20) Registration
 */

	/**
	 * Module registration class
	 */
	class WM_BB_Module_Call_to_action extends FLBuilderModule {

		public function __construct() {

			$module = basename( __FILE__, '.php' );

			parent::__construct( apply_filters( WMAMP_HOOK_PREFIX . 'bb_module_construct_' . 'test', array(
					'name'          => wma_bb_shortcode_def( $module, 'name' ),
					'description'   => wma_bb_shortcode_def( $module, 'description' ),
					'category'      => wma_bb_shortcode_def( $module, 'category' ),
					'enabled'       => wma_bb_shortcode_def( $module, 'enabled' ),
					'editor_export' => wma_bb_shortcode_def( $module, 'editor_export' ),
					'dir'           => wma_bb_shortcode_def( $module, 'dir' ),
					'url'           => wma_bb_shortcode_def( $module, 'url' ),
				) ) );

		} // /__construct

	} // /WM_BB_Module_Call_to_action





/**
 * 30) Forms
 */

	/**
	 * Register the module and its form
	 */
	if ( ! empty( $module_form ) && is_array( $module_form ) ) {
		FLBuilder::register_module( 'WM_BB_Module_Call_to_action', $module_form );
	}



	/**
	 * Module children form
	 */
	if ( ! empty( $module_form_children ) && is_array( $module_form_children ) ) {
		FLBuilder::register_settings_form( 'wm_children_form_' . $module, $module_form_children );
	}

?>