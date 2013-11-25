<?php
/**
 * Slideshow
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * You can use "Description" field of images to set the custom link on them.
 *
 * @since  1.0
 *
 * @param  string class
 * @param  string ids
 * @param  string nav
 * @param  string size
 * @param  integer speed
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'class' => '',
			'ids'   => '',
			'nav'   => '',
			'size'  => 'large',
			'speed' => 3000,
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Validation
	//ids
		$atts['ids'] = str_replace( ' ', '', trim( $atts['ids'] ) );
		$atts['ids'] = array_filter( explode( ',', $atts['ids'] ) );
	//nav
		$atts['nav'] = trim( $atts['nav'] );
		if ( ! in_array( $atts['nav'], array( 'thumbs', 'pagination' ) ) ) {
			$atts['nav'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_default_nav', '' );
		}
	//size
		$atts['size']  = trim( $atts['size'] );
		$image_sizes   = get_intermediate_image_sizes();
		$image_sizes[] = 'large';
		if ( ! in_array( $atts['size'], $image_sizes ) ) {
			$atts['size'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_default_size', 'large' );
		}
	//speed
		$atts['speed'] = absint( $atts['speed'] );
		if ( 500 > $atts['speed'] ) {
			$atts['speed'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_default_speed', 3000 );
		}
	//content
		$atts['content'] = $atts['pager'] = '';
		if ( is_array( $atts['ids'] ) && ! empty( $atts['ids'] ) ) {
			$i = 0;
			foreach ( $atts['ids'] as $id ) {
				$image       = wp_get_attachment_image( $id, $atts['size'], false, array( 'title' => esc_attr( strip_tags( get_the_title( $id ) ) ) ) );
				$image_thumb = '<a data-slide-index="' . $i++ . '" href="">' . wp_get_attachment_image( $id, 'thumbnail' ) . '</a>';
				$link        = wp_get_attachment_image_src( $id, apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_image_modal_size', 'full' ) );
				$link        = $link[0];
				preg_match( '/http(.*)/', get_the_content( $id ), $matches );
				if ( isset( $matches[0] ) ) {
					$link = esc_url( trim( $matches[0] ) );
				}
				if ( $link ) {
					$image = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_image_html', '<a href="' . $link . '">' . $image . '</a>' );
				}
				$atts['content'] .= $image;
				$atts['pager']   .= $image_thumb;
			}
		}
		if ( $atts['content'] ) {
			$unique_id       = rand( 100, 999 );
			$opening_tag = '<div class="wm-slideshow-container" data-pager="';
			if ( 'thumbs' == $atts['nav'] ) {
				$opening_tag .= '#wm-slideshow-pager-' . $unique_id;
			}
			$opening_tag .= '">';
			$atts['content'] = $opening_tag . $atts['content'] . '</div>';
			if ( 'thumbs' == $atts['nav'] ) {
				$atts['content'] .= '<div id="wm-slideshow-pager-' . $unique_id . '" class="wm-slideshow-pager">' . $atts['pager'] . '</div>';
			}
		}
	//class
		$atts['class'] = trim( esc_attr( 'wm-slideshow clearfix ' . trim( $atts['class'] ) ) );
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', $atts['class'] );

//Enqueue scripts
	if ( apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_enqueue_scripts', true ) ) {
		wp_enqueue_script( 'wm-shortcodes-bxslider' );
		wp_enqueue_script( 'wm-shortcodes-slideshow' );
	}
	do_action( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_enqueue_scripts' );

//Output
	if ( $atts['content'] ) {
		$output = '<div class="' . $atts['class'] . '" data-speed="' . $atts['speed'] . '" data-nav="' . $atts['nav'] . '">' . $atts['content'] . '</div>';
	}

?>