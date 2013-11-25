<?php
/**
 * Pricing Table
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @param  string class
 * @param  boolean no_margin
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'class'     => '',
			'no_margin' => false,
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Helper variables
	global $wm_shortcode_helper_variable;
	$wm_shortcode_helper_variable = 0; //Dynamic columns counting for current Pricing Table shortcode

//Validation
	//no_margin
		if ( $atts['no_margin'] ) {
			$atts['no_margin'] = array( ' no-margin', ' last' );
		} else {
			$atts['no_margin'] = array( '', ' last' );
		}
	//content
		$count_columns   = substr_count( $content, '[' . $this->prefix_shortcode . 'price' );
		$content         = do_shortcode( $content );
		$replacements    = array(
				'{{columns}}'                    => $count_columns . $atts['no_margin'][0],
				'price-column-' . $count_columns => 'price-column-' . $count_columns . $atts['no_margin'][1],
			);
		$content         = str_replace( array_keys( $replacements ), $replacements, $content );
		$atts['content'] = $content;
	//class
		$atts['class'] = trim( esc_attr( 'wm-pricing-table clearfix ' . trim( $atts['class'] ) ) );
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', $atts['class'] );

//Output
	$output = '<div class="' . $atts['class'] . '">' . $atts['content'] . '</div>';

?>