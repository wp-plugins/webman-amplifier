<?php
/**
 * Message box
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @uses   $this->$codes_globals['colors'], $codes_globals['sizes']
 *
 * @param  string class
 * @param  string color
 * @param  string icon
 * @param  string size
 * @param  string title
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'class' => '',
			'color' => '',
			'icon' => '',
			'size'  => '',
			'title' => ''
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Validation
	//class
		$atts['class'] = trim( 'wm-message ' . trim( $atts['class'] ) );
	//content
		$content  = do_shortcode( $content );
		$iconated = ( 0 === strpos( $content, '<i class="icon-' ) ) ? ( ' iconated' ) : ( '' );
		$atts['content']  = '<div class="wm-message-content wm-message-element' . $iconated . '">' . $content . '</div>';
	//color
		$atts['color'] = trim( $atts['color'] );
		if ( in_array( $atts['color'], array_keys( $codes_globals['colors'] ) ) ) {
			$atts['class'] .= ' color-' . $atts['color'];
		}
	//icon
		$atts['icon'] = trim( $atts['icon'] );
		if ( $atts['icon'] ) {
			$atts['class'] .= ' ' . esc_attr( $atts['icon'] );
		}
	//size
		$atts['size'] = trim( $atts['size'] );
		if ( in_array( $atts['size'], array_keys( $codes_globals['sizes'] ) ) ) {
			$atts['class'] .= ' size-' . $codes_globals['sizes'][$atts['size']];
		}
	//title
		$atts['title'] = trim( $atts['title'] );
		if ( $atts['title'] ) {
			$atts['title'] = strip_tags( $atts['title'], $this->inline_tags );
			$atts['title'] = '<h3 class="wm-message-title wm-message-element">' . $atts['title'] . '</h3>';
		}
	//class
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', esc_attr( $atts['class'] ) );

//Output
	$output = '<div class="' . $atts['class'] . '">' . $atts['title'] . $atts['content'] . '</div>';

?>