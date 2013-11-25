<?php
/**
 * Row
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @param  string class
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'class' => '',
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Validation
	//content
		$atts['content'] = do_shortcode( $content );
	//class
		$atts['class'] = trim( 'wm-row clearfix ' . trim( $atts['class'] ) );
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', esc_attr( $atts['class'] ) );

//Output
	$output = '<div class="' . $atts['class'] . '">' . $atts['content'] . '</div>';

?>