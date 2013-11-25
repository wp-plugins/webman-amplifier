<?php
/**
 * Marker
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @uses   $codes_globals['colors']
 *
 * @param  string class
 * @param  string color
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'class' => '',
			'color' => '',
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Validation
	//content
		$atts['content'] = do_shortcode( $content );
	//class
		$atts['class'] = trim( 'wm-marker ' . trim( $atts['class'] ) );
	//color
		$atts['color'] = trim( $atts['color'] );
		if ( in_array( $atts['color'], array_keys( $codes_globals['colors'] ) ) ) {
			$atts['class'] .= ' color-' . $atts['color'];
		}
	//class
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', esc_attr( $atts['class'] ) );

//Output
	$output = '<mark class="' . $atts['class'] . '">' . $atts['content'] . '</mark>';

?>