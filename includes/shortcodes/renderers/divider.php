<?php
/**
 * Divider
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @uses   $codes_globals['divider_types']
 *
 * @param  string class
 * @param  string space_after
 * @param  string space_before
 * @param  string style
 * @param  string type
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'class'        => '',
			'space_after'  => 0,
			'space_before' => 0,
			'style'        => '',
			'type'         => '',
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Validation
	//class
		$atts['class'] = trim( 'wm-divider ' . trim( $atts['class'] ) );
	//space_after
		$atts['space_after'] = absint( $atts['space_after'] );
		if ( $atts['space_after'] ) {
			$atts['style'] .= 'margin-top:' . $atts['space_after'] . 'px;';
		}
	//space_before
		$atts['space_before'] = absint( $atts['space_before'] );
		if ( $atts['space_before'] ) {
			$atts['style'] .= 'margin-bottom:' . $atts['space_before'] . 'px;';
		}
	//style
		if ( $atts['style'] ) {
			$atts['style'] = ' style="' . esc_attr( $atts['style'] ) . '"';
		}
	//type
		$atts['type'] = trim( $atts['type'] );
		if ( in_array( $atts['type'], array_keys( $codes_globals['divider_types'] ) ) ) {
			$atts['class'] .= ' type-' . $atts['type'];
		}
	//class
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', esc_attr( $atts['class'] ) );

//Output
	$output = '<hr class="' . $atts['class'] . '"' . $atts['style'] . ' />';

?>