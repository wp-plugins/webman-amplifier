<?php
/**
 * Posts and custom post types
 *
 * This file is being included into "../class-shortcodes.php" file's shortcode_render() method.
 *
 * @since  1.0
 *
 * @uses   $codes_globals['post_types']
 *
 * @param  string align
 * @param  string class
 * @param  integer columns
 * @param  integer count
 * @param  integer desc_column_size
 * @param  string filter (example: "taxonomy_name:taxonomy_slug" - "taxonomy_slug" is optional and will act like parent category)
 * @param  string filter_layout
 * @param  string image_size
 * @param  string layout (available options: contacts, content, excerpt:##, image, meta:date+comments, morelink, taxonomy:tax_name, title)
 * @param  boolean no_margin
 * @param  string order
 * @param  boolean pagination
 * @param  string post_type
 * @param  string related (example: "taxonomy_name" - a taxonomy which should be considered as for related posts)
 * @param  integer scroll (value ranges: 0, 1-999, 1000+)
 * @param  string taxonomy (example: "taxonomy_name:taxonomy_slug" - both are required)
 */



//Shortcode attributes
	$defaults = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_attributes', array(
			'align'            => 'left',
			'class'            => '',
			'columns'          => 4,
			'count'            => -1,
			'desc_column_size' => 4,
			'filter'           => '',
			'filter_layout'    => 'fitRows',
			'image_size'       => '',
			'layout'           => '',
			'no_margin'        => false,
			'order'            => 'new',
			'pagination'       => false,
			'post_type'        => 'post',
			'related'          => '',
			'scroll'           => 0,
			'taxonomy'         => '',
		) );
	$atts = shortcode_atts( $defaults, $atts, $this->prefix_shortcode . $shortcode );

//Helper variables
	global $page, $paged;
	if ( ! isset( $paged ) ) {
		$paged = 1;
	}
	$paged                 = max( $page, $paged );
	$output                = $filter_content = '';
	$image_size            = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_image_size', 'medium' );
	$excerpt_length        = 10;
	$filter_settings       = false;
	$posts_container_class = 'wm-posts-container wm-items-container';
	$content               = trim( $content );

	//Set layouts for custom post types
		$layouts = array(
				'default'     => array( 'image', 'title', 'excerpt', 'morelink' ),
				'post'        => array( 'image', 'title', 'meta:date+comments', 'excerpt', 'taxonomy:category', 'morelink' ),
				'wm_logos'    => array( 'image' ),
				'wm_projects' => array( 'image', 'title', 'taxonomy:project_category', 'excerpt' ),
				'wm_staff'    => array( 'image', 'title', 'taxonomy:staff_position', 'content', 'contacts' ),
			);
		$layouts = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_layouts', $layouts );

//Validation
	//post_type
		$atts['post_type'] = trim( $atts['post_type'] );
		$image_size        = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_image_size_' . $atts['post_type'], $image_size );
	//align
		$atts['align'] = ( 'right' === trim( $atts['align'] ) ) ? ( trim( $atts['align'] ) ) : ( 'left' );
	//columns
		$atts['columns'] = absint( $atts['columns'] );
		$max_columns     = ( 'wm_logos' == $atts['post_type'] ) ? ( 9 ) : ( 6 );
		if ( 1 > $atts['columns'] || $max_columns < $atts['columns'] ) {
			$atts['columns'] = 4;
		}
	//count
		$atts['count'] = intval( $atts['count'] );
	//desc_column_size
		$atts['desc_column_size'] = absint( $atts['desc_column_size'] );
		if ( 1 > $atts['desc_column_size'] || 6 < $atts['desc_column_size'] ) {
			$atts['desc_column_size'] = 4;
		}
	//filter
		$atts['filter'] = trim( $atts['filter'] );
		if ( strpos( $atts['filter'], ':' ) && ! $atts['taxonomy'] ) {
			$atts['taxonomy'] = $atts['filter'];
		}
	//filter_layout
		$atts['filter_layout'] = trim( $atts['filter_layout'] );
		if ( ! $atts['filter_layout'] ) {
			$atts['filter_layout'] = 'fitRows';
		}
	//image_size
		$atts['image_size'] = trim( $atts['image_size'] );
		if ( $atts['image_size'] ) {
			$image_size = $atts['image_size'];
		}
	//layout
		$atts['layout'] = explode( ',', str_replace( ' ', '', $atts['layout'] ) );
		$atts['layout'] = array_filter( $atts['layout'] );
		if ( empty( $atts['layout'] ) ) {
			$layout = ( ! in_array( $atts['post_type'], array_keys( $codes_globals['post_types'] ) ) ) ? ( 'default' ) : ( $atts['post_type'] );
			$atts['layout'] = $layouts[ $layout ];
		}
		foreach ( $atts['layout'] as $key => $layout ) {
			if ( strpos( $layout, ':' ) ) {
				$layout = explode( ':', trim( $layout ) );
				if ( 'meta' == $layout[0] ) {
					$layout[1] = explode( '+', $layout[1] );
					$layout[1] = array_filter( $layout[1] );
				}
				$atts['layout'][$layout[0]] = $layout[1];
			} else {
				$atts['layout'][$layout] = '';
			}
			unset( $atts['layout'][$key] );
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
	//scroll
		$atts['scroll'] = absint( $atts['scroll'] );
		if ( ! $atts['filter'] ) {
			if ( $atts['scroll'] && 999 < $atts['scroll'] ) {
				$atts['class'] .= ' scrollable-auto';
			} elseif ( $atts['scroll'] ) {
				$atts['class'] .= ' scrollable-manual';
			}
		}
	//taxonomy
		$atts['taxonomy'] = explode( ':', trim( $atts['taxonomy'] ) );
	//related
		$atts['related'] = trim( $atts['related'] );
		if (
				$atts['related']
				&& get_the_ID()
				&& taxonomy_exists( $atts['related'] )
			) {
			$atts['taxonomy'] = $atts['related'] . ':';

			$terms = get_the_terms( get_the_ID() , $atts['related'] );
			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$separator = '';
				foreach( $terms as $term ) {
					$atts['taxonomy'] .= $separator . $term->slug;
					$separator = ',';
				}
			}
		}
	//class
		$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_classes', esc_attr( trim( 'wm-posts wm-posts-wrap clearfix wm-posts-' . $atts['post_type'] . ' ' . trim( $atts['class'] ) ) ) );

//Preparing content
	//Get the posts
		//Set query arguments
			$query_args = array(
					'paged'               => $paged,
					'post_type'           => $atts['post_type'],
					'posts_per_page'      => $atts['count'],
					'ignore_sticky_posts' => 1,
					'orderby'             => $atts['order'][0],
					'order'               => $atts['order'][1]
				);
			if (
					is_array( $atts['taxonomy'] )
					&& 2 === count( $atts['taxonomy'] )
					&& taxonomy_exists( $atts['taxonomy'][0] )
				) {
				$query_args['tax_query'] = array( array(
					'taxonomy' => $atts['taxonomy'][0],
					'field'    => 'slug',
					'terms'    => explode( ',', $atts['taxonomy'][1] )
				) );
			}
			if ( $atts['related'] && get_the_ID() ) {
				$query_args['post__not_in'] = array( get_the_ID() );
			}

			//Allow filtering the query
				$query_args = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_query_args', $query_args );
				$query_args = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_query_args_' . $atts['post_type'], $query_args );

		//Set query and loop through it
		$posts = new WP_Query( $query_args );

			//Set pagination output
				if ( $atts['pagination'] ) {
					$atts['pagination'] = wm_pagination( $posts );
				}

		if ( $posts->have_posts() ) {

			//Filter HTML
				if ( $atts['filter'] ) {

					//Prepare the filter taxonomy settings
						$atts['filter'] = explode( ':', $atts['filter'] );

					//Continue only if the taxonomy exists
						if ( taxonomy_exists( $atts['filter'][0] ) ) {

							//Check if parent taxonomy set, if not, set empty one
								if (
										! is_taxonomy_hierarchical( $atts['filter'][0] )
										|| ! isset( $atts['filter'][1] )
									) {
									$atts['filter'][1] = '';
								}

							//Save the filter taxonomy settings for later use
								$filter_settings = $atts['filter'];

							if ( $atts['filter'][1] ) {
							//If parent taxonomy set - filter from child taxonomies

								//"All" item
								$parent_tax = get_term_by( 'slug', $atts['filter'][1], $atts['filter'][0] );
								$filter_content .= '<li class="wm-filter-items-all active"><a href="#" data-filter="*">' . sprintf( __( 'All <span>%s</span>', 'wm_domain' ), $parent_tax->name ) . '</a></li>';

								//Other items
								$terms  = get_term_children( $parent_tax->term_id, $atts['filter'][0] );
								$count  = count( $terms );
								if ( ! is_wp_error( $terms ) && 0 < $count ) {
									$output_array = array();
									foreach ( $terms as $child ) {
										$child = get_term_by( 'id', $child, $atts['filter'][0] );
										$output_array['<li class="wm-filter-items-' . $child->slug . '"><a href="#" data-filter=".' . $atts['filter'][0] . '-' . $child->slug . '">' . $child->name . '<span class="count"> (' . $child->count . ')</span></a></li>'] = $child->name;
									}
									asort( $output_array );
									$output_array = array_flip( $output_array );
									$filter_content .= implode( '', $output_array );
								}

							} else {
							//No parent taxonomy - filter from all taxonomies

								//"All" item
								$filter_content .= '<li class="wm-filter-items-all active"><a href="#" data-filter="*">' . __( 'All', 'wm_domain' ) . '</a></li>';

								//Other items
								$terms = get_terms( $atts['filter'][0] );
								$count = count( $terms );
								if ( ! is_wp_error( $terms ) && 0 < $count ) {
									foreach ( $terms as $term ) {
										$filter_content .= '<li class="wm-filter-items-' . $term->slug . '"><a href="#" data-filter=".' . $atts['filter'][0] . '-' . $term->slug . '">' . $term->name . '<span class="count"> (' . $term->count . ')</span></a></li>';
									}
								}

							}

							$filter_content = '<div class="wm-filter"><ul>' . $filter_content . '</ul></div>';

							//Set posts container class
								$posts_container_class .= ' filter-this';

							//Filter is prioritized against scrolling functionality, so just turn it off
								$atts['scroll'] = 0;

						} // /check if taxonomy exists

				} // /if filter

				$atts['filter'] = $filter_content;

			//Scrollable posts
				if ( $atts['scroll'] ) {

					//Set posts container class
						$posts_container_class .= ' stack';

				} // /if scroll

			//Posts grid container openings
				$posts_container_class = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_posts_container_class', $posts_container_class );
				if ( $content ) {
					if ( 'right' == $atts['align'] ) {
					//open posts container div only
						$output .= '<div class="wm-column width-' . ( $atts['desc_column_size'] - 1 ) . '-' . $atts['desc_column_size'] . '">' . $atts['filter'] . '<div class="' . $posts_container_class . '" data-columns="' . $atts['columns'] . '" data-time="' . absint( $atts['scroll'] ) . '" data-layout-mode="' . $atts['filter_layout'] . '">';
					} else {
					//insert posts description (shortcode content) in a column and open the posts container div
						$output .= '<div class="wm-column width-1-' . $atts['desc_column_size'] . ' wm-posts-description">' . do_shortcode( $content ) . '</div><div class="wm-column width-' . ( $atts['desc_column_size'] - 1 ) . '-' . $atts['desc_column_size'] . ' last">' . $atts['filter'] . '<div class="' . $posts_container_class . '" data-columns="' . $atts['columns'] . '" data-time="' . absint( $atts['scroll'] ) . '" data-layout-mode="' . $atts['filter_layout'] . '">';
					}
				} else {
					$output .= $atts['filter'] . '<div class="' . $posts_container_class . '" data-columns="' . $atts['columns'] . '" data-time="' . absint( $atts['scroll'] ) . '" data-layout-mode="' . $atts['filter_layout'] . '">';
				}

			//Row
				$output .= ( ! $atts['filter'] && ! $atts['scroll'] && 1 != $atts['columns'] ) ? ( '<div class="wm-row">' ) : ( '' );

			//Alternative item class and helper variables
				$alt = '';
				$row = $i = 0;

		//Loop the posts
			while ( $posts->have_posts() ) :
				$posts->the_post();

				//Row
					if ( ! $atts['filter'] && ! $atts['scroll'] && 1 != $atts['columns'] ) {
						$row     = ( ++$i % $atts['columns'] === 1 ) ? ( $row + 1 ) : ( $row );
						$output .= ( $i % $atts['columns'] === 1 && 1 < $row ) ? ( '</div><div class="wm-row">' ) : ( '' );
					}

				//Setting up layout elements HTML
					$link = '';
					$link_atts = array( wm_meta_option( 'link-page' ), wm_meta_option( 'link' ), wm_meta_option( 'link-action' ) );
					if ( 'wm_projects' == $atts['post_type'] && ! $link_atts[2] ) {
						$link = ' href="' . get_permalink() . '"';
					} elseif ( $link_atts[0] ) {
						$page_object = get_page_by_path( $link_atts[0] );
						$link = ( $page_object ) ? ( ' href="' . get_permalink( $page_object->ID ) . '"' ) : ( '#' );
					} elseif ( $link_atts[1] ) {
						$link = ' href="' . esc_url( $link_atts[1] ) . '"';
					} else {
						$link = ' href="' . get_permalink() . '"';
					}
					if ( 'wm_staff' == $atts['post_type'] && ! $link_atts[0] && ! $link_atts[1] ) {
						$link = '';
					}
					if ( $link_atts[2] ) {
						$link .= ( in_array( $link_atts[2], array( '_self', '_blank' ) ) ) ? ( ' target="' . esc_attr( $link_atts[2] ) . '"' ) : ( ' data-target="' . esc_attr( $link_atts[2] ) . '"' );
					}
					$helpers = array(
							'link'           => $link,
							'excerpt_length' => ( isset( $atts['layout']['excerpt'] ) && '' != $atts['layout']['excerpt'] ) ? ( absint( $atts['layout']['excerpt'] ) ) : ( $excerpt_length ),
							'taxonomy'       => ( isset( $atts['layout']['taxonomy'] ) ) ? ( trim( $atts['layout']['taxonomy'] ) ) : ( '' ),
							'meta'           => ( isset( $atts['layout']['meta'] ) ) ? ( $atts['layout']['meta'] ) : ( array() ),
						);
					$helpers = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_layout_elements_helpers', $helpers, get_the_id(), $atts );
					$layout_elements = array(
							'contacts' => '',
							'content'  => do_shortcode( '<div class="wm-posts-element wm-html-element content">' . wpautop( get_the_content() ) . '</div>' ),
							'excerpt'  => '',
							'image'    => '',
							'meta'     => '',
							'morelink' => '<div class="wm-posts-element wm-html-element read-more"><a href="' . get_permalink() . '">' . __( 'Read more', 'wm_domain' ) . '</a></div>',
							'taxonomy' => '',
							'title'    => ( $helpers['link'] ) ? ( '<header class="wm-posts-element wm-html-element title"><h3><a' . $helpers['link'] . '>' . get_the_title() . '</a></h3></header>' ) : ( '<header class="wm-posts-element wm-html-element title"><h3>' . get_the_title() . '</h3></header>' ),
						);
					//contact layout element
						$staff_contacts = wm_meta_option( 'contacts' );
						if ( $staff_contacts && is_array( $staff_contacts ) ) {
							$layout_elements['contacts'] = '<ul class="wm-posts-element wm-html-element contacts">';
							foreach ( $staff_contacts as $contact ) {
								if (
										! isset( $contact['icon'] )
										|| ! isset( $contact['title'] )
										|| ! isset( $contact['content'] )
									) {
									continue;
								}
								$layout_elements['contacts'] .= '<li class="contacts-item ' . $contact['icon'] . '" title="' . $contact['title'] . '">' . $contact['content'] . '</li>';
							}
							$layout_elements['contacts'] .= '</ul>';
						}
					//excerpt layout element
						if ( 0 < $helpers['excerpt_length'] ) {
							$layout_elements['excerpt'] = '<div class="wm-posts-element wm-html-element excerpt">' . wp_trim_words( get_the_excerpt(), $helpers['excerpt_length'], '&hellip;' ) . '</div>';
						}
					//image layout element
						if ( has_post_thumbnail() ) {
							$layout_elements['image'] = '<div class="wm-posts-element wm-html-element image image-container"><a' . $helpers['link'] . '>' . get_the_post_thumbnail( get_the_id(), $image_size, array( 'title' => esc_attr( get_the_title( get_post_thumbnail_id( get_the_id() ) ) ) ) ) . '</a></div>';
						}
					//meta layout element
						if ( ! empty( $helpers['meta'] ) ) {
							$layout_elements['meta']  = '<footer class="wm-posts-element wm-html-element meta">';
							foreach ( $helpers['meta'] as $meta ) {
								switch ( $meta ) {
									case 'date':
										$layout_elements['meta'] .= '<time class="meta-date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
										break;
									case 'comments':
										$layout_elements['meta'] .= '<a class="meta-comments" href="' . get_comments_link() . '" title="' . __( 'Comments: ', 'wm_domain' ) . get_comments_number() . '">' . get_comments_number() . '</a>';
										break;
									default:
										break;
								}
							}
							$layout_elements['meta'] .= '</footer>' ;
						}
					//taxonomy layout element
						if ( $helpers['taxonomy'] ) {
							$terms       = get_the_terms( get_the_ID(), $helpers['taxonomy'] );
							$terms_array = array();
							if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
								foreach( $terms as $term ) {
									$terms_array[] = '<span class="term term-' . sanitize_html_class( $term->slug ) . '">' . $term->name . '</span>';
								}
								$layout_elements['taxonomy'] = '<div class="wm-posts-element wm-html-element taxonomy">' . implode( ', ', $terms_array ) . '</div>' ;
							}
						}
					//filter the elements html
						$layout_elements = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_layout_elements', $layout_elements, get_the_id(), $helpers );

				//Output the posts item
					$output_item = $class_item = '';
					foreach ( array_keys( $atts['layout'] ) as $layout_element ) {
						if ( isset( $layout_elements[$layout_element] ) ) {
							$output_item .= $layout_elements[$layout_element];
						}
					}

					//filter the posts item html output
						$output_item = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_item_html', $output_item, get_the_id() );
						$output_item = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_item_html_' . $atts['post_type'], $output_item, get_the_id() );

					//posts single item output
						$class_item .= 'wm-posts-item wm-posts-item-' . get_the_id() . ' wm-column width-1-' . $atts['columns'] . $atts['no_margin'] . $alt;
						if (
								! $atts['no_margin']
								&& ! $atts['filter']
								&& ! $atts['scroll']
								&& ( $i % $atts['columns'] === 0 )
							) {
							$class_item .= ' last';
						}
						if ( $atts['filter'] && isset( $filter_settings[0] ) ) {
							$terms = get_the_terms( get_the_ID() , $filter_settings[0] );
							if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
								foreach( $terms as $term ) {
									$class_item .= ' ' . $filter_settings[0] . '-' . $term->slug;
								}
							}
						}
						$class_item  = apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_item_class', $class_item, get_the_id() );
						$output_item = '<article class="' . $class_item . '">' . $output_item . '</article>';

				$output .= $output_item;

				$alt = ( $alt ) ? ( '' ) : ( ' alt' );

			endwhile;

			//Row
				$output .= ( ! $atts['filter'] && ! $atts['scroll'] && 1 != $atts['columns'] ) ? ( '</div>' ) : ( '' );

			//Posts grid container closings
				if ( $content ) {
					if ( 'right' == $atts['align'] ) {
					//close posts container div and output description column
						$output .= '</div>' . $atts['pagination'] . '</div><div class="wm-column width-1-' . $atts['desc_column_size'] . ' last wm-posts-description">' . do_shortcode( $content ) . '</div>';
					} else {
					//close the posts container div
						$output .= '</div>' . $atts['pagination'] . '</div>';
					}
				} else {
					$output .= '</div>' . $atts['pagination'];
				}

		}

		//Reset query
			wp_reset_query();

		//Enqueue scripts
			if ( apply_filters( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_enqueue_scripts', true ) ) {
				if ( $atts['scroll'] ) {
					wp_enqueue_script( 'wm-shortcodes-bxslider' );
					wp_enqueue_script( 'wm-shortcodes-posts' );
				} elseif ( $atts['filter'] ) {
					wp_enqueue_script( 'wm-shortcodes-isotope' );
					wp_enqueue_script( 'wm-shortcodes-posts' );
				}
			}
			do_action( WM_SHORTCODES_HOOK_PREFIX . $shortcode . '_enqueue_scripts' );

	$atts['content'] = $output;

//Output
	$output = '<div class="' . $atts['class'] . '">' . $atts['content'] . '</div>';

?>