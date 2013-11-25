<?php
/**
 * Testimonials
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @param  string align
 * @param  string category (testimonials category slug)
 * @param  string class
 * @param  integer columns
 * @param  integer count
 * @param  integer desc_column_size
 * @param  boolean masonry
 * @param  boolean no_margin
 * @param  string order
 * @param  boolean pagination
 * @param  integer scroll (value ranges: 0, 1-999, 1000+)
 * @param  string testimonial (ID or slug, if this is set, a single module will be displayed only)
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'align'            => 'left',
			'category'         => '',
			'class'            => '',
			'columns'          => 4,
			'count'            => -1,
			'desc_column_size' => 4,
			'masonry'          => false,
			'no_margin'        => false,
			'order'            => 'new',
			'pagination'       => false,
			'scroll'           => 0,
			'testimonial'      => '',
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Helper variables
	global $page, $paged;
	if ( ! isset( $paged ) ) {
		$paged = 1;
	}
	$paged                 = max( $page, $paged );
	$output                = '';
	$image_size            = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_image_size', 'thumbnail' );
	$posts_container_class = 'wm-testimonials-container wm-items-container';
	$content               = trim( $content );

//Validation
	//post_type
		$atts['post_type'] = 'wm_testimonials';
	//align
		$atts['align'] = ( 'right' === trim( $atts['align'] ) ) ? ( trim( $atts['align'] ) ) : ( 'left' );
	//category
		$atts['category'] = ( trim( $atts['category'] ) ) ? ( array( 'testimonial_category', trim( $atts['category'] ) ) ) : ( '' );
	//columns
		$atts['columns'] = absint( $atts['columns'] );
		if ( 1 > $atts['columns'] || 6 < $atts['columns'] ) {
			$atts['columns'] = 4;
		}
	//count
		$atts['count'] = intval( $atts['count'] );
	//desc_column_size
		$atts['desc_column_size'] = absint( $atts['desc_column_size'] );
		if ( 1 > $atts['desc_column_size'] || 6 < $atts['desc_column_size'] ) {
			$atts['desc_column_size'] = 4;
		}
	//masonry
		$atts['masonry'] = trim( $atts['masonry'] );
		if ( $atts['masonry'] ) {
			$posts_container_class .= ' masonry-this';
		}
	//no_margin
		$atts['no_margin'] = ( trim( $atts['no_margin'] ) ) ? ( ' no-margin' ) : ( '' );
	//order
		$atts['order'] = trim( $atts['order'] );
		$order_method = array(
				'new'    => array( 'date', 'DESC' ),
				'old'    => array( 'date', 'ASC' ),
				'name'   => array( 'title', 'ASC' ),
				'random' => array( 'rand', '' )
			);
		$atts['order'] = ( in_array( $atts['order'], array_keys( $order_method ) ) ) ? ( $order_method[ $atts['order'] ] ) : ( $order_method['new'] );
	//testimonial
		$atts['testimonial'] = trim( $atts['testimonial'] );
		if ( $atts['testimonial'] && is_numeric( $atts['testimonial'] ) ) {
			$atts['testimonial'] = array( 'p', absint( $atts['testimonial'] ) );
		} elseif ( $atts['testimonial'] ) {
			$atts['testimonial'] = array( 'name', $atts['testimonial'] );
		}
		if ( $atts['testimonial'] ) {
			$content            = '';
			$atts['pagination'] = false;
			$atts['scroll']     = 0;
		}
	//scroll
		$atts['scroll'] = absint( $atts['scroll'] );
		if ( $atts['scroll'] && 999 < $atts['scroll'] ) {
			$atts['class'] .= ' scrollable-auto';
		} elseif ( $atts['scroll'] ) {
			$atts['class'] .= ' scrollable-manual';
		}
	//class
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', esc_attr( trim( 'wm-testimonials wm-posts-wrap clearfix ' . trim( $atts['class'] ) ) ) );

//Preparing content
	//Get the posts
		//Set query arguments
			if ( $atts['testimonial'] ) {
				$query_args = array(
						'post_type'        => $atts['post_type'],
						$atts['testimonial'][0] => $atts['testimonial'][1],
					);
			} else {
				$query_args = array(
						'paged'               => $paged,
						'post_type'           => $atts['post_type'],
						'posts_per_page'      => $atts['count'],
						'ignore_sticky_posts' => 1,
						'orderby'             => $atts['order'][0],
						'order'               => $atts['order'][1]
					);
				if ( $atts['category'] ) {
					$query_args['tax_query'] = array( array(
						'taxonomy' => $atts['category'][0],
						'field'    => 'slug',
						'terms'    => explode( ',', $atts['category'][1] )
					) );
				}
			}

			//Allow filtering the query
				$query_args = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_query_args', $query_args );

		//Set query and loop through it
		$posts = new WP_Query( $query_args );

			//Set pagination output
				if ( $atts['pagination'] ) {
					$atts['pagination'] = wm_pagination( $posts );
				}

		if ( $posts->have_posts() ) {

			//Scrollable posts
				if ( $atts['scroll'] ) {

					$atts['masonry'] = false;

					//Set posts container class
						$posts_container_class .= ' stack';

				} // /if scroll

			//Posts grid container openings
				if ( ! $atts['testimonial'] ) {
					$posts_container_class = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_posts_container_class', $posts_container_class );
					if ( $content ) {
						if ( 'right' == $atts['align'] ) {
						//open posts container div only
							$output .= '<div class="wm-column width-' . ( $atts['desc_column_size'] - 1 ) . '-' . $atts['desc_column_size'] . '"><div class="' . $posts_container_class . '" data-columns="' . $atts['columns'] . '" data-time="' . absint( $atts['scroll'] ) . '">';
						} else {
						//insert posts description (shortcode content) in a column and open the posts container div
							$output .= '<div class="wm-column width-1-' . $atts['desc_column_size'] . ' wm-testimonials-description">' . do_shortcode( $content ) . '</div><div class="wm-column width-' . ( $atts['desc_column_size'] - 1 ) . '-' . $atts['desc_column_size'] . ' last"><div class="' . $posts_container_class . '" data-columns="' . $atts['columns'] . '" data-time="' . absint( $atts['scroll'] ) . '">';
						}
					} else {
						$output .= '<div class="' . $posts_container_class . '" data-columns="' . $atts['columns'] . '" data-time="' . absint( $atts['scroll'] ) . '">';
					}
				}

			//Row
				$output .= ( ! $atts['testimonial'] && ! $atts['masonry'] && ! $atts['scroll'] && 1 != $atts['columns'] ) ? ( '<div class="wm-row">' ) : ( '' );

			//Alternative item class and helper variables
				$alt = '';
				$row = $i = 0;

		//Loop the posts
			while ( $posts->have_posts() ) :
				$posts->the_post();

				//Row
					if ( ! $atts['testimonial'] && ! $atts['masonry'] && ! $atts['scroll'] && 1 != $atts['columns'] ) {
						$row     = ( ++$i % $atts['columns'] === 1 ) ? ( $row + 1 ) : ( $row );
						$output .= ( $i % $atts['columns'] === 1 && 1 < $row ) ? ( '</div><div class="wm-row">' ) : ( '' );
					}

				//Setting up custom link
					$link = '';
					$link_atts = array( wm_meta_option( 'link-page' ), wm_meta_option( 'link' ), wm_meta_option( 'link-action' ) );
					if ( $link_atts[0] ) {
						$page_object = get_page_by_path( $link_atts[0] );
						$link = ( $page_object ) ? ( ' href="' . get_permalink( $page_object->ID ) . '"' ) : ( '#' );
					} elseif ( $link_atts[1] ) {
						$link = ' href="' . esc_url( $link_atts[1] ) . '"';
					} else {
						$link = '';
					}
					if ( $link && $link_atts[2] ) {
						$link .= ( in_array( $link_atts[2], array( '_self', '_blank' ) ) ) ? ( ' target="' . esc_attr( $link_atts[2] ) . '"' ) : ( ' data-target="' . esc_attr( $link_atts[2] ) . '"' );
					}

				//Output the posts item
					$output_item = $class_item = '';

					//Testimonial content
						$output_item .= do_shortcode( '<blockquote class="wm-testimonials-element wm-html-element content">' . wpautop( preg_replace( '/<(\/?)blockquote(.*?)>/', '', get_the_content() ) ) . '</blockquote>' );
					//Testimonial author
						if ( trim( wm_meta_option( 'author' ) ) ) {
							$output_item .= '<cite class="wm-testimonials-element wm-html-element source">';
								$output_item .= ( $link ) ? ( '<a' . $link . '>' ) : ( '' );
									$output_item .= ( has_post_thumbnail() ) ? ( '<span class="wm-testimonials-element wm-html-element image image-container">' . get_the_post_thumbnail( get_the_id(), $image_size, array( 'title' => esc_attr( get_the_title( get_post_thumbnail_id( get_the_id() ) ) ) ) ) . '</span>' ) : ( '' );
									$output_item .= '<span class="wm-testimonials-element wm-html-element author">' . do_shortcode( strip_tags( wm_meta_option( 'author' ), '<a><em><i><img><mark><small><strong>' ) ) . '</span>';
								$output_item .= ( $link ) ? ( '</a>' ) : ( '' );
							$output_item .= '</cite>';
						}

					//filter the posts item html output
						$output_item = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_item_html', $output_item, get_the_id() );

					//posts single item output
						$class_item .= 'wm-testimonials-item wm-testimonials-item-' . get_the_id();
						if ( ! $atts['testimonial'] ) {
							$class_item .= ' wm-column width-1-' . $atts['columns'] . $atts['no_margin'] . $alt;
						}
						if (
								! $atts['testimonial']
								&& ! $atts['no_margin']
								&& ! $atts['masonry']
								&& ! $atts['scroll']
								&& ( $i % $atts['columns'] === 0 )
							) {
							$class_item .= ' last';
						}
						$terms = get_the_terms( get_the_ID(), 'testimonial_category' );
						if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
							foreach( $terms as $term ) {
								$class_item .= ' testimonial_category-' . $term->slug;
							}
						}
						$class_item  = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_item_class', $class_item, get_the_id() );
						$output_item = '<article class="' . $class_item . '">' . $output_item . '</article>';

				$output .= $output_item;

				$alt = ( $alt ) ? ( '' ) : ( ' alt' );

			endwhile;

			//Row
				$output .= ( ! $atts['testimonial'] && ! $atts['masonry'] && ! $atts['scroll'] && 1 != $atts['columns'] ) ? ( '</div>' ) : ( '' );

			//Posts grid container closings
				if ( ! $atts['testimonial'] ) {
					if ( $content ) {
						if ( 'right' == $atts['align'] ) {
						//close posts container div and output description column
							$output .= '</div>' . $atts['pagination'] . '</div><div class="wm-column width-1-' . $atts['desc_column_size'] . ' last wm-testimonials-description">' . do_shortcode( $content ) . '</div>';
						} else {
						//close the posts container div
							$output .= '</div>' . $atts['pagination'] . '</div>';
						}
					} else {
						$output .= '</div>' . $atts['pagination'];
					}
				}

		}

		//Reset query
			wp_reset_query();

		//Enqueue scripts
			if ( apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_enqueue_scripts', true ) ) {
				if ( $atts['scroll'] ) {
					wp_enqueue_script( 'wm-shortcodes-bxslider' );
					wp_enqueue_script( 'wm-shortcodes-posts' );
				} elseif ( $atts['masonry'] ) {
					wp_enqueue_script( 'jquery-masonry' );
					wp_enqueue_script( 'wm-shortcodes-posts' );
				}
			}
			do_action( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_enqueue_scripts' );

	$atts['content'] = $output;

//Output
	$output = '<div class="' . $atts['class'] . '">' . $atts['content'] . '</div>';

?>