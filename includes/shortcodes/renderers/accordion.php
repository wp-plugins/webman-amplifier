<?php
/**
 * Accordion wrapper
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @param  integer active
 * @param  string behaviour
 * @param  string class
 * @param  boolean filter
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'active'    => 0,
			'behaviour' => 'accordion',
			'class'     => '',
			'filter'    => false,
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Helper variables
	global $wm_shortcode_helper_variable;
	$wm_shortcode_helper_variable = $shortcode; //Passing the parent shortcode for "wm_item" shortcodes

//Validation
	//active
		$atts['active'] = absint( $atts['active'] );
	//behaviour
		$atts['behaviour'] = trim( $atts['behaviour'] );
		if ( ! in_array( $atts['behaviour'], array( 'accordion', 'toggle' ) ) ) {
			$atts['behaviour'] = 'accordion';
		}
	//content
		$content = do_shortcode( $content );
		if ( $atts['filter'] && 'toggle' == $atts['behaviour'] ) {
			//Prepare filter output
				$tags = array();
				preg_match_all( '/(data-tag-names)=("[^"]*")/i', $content, $tags );
				if (
						is_array( $tags )
						&& ! empty( $tags )
						&& isset( $tags[2] )
					) {
					$tags = $tags[2];
					$tags = implode( '|', $tags );
					$tags = str_replace( '"', '', $tags );
					$tags = explode( '|', $tags );
					$tags = array_unique( $tags );
					asort( $tags );

					//Filter output
						$atts['filter'] = '<li class="wm-filter-items-all active"><a href="#" data-filter="*">' . __( 'All', 'wm_domain' ) . '</a></li>';
						foreach ( $tags as $tag ) {
							$tag_class = esc_attr( 'tag-' . sanitize_html_class( $tag ) );
							$atts['filter'] .= '<li class="wm-filter-items-' . $tag_class . '"><a href="#" data-filter=".' . $tag_class . '">' . html_entity_decode( $tag ) . '</a></li>';
						}
						$atts['filter'] = '<div class="wm-filter"><ul>' . $atts['filter'] . '</ul></div>';
				}
			//Implement filter output
				$atts['content'] = $atts['filter'] . '<div class="wm-filter-this-simple">' . $content . '</div>';
		} else {
			$atts['content'] = $content;
		}
	//class
		$atts['class'] = trim( esc_attr( 'wm-accordion clearfix ' . trim( $atts['class'] ) ) );
		if ( $atts['filter'] ) {
			$atts['class'] .= ' filterable-simple';
		}
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', $atts['class'] );

//Enqueue scripts
	if ( apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_enqueue_scripts', true ) ) {
		wp_enqueue_script( 'wm-shortcodes-accordion' );
	}
	do_action( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_enqueue_scripts' );

//Output
	$output = '<div class="' . $atts['class'] . '" data-active="' . $atts['active'] . '" data-behaviour="' . $atts['behaviour'] . '">' . $atts['content'] . '</div>';

?>