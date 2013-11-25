<?php
/**
 * Button
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @uses   $codes_globals['colors'], $codes_globals['sizes']
 *
 * @param  string class
 * @param  string color
 * @param  string icon
 * @param  string size
 * @param  string url
 * @param  string ... You can actually set up a custom attributes for this shortcode. They will be outputed as HTML attributes.
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'class' => '',
			'color' => '',
			'icon'  => '',
			'size'  => '',
			'url'   => '#'
		) );
	//get the custom attributes in $atts['attributes']
	//parameters: $defaults, $atts, $remove, $aside, $shortcode
	$atts = wm_shortcode_custom_atts( $defaults, $atts, array( 'href' ), array( 'class' ), $this->prefix_shortcode . $shortcode );

//Validation
	//class
		$atts['class'] = trim( 'wm-button ' . trim( $atts['class'] ) );
	//content
		$atts['content'] = do_shortcode( $content );
	//color
		$atts['color'] = trim( $atts['color'] );
		if ( in_array( $atts['color'], array_keys( $codes_globals['colors'] ) ) ) {
			$atts['class'] .= ' color-' . $atts['color'];
		}
	//size
		$atts['size'] = trim( $atts['size'] );
		if ( in_array( $atts['size'], array_keys( $codes_globals['sizes'] ) ) ) {
			$atts['class'] .= ' size-' . $codes_globals['sizes'][$atts['size']];
		}
	//url
		$atts['url'] = esc_url( $atts['url'] );
	//icon
		$atts['icon'] = trim( $atts['icon'] );
		if ( $atts['icon'] ) {
			$atts['icon'] = '<i class="' . esc_attr( $atts['icon'] ) . '"></i> ';
		}
	//class
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', esc_attr( $atts['class'] ) );

//Output
	$output = '<a href="' . $atts['url'] . '" class="' . $atts['class'] . '"' . $atts['attributes'] . '>' . $atts['icon'] . $atts['content'] . '</a>';

?>