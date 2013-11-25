<?php
/**
 * WebMan Custom Posts
 *
 * Registering "wm_modules" custom post.
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
			add_action( WMAMP_HOOK_PREFIX . 'register_post_types', 'wm_modules_cp_register', 10 );
		//CP list table columns
			add_action( 'manage_wm_modules_posts_custom_column', 'wm_modules_cp_columns_render' );
		//Registering taxonomies
			add_action( WMAMP_HOOK_PREFIX . 'register_post_types', 'wm_modules_cp_taxonomies', 10 );
		//Permanlinks settings
			add_action( 'admin_init', 'wm_modules_cp_permalinks' );

		/**
		* The init action occurs after the theme's functions file has been included.
		* So, if you're looking for terms directly in the functions file,
		* you're doing so before they've actually been registered.
		*/

	//Filters
		//CP list table columns
			add_filter( 'manage_edit-wm_modules_columns', 'wm_modules_cp_columns_register' );





/**
 * CREATING A CUSTOM POST
 */

	/**
	 * Custom post registration
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_modules_cp_register' ) ) {
		function wm_modules_cp_register() {
			$permalinks = get_option( 'wmamp-permalinks' );

			//Custom post registration arguments
				$args = apply_filters( WMAMP_HOOK_PREFIX . 'cp_register_' . 'wm_modules', array(
					'query_var'           => 'modules',
					'capability_type'     => 'page',
					'public'              => true,
					'show_ui'             => true,
					'exclude_from_search' => true,
					'hierarchical'        => false,
					'rewrite'             => array(
							'slug' => ( isset( $permalinks['module'] ) && $permalinks['module'] ) ? ( $permalinks['module'] ) : ( 'module' )
						),
					'menu_position'       => 45,
					'menu_icon'           => WMAMP_ASSETS_URL . 'img/custom-posts/modules.png',
					'supports'            => array(
							'title',
							'editor',
							'thumbnail',
							'author',
						),
					'labels'              => array(
						'name'               => __( 'Content Modules', 'wm_domain' ),
						'singular_name'      => __( 'Content Module', 'wm_domain' ),
						'add_new'            => __( 'Add New', 'wm_domain' ),
						'add_new_item'       => __( 'Add New module', 'wm_domain' ),
						'new_item'           => __( 'Add New', 'wm_domain' ),
						'edit_item'          => __( 'Edit Module', 'wm_domain' ),
						'view_item'          => __( 'View Module', 'wm_domain' ),
						'search_items'       => __( 'Search Modules', 'wm_domain' ),
						'not_found'          => __( 'No module found', 'wm_domain' ),
						'not_found_in_trash' => __( 'No module found in trash', 'wm_domain' ),
						'parent_item_colon'  => ''
					)
				) );

			//Register custom post type
				register_post_type( 'wm_modules' , $args );
		}
	} // /wm_modules_cp_register





/**
 * CUSTOM POST LIST TABLE IN ADMIN
 */

	/**
	 * Register table columns
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_modules_cp_columns_register' ) ) {
		function wm_modules_cp_columns_register( $columns ) {
			//Helper variables
				$prefix = 'wmamp-';
				$suffix = '-wm_modules';

			//Register table columns
				$columns = apply_filters( WMAMP_HOOK_PREFIX . 'cp_columns_' . 'wm_modules', array(
					'cb'                        => '<input type="checkbox" />',
					$prefix . 'thumb' . $suffix => __( 'Image', 'wm_domain' ),
					'title'                     => __( 'Content module', 'wm_domain' ),
					$prefix . 'tag' . $suffix   => __( 'Tags', 'wm_domain' ),
					$prefix . 'link' . $suffix  => __( 'Custom link', 'wm_domain' ),
					$prefix . 'slug' . $suffix  => __( 'Slug', 'wm_domain' ),
					'date'                      => __( 'Date', 'wm_domain' ),
					'author'                    => __( 'Author', 'wm_domain' )
				) );

			return $columns;
		}
	} // /wm_modules_cp_columns_register



	/**
	 * Render table columns
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_modules_cp_columns_render' ) ) {
		function wm_modules_cp_columns_render( $column ) {
			//Helper variables
				global $post;
				$prefix = 'wmamp-';
				$suffix = '-wm_modules';

			//Columns renderers
				switch ( $column ) {
					case $prefix . 'link' . $suffix:

						$link = esc_url( stripslashes( wm_meta_option( 'link' ) ) );
						echo '<a href="' . $link . '" target="_blank">' . $link . '</a>';

					break;
					case $prefix . 'slug' . $suffix:

						echo $post->post_name;

					break;
					case $prefix . 'tag' . $suffix:

						$separator = '';
						$terms     = get_the_terms( $post->ID , 'module_tag' );
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

						$fontIcon    = wm_meta_option( 'icon-font' );
						$iconColor   = wm_meta_option( 'icon-color' );
						$iconBgColor = wm_meta_option( 'icon-color-background' );

						$styleIcon      = ( $iconColor ) ? ( ' style="color: ' . $iconColor . '"' ) : ( '' );
						$styleContainer = ( $iconBgColor ) ? ( ' style="background-color: ' . $iconBgColor . '"' ) : ( '' );

						if ( $fontIcon ) {
							$image = '<i class="' . $fontIcon . '"' . $styleIcon . '></i>';
						}

						$hasThumb = ( $image ) ? ( ' has-thumb' ) : ( ' no-thumb' );

						echo '<span class="wm-image-container' . $hasThumb . '"' . $styleContainer . '>';

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
	} // /wm_modules_cp_columns_render





/**
 * TAXONOMIES
 */

	/**
	 * Register taxonomies
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_modules_cp_taxonomies' ) ) {
		function wm_modules_cp_taxonomies() {
			$permalinks = get_option( 'wmamp-permalinks' );

			//Module tags
				$args = apply_filters( WMAMP_HOOK_PREFIX . 'cp_taxonomy_' . 'module_tag', array(
					'hierarchical'      => false,
					'show_in_nav_menus' => false,
					'show_ui'           => true,
					'query_var'         => 'module-tag',
					'rewrite'           => array(
							'slug' => ( isset( $permalinks['module_tag'] ) && $permalinks['module_tag'] ) ? ( $permalinks['module_tag'] ) : ( 'module/tag' )
						),
					'labels'            => array(
						'name'          => __( 'Module Tags', 'wm_domain' ),
						'singular_name' => __( 'Module Tag', 'wm_domain' ),
						'search_items'  => __( 'Search Tags', 'wm_domain' ),
						'all_items'     => __( 'All Tags', 'wm_domain' ),
						'edit_item'     => __( 'Edit Tag', 'wm_domain' ),
						'update_item'   => __( 'Update Tag', 'wm_domain' ),
						'add_new_item'  => __( 'Add New Tag', 'wm_domain' ),
						'new_item_name' => __( 'New Tag Title', 'wm_domain' )
					)
				) );

				register_taxonomy( 'module_tag', 'wm_modules', $args );
		}
	} // /wm_modules_cp_taxonomies





/**
 * PERMALINKS SETTINGS
 */

	/**
	 * Create permalinks settings fields in WordPress admin
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_modules_cp_permalinks' ) ) {
		function wm_modules_cp_permalinks() {
			//Adding sections
				add_settings_section(
						'wmamp-' . 'wm_modules' . '-permalinks',
						__( 'Content Modules Custom Post Permalinks', 'wm_domain' ),
						'wm_modules_cp_permalinks_render_section',
						'permalink'
					);

			//Adding settings fields
				add_settings_field(
						'module',
						__( 'Single module permalink', 'wm_domain' ),
						'wm_permalinks_render_field',
						'permalink',
						'wmamp-' . 'wm_modules' . '-permalinks',
						array(
								'name'        => 'module',
								'placeholder' => apply_filters( WMAMP_HOOK_PREFIX . 'cp_permalink_' . 'module', 'module' )
							)
					);
				add_settings_field(
						'module_tag',
						__( 'Module tag base', 'wm_domain' ),
						'wm_permalinks_render_field',
						'permalink',
						'wmamp-' . 'wm_modules' . '-permalinks',
						array(
								'name'        => 'module_tag',
								'placeholder' => apply_filters( WMAMP_HOOK_PREFIX . 'cp_permalink_' . 'module_tag', 'module/tag' )
							)
					);
		}
	} // /wm_modules_cp_permalinks



	/**
	 * Create permalinks settings section WordPress admin
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_modules_cp_permalinks_render_section' ) ) {
		function wm_modules_cp_permalinks_render_section() {
			//Settings section description
				echo '<p>' . __( 'You can change the Content Modules custom post type permalinks here.', 'wm_domain' ) . '</p>';
		}
	} // /wm_modules_cp_permalinks_render_section





/**
 * META BOXES
 */

	/**
	 * Register metabox fields
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wm_modules_cp_metafields' ) ) {
		function wm_modules_cp_metafields() {
			//Helper variables
				$fields    = $icons = array();
				$fonticons = get_option( 'wmamp-icons' );

			//Prepare font icons
				if ( isset( $fonticons['icons_select'] ) ) {
					$fonticons = array_merge( array( '' => '' ), $fonticons['icons_select'] );
				} else {
					$fonticons = array();
				}

				//"Settings" tab
					$fields[1000] = array(
							'type'  => 'section-open',
							'id'    => 'module-settings-section',
							'title' => __( 'Settings', 'wm_domain' ),
						);

						//Module custom link input field
							$fields[1020] = array(
									'type'        => 'text',
									'id'          => 'link',
									'label'       => __( 'Custom link URL', 'wm_domain' ),
									'description' => __( 'No link will be displayed / applied when left blank', 'wm_domain' ),
									'validate'    => 'url',
								);

						//Module custom link actions
							$fields[1040] = array(
									'type'        => 'select',
									'id'          => 'link-action',
									'label'       => __( 'Custom link action', 'wm_domain' ),
									'description' => __( 'Choose how to display / apply the link set above', 'wm_domain' ),
									'options'     => array(
											'_blank' => __( 'Open in new tab / window', 'wm_domain' ),
											'_self'  => __( 'Open in same window', 'wm_domain' ),
										),
									'default'    => '_self',
								);

						//Module type (setting icon box)
							$fields[1060] = array(
									'type'        => 'checkbox',
									'id'          => 'icon-box',
									'label'       => __( 'Use as icon box', 'wm_domain' ),
									'description' => __( 'Makes the module to behaviour as icon box', 'wm_domain' ),
								);

						//Conditional subsection displayed if icon box set
							$fields[1200] = array(
									'type'  => 'sub-section-open',
									'id'    => 'module-icon-section',
								);

								//Featured image setup
									$fields[1220] = array(
											'type'    => 'html',
											'content' => '<tr class="option padding-20"><td colspan="2"><div class="box blue"><a href="#" class="button-primary button-set-featured-image" style="margin-right: 1em">' . __( 'Set featured image', 'wm_domain' ) . '</a> ' . __( 'Set the icon as featured image of the post. If the font icon is used instead, it will be prioritized.', 'wm_domain' ) . '</div></td></tr>',
										);

								if ( ! empty( $fonticons ) ) {
								//Choose font icon
									$fields[1240] = array(
											'type'        => 'radio',
											'id'          => 'icon-font',
											'label'       => __( '...or choose a font icon', 'wm_domain' ),
											'description' => __( 'Select from predefined font icons', 'wm_domain' ),
											'options'     => $fonticons,
											'inline'      => true,
											'custom'      => '<i class="{{value}}" title="{{value}}" style="display: inline-block; width: 20px; height: 20px; font-size: 20px; vertical-align: top; color: #444;"></i>',
											'hide-radio'  => true,
										);

								//Icon color
									$fields[1260] = array(
											'type'        => 'color',
											'id'          => 'icon-color',
											'label'       => __( 'Font icon color', 'wm_domain' ),
											'description' => __( 'Set the color of the icon font', 'wm_domain' ),
											'conditional' => array(
													'option'       => array(
															'tag'  => 'input',
															'name' => 'wm-icon-font',
															'type' => 'radio',
														),
													'option_value' => array( '' ),
													'operand'      => 'IS_NOT',
												),
										);
								}

								//Icon background
									$fields[1280] = array(
											'type'        => 'color',
											'id'          => 'icon-color-background',
											'label'       => __( 'Icon background color', 'wm_domain' ),
											'description' => __( 'Set the color of the icon background', 'wm_domain' ),
										);

							$fields[1480] = array(
									'type'        => 'sub-section-close',
									'id'          => 'module-icon-section',
									'conditional' => array(
											'option'       => array(
													'tag'  => 'input',
													'name' => 'wm-icon-box',
													'type' => 'checkbox',
												),
											'option_value' => array( 1 ),
											'operand'      => 'IS',
										),
								);

					$fields[1980] = array(
							'type' => 'section-close',
						);
				// /"Settings" tab

			//Apply filter to manipulate with metafields array
				$fields = apply_filters( WMAMP_HOOK_PREFIX . 'cp_metafields_' . 'wm_modules', $fields );

			//Sort the array by the keys
				ksort( $fields );

			//Output
				return $fields;
		}
	} // /wm_modules_cp_metafields



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
				'fields' => 'wm_modules_cp_metafields',

				// Meta box id, unique per meta box.
				'id' => 'wm_modules' . '-metabox',

				// Post types.
				'pages' => array( 'wm_modules' ),

				// Tabbed meta box interface?
				'tabs' => true,

				// Meta box title.
				'title' => __( 'Module settings', 'wm_domain' ),
			) );
	}

?>