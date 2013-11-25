<?php
/**
 * Pre (HTML tag)
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 */



//Validation
	//content
		$content         = str_replace( '[', '&#91;', $content );
		$content         = str_replace( array( '<p>', '</p>', '<br />' ), '', $content );
		$atts['content'] = esc_html( shortcode_unautop( $content ) );

//Output
	$output = '<pre>' . $atts['content'] . '</pre>';

?>