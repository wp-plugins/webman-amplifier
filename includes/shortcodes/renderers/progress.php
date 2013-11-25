<?php
/**
 * Progress bar
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @uses   $codes_globals['colors']
 *
 * @param  string class
 * @param  string color
 * @param  integer progress
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'class'    => '',
			'color'    => '',
			'progress' => 66,
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Validation
	//class
		$atts['class'] = trim( 'wm-progress ' . trim( $atts['class'] ) );
	//content
		$atts['content'] = '<div class="wm-progress-caption">' . do_shortcode( strip_tags( $content, '<a><code><em><i><img><mark><small><strong>' ) ) . '</div>';
	//color
		$atts['color'] = trim( $atts['color'] );
		if ( in_array( $atts['color'], array_keys( $codes_globals['colors'] ) ) ) {
			$atts['class'] .= ' color-' . $atts['color'];
		}
	//progress
		$atts['progress'] = absint( $atts['progress'] );
		if ( 100 < $atts['progress'] ) {
			$atts['progress'] = 66;
		}
	//class
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', esc_attr( $atts['class'] ) );

//Output
	$output = '<div class="' . $atts['class'] . '" title="' . $atts['progress'] . '%" data-progress="' . $atts['progress'] . '">' . $atts['content'] . '<div class="wm-progress-bar" style="width:' . $atts['progress'] . '%;"></div></div>';

?>