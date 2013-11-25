<?php
/**
 * Pullquote
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @uses   $codes_globals['align']
 *
 * @param  string align
 * @param  string class
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'align' => 'left',
			'class' => '',
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Validation
	//content
		$atts['content'] = do_shortcode( $content );
	//class
		$atts['class'] = trim( 'wm-pullquote ' . trim( $atts['class'] ) );
	//align
		$atts['align'] = trim( $atts['align'] );
		if ( in_array( $atts['align'], array_keys( $codes_globals['align'] ) ) ) {
			$atts['class'] .= ' align' . $atts['align'];
		}
	//class
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', esc_attr( $atts['class'] ) );

//Output
	$output = '<blockquote class="' . $atts['class'] . '">' . $atts['content'] . '</blockquote>';

?>