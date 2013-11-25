<?php
/**
 * WebMan Metabox Generator
 * Form Fields Renderers and Validators
 *
 * Numeric slider element
 *
 * @package     WebMan Amplifier
 * @subpackage  Metabox
 */



/**
 * NUMERIC SLIDER
 */

	/**
	 * Numeric slider
	 *
	 * @since       1.0
	 * @package	    WebMan Amplifier
	 * @subpackage  Metabox
	 * @author      WebMan
	 * @version     1.0
	 */
	if ( ! function_exists( 'wm_field_slider' ) ) {
		function wm_field_slider( $field, $page_template = null ) {
			//Field definition array
				$field = wp_parse_args( (array) $field, array(
						//DEFAULTS:
						//* = Required setting
						'type'        => 'slider',  //Field type name *
						'id'          => 'id',      //Field ID (form field name) *
						'label'       => '',        //Field label
						'description' => '',        //Field description
						'class'       => '',        //Additional CSS class
						'default'     => '',        //Default value
						'min'         => 0,         //Minimal value
						'max'         => 10,        //Maximal value
						'step'        => 1,         //Steps
						'zero'        => true,      //Zero value allowed?
						'conditional' => '',        //Conditional display setup
					) );

			//Value processing
				$value = wm_meta_option( $field['id'] );

				if (
						$value
						|| ( $field['zero'] && 0 === intval( $value ) )
					) {
					$value = esc_attr( intval( $value ) );
				} else {
					$value = esc_attr( intval( $field['default'] ) );
				}

			//Field ID setup
				$field['id'] = WM_METABOX_FIELD_PREFIX . $field['id'];

			//Output
				$output  = "\r\n\t" . '<tr class="option slider-wrap option-' . trim( sanitize_html_class( $field['id'] ) . ' ' . $field['class'] ) . '" data-option="' . $field['id'] . '"><th>';
					//Label
						$output .= "\r\n\t\t" . '<label for="' . $field['id'] . '">' . trim( strip_tags( $field['label'], WM_METABOX_LABEL_HTML ) ) . '</label>';
				$output .= "\r\n\t" . '</th><td>';
					//Input field
						$output .= "\r\n\t\t" . '<input type="number" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $value . '" size="5" maxlength="5" />';
						$output .= "\r\n\t\t" . '<span class="fieldtype-slider"><span id="' . $field['id'] . '-slider" data-min="' . intval( $field['min'] ) . '" data-max="' . intval( $field['max'] ) . '" data-step="' . intval( $field['step'] ) . '" data-value="' . $value . '"></span></span>';
					//Description
						if ( trim( $field['description'] ) ) {
							$output .= "\r\n\t\t" . '<p class="description">' . trim( $field['description'] ) . '</p>';
						}
					//Reset default value button
						if ( trim( $field['default'] ) ) {
							$output .= "\r\n\t\t" . '<a data-option="' . $field['id'] . '" class="button-default-value default-slider" title="' . __( 'Use default value', 'wm_domain' ) . '"><span>' . $field['default'] . '</span></a>';
						}

					echo $output;

				//Conditional display
					do_action( WM_METABOX_HOOK_PREFIX . 'conditional', $field, $field['id'] );
					do_action( WM_METABOX_HOOK_PREFIX . 'conditional_' . sanitize_html_class( $field['type'] ), $field, $field['id'] );
					do_action( WM_METABOX_HOOK_PREFIX . 'conditional_' . sanitize_html_class( $field['id'] ), $field );

				echo "\r\n\t" . '</td></tr>';
		} // /wm_field_slider
	} // /function_exists check

	add_action( WM_METABOX_HOOK_PREFIX . 'render_' . 'slider', 'wm_field_slider', 10, 2 );



	/**
	 * Numeric slider validation
	 *
	 * @since       1.0
	 * @package	    WebMan Amplifier
	 * @subpackage  Metabox
	 * @author      WebMan
	 * @version     1.0
	 */
	if ( ! function_exists( 'wm_field_slider_validation' ) ) {
		function wm_field_slider_validation( $new, $field, $post_id ) {
			$new = intval( $new );

			return $new;
		} // /wm_field_slider_validation
	} // /function_exists check

	add_action( WM_METABOX_HOOK_PREFIX . 'saving_' . 'slider', 'wm_field_slider_validation', 10, 3 );

?>