<?php
/**
 * WebMan Custom Posts
 *
 * Registering "wm_logos" custom post.
 *
 * @since       1.0
 * @package     WebMan Amplifier
 * @subpackage  Custom Posts
 * @author      WebMan
 * @version     1.0
 */



//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;





/**
 * ACTIONS AND FILTERS
 */
	//Actions
		//Registering CP
			add_action( WMAMP_HOOK_PREFIX . 'register_post_types', 'wm_logos_cp_register', 10 );
		//CP list table columns
			add_action( 'manage_wm_logos_posts_custom_column', 'wm_logos_cp_columns_render' );
		//Registering taxonomies
			add_action( WMAMP_HOOK_PREFIX . 'register_post_types', 'wm_logos_cp_taxonomies', 10 );
		//Permanlinks settings
			add_action( 'admin_init', 'wm_logos_cp_permalinks' );

		/**
		* The init action occurs after the theme's functions file has been included.
		* So, if you're looking for terms directly in the functions file,
		* you're doing so before they've actually been registered.
		*/

	//Filters
		//CP list table columns
			add_filter( 'manage_edit-wm_logos_columns', 'wm_logos_cp_columns_register' );





/**
 * CREATING A CUSTOM POST
 */

	/**
	 * Custom post registration
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_logos_cp_register' ) ) {
		function wm_logos_cp_register() {
			$permalinks = get_option( 'wmamp-permalinks' );

			//Custom post registration arguments
				$args = apply_filters( WMAMP_HOOK_PREFIX . 'cp_register_' . 'wm_logos', array(
					'query_var'           => 'logos',
					'capability_type'     => 'post',
					'public'              => true,
					'show_ui'             => true,
					'exclude_from_search' => true,
					'hierarchical'        => false,
					'rewrite'             => array(
							'slug' => ( isset( $permalinks['logo'] ) && $permalinks['logo'] ) ? ( $permalinks['logo'] ) : ( 'logo' )
						),
					'menu_position'       => 33,
					'menu_icon'           => WMAMP_ASSETS_URL . 'img/custom-posts/logos.png',
					'supports'            => array(
							'title',
							'thumbnail',
							'author',
						),
					'labels'              => array(
						'name'               => __( 'Logos', 'wm_domain' ),
						'singular_name'      => __( 'Logos', 'wm_domain' ),
						'add_new'            => __( 'Add New', 'wm_domain' ),
						'add_new_item'       => __( 'Add New', 'wm_domain' ),
						'new_item'           => __( 'Add New', 'wm_domain' ),
						'edit_item'          => __( 'Edit', 'wm_domain' ),
						'view_item'          => __( 'View', 'wm_domain' ),
						'search_items'       => __( 'Search', 'wm_domain' ),
						'not_found'          => __( 'No item found', 'wm_domain' ),
						'not_found_in_trash' => __( 'No item found in trash', 'wm_domain' ),
						'parent_item_colon'  => ''
					)
				) );

			//Register custom post type
				register_post_type( 'wm_logos' , $args );
		}
	} // /wm_logos_cp_register





/**
 * CUSTOM POST LIST TABLE IN ADMIN
 */

	/**
	 * Register table columns
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_logos_cp_columns_register' ) ) {
		function wm_logos_cp_columns_register( $columns ) {
			//Helper variables
				$prefix = 'wmamp-';
				$suffix = '-wm_logos';

			//Register table columns
				$columns = apply_filters( WMAMP_HOOK_PREFIX . 'cp_columns_' . 'wm_logos', array(
					'cb'                           => '<input type="checkbox" />',
					$prefix . 'thumb' . $suffix    => __( 'Logo', 'wm_domain' ),
					'title'                        => __( 'Name', 'wm_domain' ),
					$prefix . 'category' . $suffix => __( 'Category', 'wm_domain' ),
					$prefix . 'link' . $suffix     => __( 'Custom link', 'wm_domain' ),
					'date'                         => __( 'Date', 'wm_domain' ),
					'author'                       => __( 'Author', 'wm_domain' )
				) );

			return $columns;
		}
	} // /wm_logos_cp_columns_register



	/**
	 * Render table columns
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_logos_cp_columns_render' ) ) {
		function wm_logos_cp_columns_render( $column ) {
			//Helper variables
				global $post;
				$prefix = 'wmamp-';
				$suffix = '-wm_logos';

			//Columns renderers
				switch ( $column ) {
					case $prefix . 'category' . $suffix:

						$terms = get_the_terms( $post->ID , 'logo_category' );
						if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
							foreach ( $terms as $term ) {
								$termName = ( isset( $term->name ) ) ? ( $term->name ) : ( null );
								echo '<strong class="logo-category">' . $termName . '</strong><br />';
							}
						}

					break;
					case $prefix . 'link' . $suffix:

						$link = esc_url( stripslashes( wm_meta_option( 'link' ) ) );
						echo '<a href="' . $link . '" target="_blank">' . $link . '</a>';

					break;
					case $prefix . 'thumb' . $suffix:

						$size  = apply_filters( WMAMP_HOOK_PREFIX . 'cp_admin_thumb_size', 'admin-thumbnail' );
						$image = ( has_post_thumbnail() ) ? ( get_the_post_thumbnail( null, $size ) ) : ( '' );

						$hasThumb = ( $image ) ? ( ' has-thumb' ) : ( ' no-thumb' );

						echo '<span class="wm-image-container' . $hasThumb . '">';

						if ( get_edit_post_link() ) {
							edit_post_link( $image );
						} else {
							echo '<a href="' . get_permalink() . '">' . $image . '</a>';
						}

						echo '</span>';

					break;
					default:
					break;
				} // /switch
		}
	} // /wm_logos_cp_columns_render





/**
 * TAXONOMIES
 */

	/**
	 * Register taxonomies
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_logos_cp_taxonomies' ) ) {
		function wm_logos_cp_taxonomies() {
			$permalinks = get_option( 'wmamp-permalinks' );

			//Logos categories
				$args = apply_filters( WMAMP_HOOK_PREFIX . 'cp_taxonomy_' . 'logo_category', array(
					'hierarchical'      => true,
					'show_in_nav_menus' => false,
					'show_ui'           => true,
					'query_var'         => 'logo-category',
					'rewrite'           => array(
							'slug' => ( isset( $permalinks['logo_category'] ) && $permalinks['logo_category'] ) ? ( $permalinks['logo_category'] ) : ( 'logos/category' )
						),
					'labels'            => array(
						'name'          => __( 'Logo Categories', 'wm_domain' ),
						'singular_name' => __( 'Logo Category', 'wm_domain' ),
						'search_items'  => __( 'Search Category', 'wm_domain' ),
						'all_items'     => __( 'All Categories', 'wm_domain' ),
						'parent_item'   => __( 'Parent Category', 'wm_domain' ),
						'edit_item'     => __( 'Edit Category', 'wm_domain' ),
						'update_item'   => __( 'Update Category', 'wm_domain' ),
						'add_new_item'  => __( 'Add New Category', 'wm_domain' ),
						'new_item_name' => __( 'New Category Title', 'wm_domain' )
					)
				) );

				register_taxonomy( 'logo_category', 'wm_logos', $args );
		}
	} // /wm_logos_cp_taxonomies





/**
 * PERMALINKS SETTINGS
 */

	/**
	 * Create permalinks settings fields in WordPress admin
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_logos_cp_permalinks' ) ) {
		function wm_logos_cp_permalinks() {
			//Adding sections
				add_settings_section(
						'wmamp-' . 'wm_logos' . '-permalinks',
						__( 'Logos Custom Post Permalinks', 'wm_domain' ),
						'wm_logos_cp_permalinks_render_section',
						'permalink'
					);

			//Adding settings fields
				add_settings_field(
						'logo',
						__( 'Single logo permalink', 'wm_domain' ),
						'wm_permalinks_render_field',
						'permalink',
						'wmamp-' . 'wm_logos' . '-permalinks',
						array(
								'name'        => 'logo',
								'placeholder' => apply_filters( WMAMP_HOOK_PREFIX . 'cp_permalink_' . 'logo', 'logo' )
							)
					);
				add_settings_field(
						'logo_category',
						__( 'Logo category base', 'wm_domain' ),
						'wm_permalinks_render_field',
						'permalink',
						'wmamp-' . 'wm_logos' . '-permalinks',
						array(
								'name'        => 'logo_category',
								'placeholder' => apply_filters( WMAMP_HOOK_PREFIX . 'cp_permalink_' . 'logo_category', 'logos/category' )
							)
					);
		}
	} // /wm_logos_cp_permalinks



	/**
	 * Create permalinks settings section WordPress admin
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_logos_cp_permalinks_render_section' ) ) {
		function wm_logos_cp_permalinks_render_section() {
			//Settings section description
				echo '<p>' . __( 'You can change the Logos custom post type permalinks here.', 'wm_domain' ) . '</p>';
		}
	} // /wm_logos_cp_permalinks_render_section





/**
 * META BOXES
 */

	/**
	 * Register metabox fields
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_logos_cp_metafields' ) ) {
		function wm_logos_cp_metafields() {
			//Helper variables
				$fields = array();

				//"Attributes" tab
					$fields[1000] = array(
							'type'  => 'section-open',
							'id'    => 'logo-settings-section',
							'title' => __( 'Logo settings', 'wm_domain' ),
						);

						//Logo image
							$fields[1020] = array(
									'type'    => 'html',
									'content' => '<tr class="option padding-20"><td colspan="2"><div class="box blue"><a href="#" class="button-primary button-set-featured-image" style="margin-right: 1em">' . __( 'Set featured image', 'wm_domain' ) . '</a> ' . __( 'Set the logo image as the featured image of the post', 'wm_domain' ) . '</div></td></tr>',
								);

						//Logo custom link input field
							$fields[1040] = array(
									'type'        => 'text',
									'id'          => 'link',
									'label'       => __( 'Custom link URL', 'wm_domain' ),
									'description' => __( 'No link will be displayed / applied when left blank', 'wm_domain' ),
									'validate'    => 'url',
								);

						//Logo custom link actions
							$fields[1060] = array(
									'type'        => 'select',
									'id'          => 'link-action',
									'label'       => __( 'Custom link action', 'wm_domain' ),
									'description' => __( 'Choose how to display / apply the link set above', 'wm_domain' ),
									'options'     => array(
											'_blank' => __( 'Open in new tab / window', 'wm_domain' ),
											'_self'  => __( 'Open in same window', 'wm_domain' ),
										),
								);

					$fields[1980] = array(
							'type' => 'section-close',
						);
				// /"Attributes" tab

			//Apply filter to manipulate with metafields array
				$fields = apply_filters( WMAMP_HOOK_PREFIX . 'cp_metafields_' . 'wm_logos', $fields );

			//Sort the array by the keys
				ksort( $fields );

			//Output
				return $fields;
		}
	} // /wm_logos_cp_metafields



	/**
	 * Create actual metabox
	 *
	 * @since  1.0
	 */
	if ( function_exists( 'wm_add_meta_box' ) ) {
		wm_add_meta_box( array(
				// Meta fields function callback (should return array of fields).
				// The function callback is used for to use a WordPress globals
				// available during the metabox rendering, such as $post.
				'fields' => 'wm_logos_cp_metafields',

				// Meta box id, unique per meta box.
				'id' => 'wm_logos' . '-metabox',

				// Post types.
				'pages' => array( 'wm_logos' ),

				// Tabbed meta box interface?
				'tabs' => false,

				// Meta box title.
				'title' => __( 'Logo settings', 'wm_domain' ),

				// Wrap the meta form around visual editor? (This is always tabbed.)
				'visual-wrapper' => false,
			) );
	}

?>