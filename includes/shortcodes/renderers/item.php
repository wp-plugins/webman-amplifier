<?php
/**
 * Item (can be accordion/tab item)
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @param  string icon
 * @param  string tags
 * @param  string title
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'icon'  => '',
			'tags'  => '',
			'title' => 'TITLE?',
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Helper variables
	global $wm_shortcode_helper_variable; //Passing the parent shortcode for "wm_item" shortcodes

//Validation
	//content
		$atts['content'] = wpautop( do_shortcode( $content ) );
	//title
		$atts['title'] = strip_tags( trim( $atts['title'] ), $this->inline_tags );
	//icon
		$atts['icon'] = trim( $atts['icon'] );
		if ( $atts['icon'] ) {
			$atts['title'] = '<i class="' . esc_attr( $atts['icon'] ) . '"></i>' . $atts['title'];
		}
	//tags
		$atts['tag_names'] = array();
		$atts['tags']      = trim( $atts['tags'] );
		$atts['tags']      = str_replace( ', ', ',', $atts['tags'] );
		$atts['tags']      = explode( ',', $atts['tags'] );
		foreach ( $atts['tags'] as $key => $tag ) {
			$tag = trim( $tag );
			if ( $tag ) {
				$atts['tag_names'][$key] = $tag;
				$atts['tags'][$key]      = 'tag-' . sanitize_html_class( $tag );
			} else {
				unset( $atts['tags'][$key] );
			}
		}
		$atts['tags'] = esc_attr( implode( ' ', $atts['tags'] ) );
	//class
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', trim( esc_attr( 'wm-item wm-item-wrap ' . $atts['tags'] ) ) );

//Output
	if ( 'accordion' == $wm_shortcode_helper_variable ) {
	//Markup for "wm_accordion" parent shortcode
		$output = "\r\n" . '<div class="wm-item ' . $atts['tags'] . '"><h3 class="wm-item-title ' . $atts['tags'] . '" data-tags="' . $atts['tags'] . '" data-tag-names="' . esc_attr( implode( '|', $atts['tag_names'] ) ) . '">' . $atts['title'] . '</h3><div class="wm-item-content ' . sanitize_html_class( strip_tags( $atts['title'] ) ) . '">' . $atts['content'] . '</div></div>' . "\r\n";
	} elseif ( 'tabs' == $wm_shortcode_helper_variable ) {
	//Markup for "wm_tabs" parent shortcode
		$i      = rand( 100, 999 );
		$output = "\r\n" . '<div class="wm-item ' . sanitize_html_class( strip_tags( $atts['title'] ) ) . '_' . $i . '" id="' . sanitize_html_class( strip_tags( $atts['title'] ) ) . '_' . $i . '" data-title="' . sanitize_html_class( strip_tags( $atts['title'] ) ) . '_' . $i . '&&' . esc_attr( $atts['title'] ) . '">' . $atts['content'] . '</div>' . "\r\n";
	}

?>