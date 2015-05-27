<?php
/**
 * Slideshow
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * You can use "Description" field of images to set the custom link on them.
 *
 * @since    1.0
 * @version  1.2
 *
 * @param  string class
 * @param  string ids
 * @param  string nav
 * @param  string image_size
 * @param  integer speed
 */



//Shortcode attributes
	$defaults = apply_filters( 'wmhook_shortcode_' . '_defaults', array(
			'class'      => '',
			'ids'        => '',
			'nav'        => '',
			'image_size' => 'large',
			'speed'      => 3000,
		), $shortcode );
	$atts = apply_filters( 'wmhook_shortcode_' . '_attributes', $atts, $shortcode );
	$atts = shortcode_atts( $defaults, $atts, $prefix_shortcode . $shortcode );

//Validation
	//ids
		$atts['ids'] = str_replace( ' ', '', trim( $atts['ids'] ) );
		$atts['ids'] = array_filter( explode( ',', $atts['ids'] ) );
	//nav
		$atts['nav'] = trim( $atts['nav'] );
		if ( ! in_array( $atts['nav'], array( 'thumbs', 'pagination' ) ) ) {
			$atts['nav'] = apply_filters( 'wmhook_shortcode_' . $shortcode . '_nav', '', $atts );
		}
	//image_sizes
		$atts['image_size']  = trim( $atts['image_size'] );
		$image_sizes   = get_intermediate_image_sizes();
		$image_sizes[] = 'large';
		if ( ! in_array( $atts['image_size'], $image_sizes ) ) {
			$atts['image_size'] = apply_filters( 'wmhook_shortcode_' . $shortcode . '_image_size', 'large', $atts );
		}
	//speed
		$atts['speed'] = absint( $atts['speed'] );
		if ( 500 > $atts['speed'] ) {
			$atts['speed'] = apply_filters( 'wmhook_shortcode_' . $shortcode . '_speed', 3000, $atts );
		}
	//content
		$atts['content'] = $atts['pager'] = '';
		if ( is_array( $atts['ids'] ) && ! empty( $atts['ids'] ) ) {
			$i = 0;
			foreach ( $atts['ids'] as $id ) {
				$image       = wp_get_attachment_image( $id, $atts['image_size'], false, array( 'title' => esc_attr( strip_tags( get_the_title( $id ) ) ) ) );
				$image_thumb = '<a data-slide-index="' . $i++ . '" href="">' . wp_get_attachment_image( $id, 'thumbnail' ) . '</a>';
				$link        = wp_get_attachment_image_src( $id, apply_filters( 'wmhook_shortcode_' . $shortcode . '_modal_image_size', 'full', $atts ) );
				$link        = $link[0];

				$image_description = get_post( $id );
				if ( is_object( $image_description ) && isset( $image_description->post_content ) ) {
					preg_match( '/http(.*)/', $image_description->post_content, $matches );
					if ( isset( $matches[0] ) ) {
						$link = esc_url( trim( $matches[0] ) );
					}
				}

				if ( $link ) {
					$image = apply_filters( 'wmhook_shortcode_' . $shortcode . '_image_html', '<a href="' . esc_url( $link ) . '">' . $image . '</a>', $atts );
				}

				$atts['content'] .= $image;
				$atts['pager']   .= $image_thumb;
			}
		}
		if ( $atts['content'] ) {
			$unique_id   = rand( 100, 999 );
			$opening_tag = '<div class="wm-slideshow-container" data-pager="';
			if ( 'thumbs' == $atts['nav'] ) {
				$opening_tag .= '#wm-slideshow-pager-' . $unique_id;
			}
			$opening_tag .= '">';
			$atts['content'] = $opening_tag . $atts['content'] . '</div>';
			if ( 'thumbs' == $atts['nav'] ) {
				$atts['content'] .= '<div id="wm-slideshow-pager-' . esc_attr( $unique_id ) . '" class="wm-slideshow-pager">' . $atts['pager'] . '</div>';
			}
		}
	//content filters
		$atts['content'] = apply_filters( 'wmhook_shortcode_' . '_content', $atts['content'], $shortcode, $atts );
		$atts['content'] = apply_filters( 'wmhook_shortcode_' . $shortcode . '_content', $atts['content'], $atts );
	//class
		$atts['class'] = trim( esc_attr( 'wm-slideshow clearfix auto-height ' . trim( $atts['class'] ) ) );
		$atts['class'] = apply_filters( 'wmhook_shortcode_' . $shortcode . '_classes', $atts['class'], $atts );

//Enqueue scripts
	$enqueue_scripts = array(
			'jquery-owl-carousel',
			'wm-shortcodes-slideshow'
		);

	wma_shortcode_enqueue_scripts( $shortcode, $enqueue_scripts, $atts );

//Output
	if ( $atts['content'] ) {
		$output = '<div class="' . esc_attr( $atts['class'] ) . '" data-speed="' . esc_attr( $atts['speed'] ) . '" data-nav="' . esc_attr( $atts['nav'] ) . '">' . $atts['content'] . '</div>';
	}

?>