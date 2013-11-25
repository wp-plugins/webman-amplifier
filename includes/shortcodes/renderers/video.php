<?php
/**
 * Video
 *
 * Compatible with WordPress 3.6+
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @param  string class
 * @param  string poster @link http://codex.wordpress.org/Video_Shortcode
 * @param  string src
 * @param  string ... For attributes please see @link http://codex.wordpress.org/Video_Shortcode.
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'class'  => '',
			'poster' => '',
			'src'    => '',
		) );
	//get the custom attributes in $atts['attributes']
	//parameters: $defaults, $atts, $remove, $aside, $shortcode
	$atts = wm_shortcode_custom_atts( $defaults, $atts, array( 'height', 'poster', 'src', 'width' ), array( 'class' ), $this->prefix_shortcode . $shortcode );

//Validation
	//content
		$atts['content'] = '';
	//src
		$atts['src'] = trim( $atts['src'] );
		if ( $atts['src'] ) {
			if (
					stripos( $atts['src'], 'mp4' )
					|| stripos( $atts['src'], 'm4v' )
					|| stripos( $atts['src'], 'webm' )
					|| stripos( $atts['src'], 'ogv' )
					|| stripos( $atts['src'], 'wmv' )
					|| stripos( $atts['src'], 'flv' )
				) {
				$atts['content'] = do_shortcode( '[video src="' . $atts['src'] . '" poster="' . $atts['poster'] . '" ' . $atts['attributes'] . ' /]' );
			} else {
				$atts['content'] = wp_oembed_get( esc_url( $atts['src'] ) );
			}
		}
	//class
		$atts['class'] = trim( 'wm-video ' . trim( $atts['class'] ) );
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', esc_attr( $atts['class'] ) );
	//poster
		$atts['poster'] = trim( $atts['poster'] );
		if ( is_numeric( $atts['poster'] ) ) {
			$atts['poster'] = wp_get_attachment_url( $atts['poster'] );
		}
		$atts['poster'] = esc_url( $atts['poster'] );

//Output
	$output = '<div class="' . $atts['class'] . '">' . $atts['content'] . '</div>';

?>