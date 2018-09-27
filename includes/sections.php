<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Sections
 */

defined( 'THEME_SECTIONS_POST_TYPE' ) or define( 'THEME_SECTIONS_POST_TYPE', 'section' );

function theme_sections_register_post_type()
{
	register_post_type( THEME_SECTIONS_POST_TYPE, array
	(
		'labels' => array
		(
			'name'               => _x( 'Sections', 'post type general name', 'theme' ),
			'singular_name'      => _x( 'Section', 'post type singular name', 'theme' ),
			'menu_name'          => _x( 'Sections', 'admin menu', 'theme' ),
			'name_admin_bar'     => _x( 'Section', 'add new on admin bar', 'theme' ),
			'add_new'            => _x( 'Add New', 'section', 'theme' ),
			'add_new_item'       => __( 'Add New Section', 'theme' ),
			'new_item'           => __( 'New Section', 'theme' ),
			'edit_item'          => __( 'Edit Section', 'theme' ),
			'view_item'          => __( 'View Section', 'theme' ),
			'all_items'          => __( 'All Sections', 'theme' ),
			'search_items'       => __( 'Search Sections', 'theme' ),
			'parent_item_colon'  => __( 'Parent Sections:', 'theme' ),
			'not_found'          => __( 'No sections found.', 'theme' ),
			'not_found_in_trash' => __( 'No sections found in Trash.', 'theme' )
		),
        'description'        => __( 'Description.', 'theme' ),
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => false,
		'rewrite'            => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor' )
	));
}

add_action( 'init', 'theme_sections_register_post_type' );

function theme_sections_widgets_init()
{
	require_once get_parent_theme_file_path( 'includes/widgets/section.php' );
}

add_action( 'widgets_init', 'theme_sections_widgets_init' );
