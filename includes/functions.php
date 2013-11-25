<?php
/**
 * WebMan Amplifier global helper functions
 *
 * @package  WebMan Amplifier
 */



	/**
	 * Theme subfeature support checker
	 *
	 * @since   1.0
	 *
	 * @param   string $feature
	 *
	 * @return  array Empty array if the theme doesn't support the feature or array of supported subfeatures.
	 */
	if ( ! function_exists( 'wm_current_theme_supports_subfeatures' ) ) {
		function wm_current_theme_supports_subfeatures( $feature = '' ) {
			if ( ! trim( $feature ) ) {
				return false;
			}

			//Default output setup
				$output = array();

			//Processing
				if ( current_theme_supports( $feature ) ) {
					$theme_supports = get_theme_support( $feature );
					if ( isset( $theme_supports[0] ) ) {
						$output = (array) $theme_supports[0];
					}
				}

			//Output
				return $output;
		}
	} // /wm_current_theme_supports_subfeatures



	/**
	 * Create permalinks settings field in WordPress admin
	 *
	 * @since  1.0
	 *
	 * @param  array $args
	 */
	if ( ! function_exists( 'wm_permalinks_render_field' ) ) {
		function wm_permalinks_render_field( $args = array() ) {
			//Processing arguments
				if ( isset( $args['name'] ) && $args['name'] ) {
					$args['name'] = trim( $args['name'] );
				} else {
					return;
				}
				if ( isset( $args['placeholder'] ) && $args['placeholder'] ) {
					$args['placeholder'] = trim( $args['placeholder'] );
				}

			//Get value
				$value = get_option( 'wmamp-permalinks' );
				if ( is_array( $value ) && isset( $value[ $args['name'] ] ) ) {
					$value = urlencode( untrailingslashit( $value[ $args['name'] ] ) );
				} else {
					$value = '';
				}

			//Output
				echo '<input name="wmamp-permalinks[' . $args['name'] . ']" type="text" value="' . $value . '" placeholder="' . $args['placeholder'] . '" class="regular-text code" />';
		}
	} // /wm_permalinks_render_field



	/**
	 * Create a folder
	 *
	 * @since  1.0
	 *
	 * @param  array $args
	 */
	if ( ! function_exists( 'wm_create_folder' ) ) {
		function wm_create_folder( $folder = '', $add_indexphp = true, $chmod = 0755 ) {
			//Check if folder exists already
				if ( is_dir( $folder ) && ! $add_indexphp ) {
					return true;
				}

			//Let WordPress to create a folder
				$created = wp_mkdir_p( trailingslashit( $folder ) );

			//Set privilegues
				@chmod( $folder, $chmod );

			//Need for index.php file inside the folder?
				if ( ! $add_indexphp ) {
					return $created;
				} else {
					$index_file = trailingslashit( $folder ) . 'index.php';

					if ( file_exists( $index_file ) ) {
						return $created;
					} else {
						$file_handle = @fopen( $index_file, 'w' );
						if ( $file_handle ) {
							fwrite( $file_handle, "<?php // Silence is golden ?>" );
							fclose( $file_handle );
						}
					}

					@chmod( $index_file, 0644 );
				}

			//Return
				return $created;
		}
	} // /wm_create_folder



	/**
	 * Get post meta option
	 *
	 * @since   1.0
	 *
	 * @param   string $name Meta option name.
	 * @param   integer $post_id Specific post ID.
	 *
	 * @return  mixed
	 */
	if ( ! function_exists( 'wm_meta_option' ) ) {
		function wm_meta_option( $name, $post_id = null ) {
			//Helper variables
				$output = '';

			//Check for premature exit
				$post_id = absint( $post_id );

				if ( ! $post_id ) {
					global $post;
					if ( isset( $post->ID ) ) {
						$post_id = $post->ID;
					}
				}

				if ( ! trim( $name ) || ! $post_id ) {
					return apply_filters( WMAMP_HOOK_PREFIX . 'meta_option_' . $name, $output );
				}

			//Output
				$meta = get_post_meta( $post_id, WM_METABOX_SERIALIZED_NAME, true );
				$name = WM_METABOX_FIELD_PREFIX . $name;

				if ( isset( $meta[$name] ) && $meta[$name] ) {
					if ( is_array( $meta[$name] ) ) {
						$output = $meta[$name];
					} else {
						$output = stripslashes( $meta[$name] );
					}
				}

				return apply_filters( WMAMP_HOOK_PREFIX . 'meta_option_' . $name, $output );
		}
	} // /wm_meta_option



	/**
	 * Taxonomy list
	 *
	 * @since   1.0
	 *
	 * @param   array $args
	 *
	 * @return  array Array of taxonomy slug => name.
	 */
	if ( ! function_exists( 'wm_taxonomy_array' ) ) {
		function wm_taxonomy_array( $args = array() ) {
			$args = wp_parse_args( $args, array(
					//"All" option
						'all'           => true,                           //whether to display "all" option
						'all_post_type' => 'post',                         //post type to count posts for "all" option, if left empty, the posts count will not be displayed
						'all_text'      => __( 'All posts', 'wm_domain' ), //"all" option text
					//Query settings
						'hierarchical'  => '1',                            //whether taxonomy is hierarchical
						'order_by'      => 'name',                         //in which order the taxonomy titles should appear
						'parents_only'  => false,                          //will return only parent (highest level) categories
						'hide_empty'    => 0,                              //whether to display only used taxonomies
					//Default returns
						'return'        => 'slug',                         //what to return as a value (slug, or term_id?)
						'tax_name'      => 'category',                     //taxonomy name
				) );

			//Helper variables
				$output = array();

			//Check
				if ( ! taxonomy_exists( $args['tax_name'] ) ) {
					return apply_filters( WMAMP_HOOK_PREFIX . 'taxonomy_array', $output );
				}

			//Get terms
				$terms  = get_terms( $args['tax_name'], 'orderby=' . $args['order_by'] . '&hide_empty=' . $args['hide_empty'] . '&hierarchical=' . $args['hierarchical'] );

			//Preparing output array
				if ( $args['all'] ) {
				//Set "All" option
					if ( ! $args['all_post_type'] ) {
						$all_count = '';
					} else {
						$readable  = ( in_array( $args['all_post_type'], array( 'post', 'page' ) ) ) ? ( 'readable' ) : ( null );
						$all_count = wp_count_posts( $args['all_post_type'], $readable );
						$all_count = ' (' . absint( $all_count->publish ) . ')';
					}
					$output[''] = apply_filters( WMAMP_HOOK_PREFIX . 'taxonomy_array_all', '- ' . $args['all_text'] . $all_count . ' -', $args['all_text'], $all_count );
				}

				if ( ! is_wp_error( $terms ) && is_array( $terms ) && ! empty( $terms ) ) {
					foreach ( $terms as $term ) {
						if ( ! $args['parents_only'] ) {
						//All taxonomies (categories) including children
							$output[$term->$args['return']]  = $term->name;
							$output[$term->$args['return']] .= ( ! $args['all_post_type'] ) ? ( '' ) : ( apply_filters( WMAMP_HOOK_PREFIX . 'taxonomy_array_count', ' (' . $term->count . ')', $term->count ) );
						} elseif ( $args['parents_only'] && ! $term->parent ) {
						//Get only parent taxonomies (categories), no children
							$output[$term->$args['return']]  = $term->name;
							$output[$term->$args['return']] .= ( ! $args['all_post_type'] ) ? ( '' ) : ( apply_filters( WMAMP_HOOK_PREFIX . 'taxonomy_array_count', ' (' . $term->count . ')', $term->count ) );
						}
					}
				}

			//Output
				return apply_filters( WMAMP_HOOK_PREFIX . 'taxonomy_array', $output );
		}
	} // /wm_taxonomy_array



	/**
	 * Posts list - returns array [post_name (slug) => name]
	 *
	 * @since   1.0
	 *
	 * @param   string $return What field to return ('post_name' or 'ID').
	 * @param   string $post_type What custom post type to return (defaults to "post").
	 *
	 * @return  array Array of post slug => name.
	 */
	if ( ! function_exists( 'wm_posts_array' ) ) {
		function wm_posts_array( $return = 'post_name', $post_type = 'post' ) {
			//Helper variables
				$args = array(
						'posts_per_page' => -1,
						'orderby'        => 'title',
						'order'          => 'ASC',
						'post_type'      => $post_type,
						'post_status'    => 'publish',
					);
				$posts  = get_posts( $args );
				$output = array();

			//Check
				if ( ! post_type_exists( $post_type ) ) {
					return apply_filters( WMAMP_HOOK_PREFIX . 'posts_array', $output );
				}

			//Preparing output array
				$output[''] = apply_filters( WMAMP_HOOK_PREFIX . 'posts_array_select_text', __( '- Select item -', 'wm_domain' ) );

				if ( is_array( $posts ) && ! empty( $posts ) ) {
					foreach ( $posts as $post ) {
						//Set return parameter
							$return_param = ( 'post_name' == $return ) ? ( $post->post_name ) : ( $post->ID );

						$output[$return_param] = trim( strip_tags( $post->post_title ) );
					}
				}

			//Output
				return apply_filters( WMAMP_HOOK_PREFIX . 'posts_array', $output );
		}
	} // /wm_posts_array



	/**
	 * Pages list - returns array [post_name (slug) => name]
	 *
	 * @since   1.0
	 *
	 * @param   string $return What field to return ('post_name' or 'ID').
	 *
	 * @return  array Array of page slug => name.
	 */
	if ( ! function_exists( 'wm_pages_array' ) ) {
		function wm_pages_array( $return = 'post_name' ) {
			//Helper variables
				$pages  = get_pages();
				$output = array();

			//Preparing output array
				$output[''] = apply_filters( WMAMP_HOOK_PREFIX . 'pages_array_select_text', __( '- Select a page -', 'wm_domain' ) );

				if ( is_array( $pages ) && ! empty( $pages ) ) {
					foreach ( $pages as $page ) {
						$indents   = $page_path = '';
						$ancestors = get_post_ancestors( $page->ID );

						if ( ! empty( $ancestors ) ) {
						//Process ancestors
							$indent    = ( $page->post_parent ) ? ( '&ndash; ' ) : ( '' );
							$ancestors = array_reverse( $ancestors ); //Need to reverse the array for proper usage in get_page_by_path() function.
							foreach ( $ancestors as $ancestor ) {
								if ( 'post_name' == $return ) {
									$parent     = get_page( $ancestor );
									$page_path .= $parent->post_name . '/';
								}
								$indents .= $indent;
							}
						}

						$page_path .= $page->post_name;

						//Set return parameter
							$return_param = ( 'post_name' == $return ) ? ( $page_path ) : ( $page->ID );

						$output[$return_param] = $indents . trim( strip_tags( $page->post_title ) );
					}
				}

			//Output
				return apply_filters( WMAMP_HOOK_PREFIX . 'pages_array', $output );
		}
	} // /wm_pages_array



	/**
	 * Get array of widget areas
	 *
	 * @since   1.0
	 *
	 * @return  array Array of widget area id => name.
	 */
	if ( ! function_exists( 'wm_widget_areas_array' ) ) {
		function wm_widget_areas_array() {
			//Helper variables
				global $wp_registered_sidebars;

				$output = array();

			//Preparing output array
				$output[''] = apply_filters( WMAMP_HOOK_PREFIX . 'widget_areas_array_select_text', __( '- Select area -', 'wm_domain' ) );

				if ( is_array( $wp_registered_sidebars ) && ! empty( $wp_registered_sidebars ) ) {
					foreach ( $wp_registered_sidebars as $area ) {
						$output[ $area['id'] ] = $area['name'];
					}
				}

				//Sort alphabetically
					asort( $output );

			//Output
				return apply_filters( WMAMP_HOOK_PREFIX . 'widget_areas_array', $output );
		}
	} // /wm_widget_areas_array



	/**
	 * Sidebar (display widget area)
	 *
	 * @since   1.0
	 *
	 * @param   array $atts Setup attributes.
	 *
	 * @return  Sidebar HTML (with a special class of number of included widgets).
	 */
	if ( ! function_exists( 'wm_sidebar' ) ) {
		function wm_sidebar( $atts = array() ) {
			//Set default setting attributes
				$atts = wp_parse_args( $atts, array(
						'class'             => '',
						'max_widgets_count' => 0,
						'sidebar'           => 'sidebar-1',
						'wrapper'           => array(
								'open'  => '',
								'close' => '',
							),
					) );

			//Helper variables
				$output = '';

			//Validation
				//class
					$atts['class'] = trim( 'wm-sidebar ' . trim( $atts['class'] ) );
				//max_widgets_count
					$atts['max_widgets_count'] = absint( $atts['max_widgets_count'] );
				//sidebar
					$atts['sidebar'] = trim( $atts['sidebar'] );
					if ( ! $atts['sidebar'] ) {
						$atts['sidebar'] = 'sidebar-1';
					}
				//widgets setup
					$atts['widgets'] = wp_get_sidebars_widgets();
					if ( ! is_array( $atts['widgets'] ) ) {
						$atts['widgets'] = array();
					}
					if ( isset( $atts['widgets'][ $atts['sidebar'] ] ) ) {
						$atts['widgets'] = $atts['widgets'][ $atts['sidebar'] ];
						$atts['class']  .= ' widgets-count-' . count( $atts['widgets'] );
					} else {
						$atts['widgets'] = array();
					}
				//wrapper
					if (
							! is_array( $atts['wrapper'] )
							&& ! isset( $atts['wrapper']['open'] )
							&& ! isset( $atts['wrapper']['close'] )
						) {
						$atts['wrapper'] = array(
								'open'  => '',
								'close' => '',
							);
					}
				//class
					$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . '_sidebar_classes', $atts['class'] );
					$atts['class'] = apply_filters( WM_SHORTCODES_HOOK_PREFIX . '_sidebar_classes_' . $atts['sidebar'], $atts['class'] );
				//Allow filtering the attributes
					$atts = apply_filters( WMAMP_HOOK_PREFIX . 'sidebar_atts', $atts );
					$atts = apply_filters( WMAMP_HOOK_PREFIX . 'sidebar_atts_' . $atts['sidebar'], $atts );

			//Preparing output
				if (
						is_active_sidebar( $atts['sidebar'] )
						&& (
								0 === $atts['max_widgets_count']
								|| $atts['max_widgets_count'] >= count( $atts['widgets'] )
							)
					) {

					$output .= $atts['wrapper']['open'];

						$output .= "\r\n\r\n" . '<div class="' . $atts['class'] . '" data-id="' . $atts['sidebar'] . '" data-widgets-count="' . count( $atts['widgets'] ) . '">' . "\r\n"; //data-id is to prevent double ID attributes on the website

							$output .= apply_filters( WMAMP_HOOK_PREFIX . 'sidebar_widgets_pre', '' );

							if (
									function_exists( 'ob_start' )
									&& function_exists( 'ob_get_clean' )
								) {
								ob_start();
								dynamic_sidebar( $atts['sidebar'] );
								$output .= ob_get_clean();
							}

							$output .= apply_filters( WMAMP_HOOK_PREFIX . 'sidebar_widgets_post', '' );

						$output .= "\r\n" . '</div>' . "\r\n\r\n";

					$output .= $atts['wrapper']['close'];
				}

			//Output
				$output = apply_filters( WMAMP_HOOK_PREFIX . 'sidebar', $output );
				$output = apply_filters( WMAMP_HOOK_PREFIX . 'sidebar_' . $atts['sidebar'], $output );
				return $output;
		}
	} // /wm_sidebar



	/**
	 * WebMan custom shortcode attributes parser
	 *
	 * Matches predefined array ($defaults) in attributes array ($atts)
	 * and creates a new item $atts['attributes'] with all the additional
	 * custom attributes and their values set for shortcode.
	 * Custom attributes can be written as "custom_attribute" while they
	 * will be outputted as "custom-attribute". The "customAttribute"
	 * attribute name is outputted as "customattribute".
	 * No need to put the attribute names to lowercase as WordPress
	 * does this automatically.
	 *
	 * @since   1.0
	 *
	 * @param   array $defaults
	 * @param   array $atts
	 * @param   array $remove The array of custom attributes to remove (like basic required HTML element attributes for example).
	 * @param   array $aside All the custom attributes of names from this array will be put as new $atts item ($atts[aside]=value).
	 * @param   string $shortcode
	 *
	 * @return  array Custom attributes are returned as sting of custom-attribute="value" inside the $atts['attributes'].
	 */
	if ( ! function_exists( 'wm_shortcode_custom_atts' ) ) {
		function wm_shortcode_custom_atts( $defaults = array(), $atts = array(), $remove = array(), $aside = array(), $shortcode = '' ) {
			//Do nothing if $defaults or $atts array is empty
				if ( empty( $defaults ) ) {
					return;
				}
				$atts = (array) $atts;

			//Backup all initial shortcode attributes
				$atts_custom = $atts;

			//Run the basic shortcodes attributes comparison
				$atts = shortcode_atts( $defaults, $atts, $shortcode );

			//Get the difference between original (backed up) attributes, the default ones, minus the reserved attributes (to be removed)
				$atts_custom = array_diff_key( $atts_custom, $atts, array_flip( $remove ) );

			//Setting up the output
				$atts['attributes'] = '';
				if ( ! empty( $atts_custom ) ) {
					foreach ( $atts_custom as $attribute => $value ) {
						//If you set a "custom-attribute=1" in the shortcode, WordPress just adds the whole attribute+value pair
						//to the attributes array and will not use the attribute name as the key for the array item.
						//That's why we need to check if the key is numeric and if it is, just add the whole value to custom attributes.
						if ( ! is_numeric( $attribute ) ) {
							//Processing aside attributes (excluded from $atts['attributes'])
								if ( in_array( trim( $attribute ), $aside ) ) {
									$atts[trim( $attribute )] = esc_attr( $value );
									continue;
								}
							//Processing "custom_attribute" names
								$attribute           = str_replace( '_', '-', sanitize_title( trim( $attribute ) ) );
								$atts['attributes'] .= ' ' . $attribute . '="' . esc_attr( $value ) . '"';
						} else {
							//Processing "custom-attribute" names
								$atts['attributes'] .= ' ' . $value;
						}
					}
				}

			//Output
				return $atts;
		}
	} // /wm_shortcode_custom_atts



	/**
	 * Pagination
	 *
	 * Supports WP-PageNavi plugin (@link http://wordpress.org/plugins/wp-pagenavi/).
	 *
	 * @since   1.0
	 *
	 * @param   object $query
	 * @param   array $atts Setup attributes.
	 *
	 * @return  Pagination HTML.
	 */
	if ( ! function_exists( 'wm_pagination' ) ) {
		function wm_pagination( $query = null, $atts = array() ) {
			//Set default setting attributes
				$atts = wp_parse_args( $atts, array(
						'label_previous' => __( '&laquo;', 'wm_domain' ),
						'label_next'     => __( '&raquo;', 'wm_domain' ),
						'before_output'  => '<div class="wm-pagination">',
						'after_output'   => '</div>',
					) );
				$atts = apply_filters( WMAMP_HOOK_PREFIX . 'pagination_atts', $atts );

			//WP-PageNavi plugin support (http://wordpress.org/plugins/wp-pagenavi/)
				if ( function_exists( 'wp_pagenavi' ) ) {
					//Set up WP-PageNavi attributes
						$atts_pagenavi = array(
								'echo' => false,
							);
						if ( $query ) {
							$atts_pagenavi['query'] = $query;
						}
						$atts_pagenavi = apply_filters( WMAMP_HOOK_PREFIX . 'wppagenavi_atts', $atts_pagenavi );

					//Output
						return $atts['before_output'] . wp_pagenavi( $atts_pagenavi ) . $atts['after_output'];
				}

			//If no WP-PageNavi plugin used, output our own pagination (using WordPress native paginate_links() function)
				global $wp_query, $wp_rewrite;

				//Override global WordPress query if custom used
					if ( $query ) {
						$wp_query = $query;
					}

				//WordPress pagination settings
					$pagination = array(
							'base'      => @add_query_arg( 'paged', '%#%' ),
							'format'    => '',
							'current'   => max( 1, get_query_var( 'paged' ) ),
							'total'     => $wp_query->max_num_pages,
							'prev_text' => $atts['label_previous'],
							'next_text' => $atts['label_next'],
						);

				//Nice URLs
					if ( $wp_rewrite->using_permalinks() ) {
						$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
					}

				//Search page
					if ( get_query_var( 's' ) ) {
						$pagination['add_args'] = array( 's' => urlencode( get_query_var( 's' ) ) );
					}

				//Output
					if ( 1 < $wp_query->max_num_pages ) {
						return $atts['before_output'] . paginate_links( $pagination ) . $atts['after_output'];
					}
		}
	} // /wm_pagination



	/**
	 * Hex color to RGB
	 *
	 * @since   1.0
	 *
	 * @param   string $hex
	 *
	 * @return  array( 'r' => [0-255], 'g' => [0-255], 'b' => [0-255] )
	 */
	if ( ! function_exists( 'wm_color_hex_to_rgb' ) ) {
		function wm_color_hex_to_rgb( $hex ) {
			//Helper variables
				$rgb = array();

			//Checking input
				$hex = trim( $hex );
				$hex = preg_replace( '/[^0-9A-Fa-f]/', '', $hex );
				$hex = substr( $hex, 0, 6 );

			//Converting hex color into rgb
				if ( $hex ) {
					if ( 6 == strlen( $hex ) ) {
						$rgb['r'] = hexdec( substr( $hex, 0, 2 ) );
						$rgb['g'] = hexdec( substr( $hex, 2, 2 ) );
						$rgb['b'] = hexdec( substr( $hex, 4, 2 ) );
					} else {
					//If shorthand notation, we need some string manipulations
						$rgb['r'] = hexdec( str_repeat( substr( $hex, 0, 1 ), 2 ) );
						$rgb['g'] = hexdec( str_repeat( substr( $hex, 1, 1 ), 2 ) );
						$rgb['b'] = hexdec( str_repeat( substr( $hex, 2, 1 ), 2 ) );
					}
				}

			//Return RGB array
				return $rgb;
		}
	} // /wm_color_hex_to_rgb



	/**
	 * Color brightness detection
	 *
	 * @since   1.0
	 *
	 * @param   string $hex
	 *
	 * @return  integer (0-255)
	 */
	if ( ! function_exists( 'wm_color_brightness' ) ) {
		function wm_color_brightness( $hex ) {
			//Helper variables
				$output = '';

			//Processing
				$rgb = wm_color_hex_to_rgb( $hex );
				if ( ! empty( $rgb ) ) {
					$output = absint( ( ( $rgb['r'] * 299 ) + ( $rgb['g'] * 587 ) + ( $rgb['b'] * 114 ) ) / 1000 ); //returns value from 0 to 255
				}

			//Output
				return $output;
		}
	} // /wm_color_brightness



	/**
	 * Alter color brightness
	 *
	 * @since   1.0
	 *
	 * @param   string $hex
	 * @param   integer $step
	 *
	 * @return  string Hex color.
	 */
	if ( ! function_exists( 'wm_alter_brightness' ) ) {
		function wm_alter_brightness( $hex, $step ) {
			//Helper variables
				$output = '';

			//Processing
				$rgb = wm_color_hex_to_rgb( $hex );
				if ( ! empty( $rgb ) ) {
					foreach ( $rgb as $key => $value ) {
						$new_hex_part = dechex( max( 0, min( 255, $value + intval( $step ) ) ) );
						$rgb[ $key ]  = ( 1 == strlen( $new_hex_part ) ) ? ( '0' . $new_hex_part ) : ( $new_hex_part );
					}
					$output = '#' . implode( '', $rgb );
				}

			//Output
				return $output;
		}
	} // /wm_alter_brightness



	/**
	 * Modifying input color by changing brightness in response to treshold
	 *
	 * @since   1.0
	 *
	 * @param   string $color Hex color
	 * @param   integer $dark [-255,255] Brightness modification when below treshold
	 * @param   integer $light [-255,255] Brightness modification when above treshold
	 * @param   string $addons Additional CSS rules (such as "!important")
	 * @param   integer $treshold [0,255]
	 *
	 * @return  string Hex color.
	 */
	if ( ! function_exists( 'wm_modify_color' ) ) {
		function wm_modify_color( $color, $dark, $light, $addons = '', $treshold = WMAMP_COLOR_TRESHOLD ) {
			//Helper variables
				$output = '';

			//Processing
				$output = ( $treshold > wm_color_brightness( $color ) ) ? ( wm_alter_brightness( $color, $dark ) ) : ( wm_alter_brightness( $color, $light ) );
				if ( $output ) {
					$output .= $addons;
				}

			//Output
				return $output;
		}
	} // /wm_modify_color



	/**
	 * CSS3 linear gradient builder
	 *
	 * @since   1.0
	 *
	 * @param   string $color Gradient bottom base hex color
	 * @param   integer $brighten [-255,255] Gradient top color brightening (default 17 DEC = 11 HEX)
	 * @param   string $addons Additional CSS rules (such as "!important")
	 *
	 * @return  string CSS3 gradient styles.
	 */
	if ( ! function_exists( 'wm_css3_gradient' ) ) {
		function wm_css3_gradient( $color, $brighten = 17, $addons = '' ) {
			//Helper variables
				$output = '';

			//Processing
				$color  = preg_replace( '/[^0-9A-Fa-f]/', '', $color );
				$color  = ( 6 > strlen( $color ) ) ? ( substr( $color, 0, 3 ) ) : ( substr( $color, 0, 6 ) );
				$addons = ( trim( $addons ) ) ? ( ' ' . trim( $addons ) ) : ( '' );

				if ( $color && 3 <= strlen( $color ) ) {
					//IMPORTANT: The first "background-image:" is omitted here (to make it easier as conditional output)!
					$output .=                          '-webkit-linear-gradient(top, ' . wm_alter_brightness( $color, $brighten ) . ', #' . $color . ')' . $addons . ';' . "\r\n";
					$output .= "\t" . 'background-image:    -moz-linear-gradient(top, ' . wm_alter_brightness( $color, $brighten ) . ', #' . $color . ')' . $addons . ';' . "\r\n";
					$output .= "\t" . 'background-image:     -ms-linear-gradient(top, ' . wm_alter_brightness( $color, $brighten ) . ', #' . $color . ')' . $addons . ';' . "\r\n";
					$output .= "\t" . 'background-image:      -o-linear-gradient(top, ' . wm_alter_brightness( $color, $brighten ) . ', #' . $color . ')' . $addons . ';' . "\r\n";
					$output .= "\t" . 'background-image:         linear-gradient(top, ' . wm_alter_brightness( $color, $brighten ) . ', #' . $color . ')' . $addons;
				}

			//Output
				return $output;
		}
	} // /wm_css3_gradient

?>