<?php
/**
 * Pricing Table
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since    1.0
 * @version  1.1.6
 *
 * @param  string class
 * @param  boolean no_margin
 */



//Shortcode attributes
	$defaults = apply_filters( 'wmhook_shortcode_' . '_defaults', array(
			'class'     => '',
			'no_margin' => false,
		), $shortcode );
	$atts = apply_filters( 'wmhook_shortcode_' . '_attributes', $atts, $shortcode );
	$atts = shortcode_atts( $defaults, $atts, $prefix_shortcode . $shortcode );

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
		$atts['content'] = apply_filters( 'wmhook_shortcode_' . '_content', $content, $shortcode, $atts );
		$atts['content'] = apply_filters( 'wmhook_shortcode_' . $shortcode . '_content', $atts['content'], $atts );
		$replacements    = array(
				'{{columns}}'                    => $count_columns . $atts['no_margin'][0],
				'price-column-' . $count_columns => 'price-column-' . $count_columns . $atts['no_margin'][1],
			);
		$atts['content'] = str_replace( array_keys( $replacements ), $replacements, $atts['content'] );
	//class
		$atts['class'] = trim( esc_attr( 'wm-pricing-table clearfix ' . trim( $atts['class'] ) ) );
		$atts['class'] = apply_filters( 'wmhook_shortcode_' . $shortcode . '_classes', $atts['class'], $atts );

//Output
	$output = '<div class="' . esc_attr( $atts['class'] ) . '">' . $atts['content'] . '</div>';

?>