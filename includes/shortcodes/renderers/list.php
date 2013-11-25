<?php
/**
 * Icon List
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @param  string bullet
 * @param  string class
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'bullet' => '',
			'class'  => '',
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Validation
	//class
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', ' ' . trim( $atts['class'] ) );
	//content
		$atts['content'] = $content;
		$atts['content'] = str_replace( '<ul>', '<ul class="wm-icon-list ' . esc_attr( trim( $atts['class'] ) ) . '">', $atts['content'] );
	//bullet
		$atts['bullet'] = trim( $atts['bullet'] );
		if ( $atts['bullet'] ) {
			$atts['bullet']  = '<li class="' . esc_attr( $atts['bullet'] ) . '">';
			$atts['content'] = str_replace( '<li>', $atts['bullet'], $atts['content'] );
		}
	//process the content
		$atts['content'] = do_shortcode( shortcode_unautop( $atts['content'] ) );

//Output
	$output = $atts['content'];

?>