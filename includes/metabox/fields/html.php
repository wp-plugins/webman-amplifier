<?php
/**
 * WebMan Metabox Generator
 * Form Fields Renderers and Validators
 *
 * Custom HTML element
 *
 * @package     WebMan Amplifier
 * @subpackage  Metabox
 */



/**
 * CSS classes
 *
 * With custom HTML element you can use several CSS classes on elements.
 * Here is the list of pre-styled classes you can use:
 *
 * tr.option
 * 		Applies the styles of meta option wrapper (paddings, borders, hover hightlight,...).
 *
 * tr.padding-20
 * 		Applies 20px padding on direct TD children of the TR.
 *
 * tr.option-heading
 * 		Special heading wrapper. Use H3 or H2 inside the child TH.
 *
 * tr.option-heading.toggle
 * 		Toggles subsequent sub-section (hidden by default) upon clicking the heading.
 *
 * tr.option-heading.toggle.open
 * 		The same as above, but makes the sub-section visible by default.
 *
 * div.box
 * 		Specially styled info box.
 *
 * div.box.blue, div.box.green, div.box.red, div.box.yellow
 * 		Additional color subclasses to style the box.
 */



/**
 * HTML
 */

	/**
	 * Custom HTML
	 *
	 * @since       1.0
	 * @package	    WebMan Amplifier
	 * @subpackage  Metabox
	 * @author      WebMan
	 * @version     1.0
	 */
	if ( ! function_exists( 'wm_field_html' ) ) {
		function wm_field_html( $field, $page_template = null ) {
			//Field definition array
				$field = wp_parse_args( (array) $field, array(
						//DEFAULTS:
						//* = Required setting
						'type'      => 'html',  //Field type name *
						'content'   => '',      //Custom HTML content
						'condition' => true,    //Displays only when condition is true
					) );

			//Output
				if ( $field['condition'] ) {
					echo "\r\n" . $field['content'] . "\r\n";
				}
		} // /wm_field_html
	} // /function_exists check

	add_action( WM_METABOX_HOOK_PREFIX . 'render_' . 'html', 'wm_field_html', 10, 2 );

?>