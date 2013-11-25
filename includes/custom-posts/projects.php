<?php
/**
 * WebMan Custom Posts
 *
 * Registering "wm_projects" custom post.
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
			add_action( WMAMP_HOOK_PREFIX . 'register_post_types', 'wm_projects_cp_register', 10 );
		//CP list table columns
			add_action( 'manage_wm_projects_posts_custom_column', 'wm_projects_cp_columns_render' );
		//Registering taxonomies
			add_action( WMAMP_HOOK_PREFIX . 'register_post_types', 'wm_projects_cp_taxonomies', 10 );
		//Permanlinks settings
			add_action( 'admin_init', 'wm_projects_cp_permalinks' );

		/**
		* The init action occurs after the theme's functions file has been included.
		* So, if you're looking for terms directly in the functions file,
		* you're doing so before they've actually been registered.
		*/

	//Filters
		//CP list table columns
			add_filter( 'manage_edit-wm_projects_columns', 'wm_projects_cp_columns_register' );





/**
 * CREATING A CUSTOM POST
 */

	/**
	 * Custom post registration
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_projects_cp_register' ) ) {
		function wm_projects_cp_register() {
			$permalinks = get_option( 'wmamp-permalinks' );

			//Custom post registration arguments
				$args = apply_filters( WMAMP_HOOK_PREFIX . 'cp_register_' . 'wm_projects', array(
					'query_var'           => 'projects',
					'capability_type'     => 'post',
					'public'              => true,
					'show_ui'             => true,
					'exclude_from_search' => false,
					'hierarchical'        => false,
					'rewrite'             => array(
							'slug' => ( isset( $permalinks['project'] ) && $permalinks['project'] ) ? ( $permalinks['project'] ) : ( 'project' )
						),
					'menu_position'       => 30,
					'menu_icon'           => WMAMP_ASSETS_URL . 'img/custom-posts/projects.png',
					'supports'            => array(
							'title',
							'editor',
							'excerpt',
							'thumbnail',
							'comments',
							'custom-fields',
							'author',
						),
					'labels'              => array(
						'name'               => __( 'Projects', 'wm_domain' ),
						'singular_name'      => __( 'Project', 'wm_domain' ),
						'add_new'            => __( 'Add New', 'wm_domain' ),
						'add_new_item'       => __( 'Add New Project', 'wm_domain' ),
						'new_item'           => __( 'Add New', 'wm_domain' ),
						'edit_item'          => __( 'Edit Project', 'wm_domain' ),
						'view_item'          => __( 'View Project', 'wm_domain' ),
						'search_items'       => __( 'Search Projects', 'wm_domain' ),
						'not_found'          => __( 'No project found', 'wm_domain' ),
						'not_found_in_trash' => __( 'No project found in trash', 'wm_domain' ),
						'parent_item_colon'  => '',
					)
				) );

			//Register custom post type
				register_post_type( 'wm_projects' , $args );
		}
	} // /wm_projects_cp_register





/**
 * CUSTOM POST LIST TABLE IN ADMIN
 */

	/**
	 * Register table columns
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_projects_cp_columns_register' ) ) {
		function wm_projects_cp_columns_register( $columns ) {
			//Helper variables
				$prefix = 'wmamp-';
				$suffix = '-wm_projects';

			//Register table columns
				$columns = apply_filters( WMAMP_HOOK_PREFIX . 'cp_columns_' . 'wm_projects', array(
					'cb'                           => '<input type="checkbox" />',
					$prefix . 'thumb' . $suffix    => __( 'Image', 'wm_domain' ),
					'title'                        => __( 'Project', 'wm_domain' ),
					$prefix . 'category' . $suffix => __( 'Category', 'wm_domain' ),
					$prefix . 'tag' . $suffix      => __( 'Tag', 'wm_domain' ),
					$prefix . 'link' . $suffix     => __( 'Custom link', 'wm_domain' ),
					'date'                         => __( 'Date', 'wm_domain' ),
					'author'                       => __( 'Author', 'wm_domain' )
				) );

			return $columns;
		}
	} // /wm_projects_cp_columns_register



	/**
	 * Render table columns
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_projects_cp_columns_render' ) ) {
		function wm_projects_cp_columns_render( $column ) {
			//Helper variables
				global $post;
				$prefix = 'wmamp-';
				$suffix = '-wm_projects';

			//Columns renderers
				switch ( $column ) {
					case $prefix . 'category' . $suffix:

						$terms = get_the_terms( $post->ID , 'project_category' );
						if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
							foreach ( $terms as $term ) {
								$termName = ( isset( $term->name ) ) ? ( $term->name ) : ( null );
								echo '<strong class="project-category">' . $termName . '</strong><br />';
							}
						}

					break;
					case $prefix . 'link' . $suffix:

						$link = esc_url( stripslashes( wm_meta_option( 'link' ) ) );
						echo '<a href="' . $link . '" target="_blank">' . $link . '</a>';

					break;
					case $prefix . 'tag' . $suffix:

						$separator = '';
						$terms     = get_the_terms( $post->ID , 'project_tag' );
						if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
							foreach ( $terms as $term ) {
								$termName = ( isset( $term->name ) ) ? ( $term->name ) : ( null );
								echo $separator . $termName;
								$separator = ', ';
							}
						}

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
	} // /wm_projects_cp_columns_render





/**
 * TAXONOMIES
 */

	/**
	 * Register taxonomies
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_projects_cp_taxonomies' ) ) {
		function wm_projects_cp_taxonomies() {
			$permalinks = get_option( 'wmamp-permalinks' );

			//Projects categories
				$args = apply_filters( WMAMP_HOOK_PREFIX . 'cp_taxonomy_' . 'project_category', array(
					'hierarchical'      => true,
					'show_in_nav_menus' => false,
					'show_ui'           => true,
					'query_var'         => 'project-category',
					'rewrite'           => array(
							'slug' => ( isset( $permalinks['project_category'] ) && $permalinks['project_category'] ) ? ( $permalinks['project_category'] ) : ( 'project/category' )
						),
					'labels'            => array(
						'name'          => __( 'Project Categories', 'wm_domain' ),
						'singular_name' => __( 'Project Category', 'wm_domain' ),
						'search_items'  => __( 'Search Categories', 'wm_domain' ),
						'all_items'     => __( 'All Categories', 'wm_domain' ),
						'parent_item'   => __( 'Parent Category', 'wm_domain' ),
						'edit_item'     => __( 'Edit Category', 'wm_domain' ),
						'update_item'   => __( 'Update Category', 'wm_domain' ),
						'add_new_item'  => __( 'Add New Category', 'wm_domain' ),
						'new_item_name' => __( 'New Category Title', 'wm_domain' ),
					)
				) );

				register_taxonomy( 'project_category', 'wm_projects', $args );

			//Projects tags
				$args = apply_filters( WMAMP_HOOK_PREFIX . 'cp_taxonomy_' . 'project_tag', array(
					'hierarchical'      => false,
					'show_in_nav_menus' => false,
					'show_ui'           => true,
					'query_var'         => 'project-tag',
					'rewrite'           => array(
							'slug' => ( isset( $permalinks['project_tag'] ) && $permalinks['project_tag'] ) ? ( $permalinks['project_tag'] ) : ( 'project/tag' )
						),
					'labels'            => array(
						'name'          => __( 'Project Tags', 'wm_domain' ),
						'singular_name' => __( 'Project Tag', 'wm_domain' ),
						'search_items'  => __( 'Search Tags', 'wm_domain' ),
						'all_items'     => __( 'All Tags', 'wm_domain' ),
						'edit_item'     => __( 'Edit Tag', 'wm_domain' ),
						'update_item'   => __( 'Update Tag', 'wm_domain' ),
						'add_new_item'  => __( 'Add New Tag', 'wm_domain' ),
						'new_item_name' => __( 'New Tag Title', 'wm_domain' ),
					)
				) );

				register_taxonomy( 'project_tag', 'wm_projects', $args );
		}
	} // /wm_projects_cp_taxonomies





/**
 * PERMALINKS SETTINGS
 */

	/**
	 * Create permalinks settings fields in WordPress admin
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_projects_cp_permalinks' ) ) {
		function wm_projects_cp_permalinks() {
			//Adding sections
				add_settings_section(
						'wmamp-' . 'wm_projects' . '-permalinks',
						__( 'Projects Custom Post Permalinks', 'wm_domain' ),
						'wm_projects_cp_permalinks_render_section',
						'permalink'
					);

			//Adding settings fields
				add_settings_field(
						'project',
						__( 'Single project permalink', 'wm_domain' ),
						'wm_permalinks_render_field',
						'permalink',
						'wmamp-' . 'wm_projects' . '-permalinks',
						array(
								'name'        => 'project',
								'placeholder' => apply_filters( WMAMP_HOOK_PREFIX . 'cp_permalink_' . 'project', 'project' )
							)
					);
				add_settings_field(
						'project_category',
						__( 'Project category base', 'wm_domain' ),
						'wm_permalinks_render_field',
						'permalink',
						'wmamp-' . 'wm_projects' . '-permalinks',
						array(
								'name'        => 'project_category',
								'placeholder' => apply_filters( WMAMP_HOOK_PREFIX . 'cp_permalink_' . 'project_category', 'project/category' )
							)
					);
				add_settings_field(
						'project_tag',
						__( 'Project tag base', 'wm_domain' ),
						'wm_permalinks_render_field',
						'permalink',
						'wmamp-' . 'wm_projects' . '-permalinks',
						array(
								'name'        => 'project_tag',
								'placeholder' => apply_filters( WMAMP_HOOK_PREFIX . 'cp_permalink_' . 'project_tag', 'project/tag' )
							)
					);
		}
	} // /wm_projects_cp_permalinks



	/**
	 * Create permalinks settings section WordPress admin
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_projects_cp_permalinks_render_section' ) ) {
		function wm_projects_cp_permalinks_render_section() {
			//Settings section description
				echo '<p>' . __( 'You can change the Projects custom post type permalinks here.', 'wm_domain' ) . '</p>';
		}
	} // /wm_projects_cp_permalinks_render_section





/**
 * META BOXES
 */

	/**
	 * Register metabox fields
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_projects_cp_metafields' ) ) {
		function wm_projects_cp_metafields() {
			//Helper variables
				$fields = array();

				//"Attributes" tab
					$fields[1000] = array(
							'type'  => 'section-open',
							'id'    => 'project-attributes-section',
							'title' => __( 'Attributes', 'wm_domain' ),
						);

						//Project custom link input field
							$fields[1020] = array(
									'type'        => 'text',
									'id'          => 'link',
									'label'       => __( 'Custom link URL', 'wm_domain' ),
									'description' => __( 'No link will be displayed / applied when left blank', 'wm_domain' ),
								);

						//Project custom link actions
							$fields[1040] = array(
									'type'        => 'select',
									'id'          => 'link-action',
									'label'       => __( 'Custom link action', 'wm_domain' ),
									'description' => __( 'Choose how to display / apply the link set above', 'wm_domain' ),
									'optgroups'   => true,
									'options'     => array(
											'1OPTGROUP'  => __( 'Project page', 'wm_domain' ),
												''         => __( 'Display link on project page', 'wm_domain' ),
											'1/OPTGROUP' => '',
											'2OPTGROUP'  => __( 'Apply directly in projects list', 'wm_domain' ),
												'modal'    => __( 'Open in popup window (videos and images only)', 'wm_domain' ),
												'_blank'   => __( 'Open in new tab / window', 'wm_domain' ),
												'_self'    => __( 'Open in same window', 'wm_domain' ),
											'2/OPTGROUP' => '',
										),
								);

					$fields[1980] = array(
							'type' => 'section-close',
						);
				// /"Attributes" tab

			//Apply filter to manipulate with metafields array
				$fields = apply_filters( WMAMP_HOOK_PREFIX . 'cp_metafields_' . 'wm_projects', $fields );

			//Sort the array by the keys
				ksort( $fields );

			//Output
				return $fields;
		}
	} // /wm_projects_cp_metafields



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
				'fields' => 'wm_projects_cp_metafields',

				// Meta box id, unique per meta box.
				'id' => 'wm_projects' . '-metabox',

				// Post types.
				'pages' => array( 'wm_projects' ),

				// Tabbed meta box interface?
				'tabs' => true,

				// Meta box title.
				'title' => __( 'Project settings', 'wm_domain' ),
			) );
	}

?>