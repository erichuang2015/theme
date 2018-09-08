<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Flexible Content
 *
 * Dependency: Advanced Custom Fields PRO. 
 *
 * @link https://www.advancedcustomfields.com/
 */

defined( 'THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD' ) or define( 'THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD', 'content' );
defined( 'THEME_LAYOUTS_POST_TYPE_FEATURE' ) 	  or define( 'THEME_LAYOUTS_POST_TYPE_FEATURE', 'theme-layouts' );

defined( 'THEME_LAYOUTS_ORDER_DEFAULT' )    or define( 'THEME_LAYOUTS_ORDER_DEFAULT'   , 0 );
defined( 'THEME_LAYOUTS_ORDER_LAYOUT' )     or define( 'THEME_LAYOUTS_ORDER_LAYOUT'    , 1000 );
defined( 'THEME_LAYOUTS_ORDER_ATTRIBUTES' ) or define( 'THEME_LAYOUTS_ORDER_ATTRIBUTES', 2000 );

require_once get_parent_theme_file_path( 'includes/layouts/class-theme-layout.php' );
require_once get_parent_theme_file_path( 'includes/layouts/class-theme-page_header-layout.php' );
require_once get_parent_theme_file_path( 'includes/layouts/class-theme-heading-layout.php' );
require_once get_parent_theme_file_path( 'includes/layouts/class-theme-content-layout.php' );
require_once get_parent_theme_file_path( 'includes/layouts/class-theme-modal-layout.php' );
require_once get_parent_theme_file_path( 'includes/layouts/class-theme-section-layout.php' );
require_once get_parent_theme_file_path( 'includes/layouts/class-theme-image_text_column-layout.php' );

$theme_layouts = array();

/**
 * Register Layout
 */
function theme_register_layout( $layout )
{
	global $theme_layouts;

	if ( ! $layout instanceof Theme_Layout ) 
	{
		$layout = new $layout();
	}

	$theme_layouts[ $layout->name ] = $layout;
}

/**
 * Unregister Layout
 */
function theme_unregister_layout( $name )
{
	global $theme_layouts;

	unset( $theme_layouts[ $name ] );
}

/**
 * Get Layouts
 */
function theme_get_layouts()
{
	global $theme_layouts;

	return $theme_layouts;
}

/**
 * Get Layout
 */
function theme_get_layout( $name )
{
	$layouts = theme_get_layouts();

	if ( isset( $layouts[ $name ] ) ) 
	{
		return $layouts[ $name ];
	}

	return null;
}

/**
 * Has Layout
 */
function theme_has_layout( $name, $post_id = 0 )
{
	// Check dependency
	if ( ! function_exists( 'have_rows' ) || ! function_exists( 'the_row' ) || ! function_exists( 'get_row_layout' ) ) 
	{
		return false;
	}

	$return = false;

	while ( have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD, $post_id ) ) 
	{
		the_row();

		if ( get_row_layout() == $name ) 
		{
			$return = true;
		}
	}

	return $return;
}

/**
 * Render Layout
 */
function theme_render_layout( $name, $instance )
{
	$layout = theme_get_layout( $name );

	if ( ! $layout ) 
	{
		return;
	}

	$wrapper = array
	(
		'class' => "layout layout-{$layout->name}"
	);

	$wrapper = apply_filters( "theme/layout_html_attributes/name={$layout->name}", $wrapper, $layout, (array) $instance );
	$wrapper = apply_filters( 'theme/layout_html_attributes/name=', $wrapper, $layout, (array) $instance );

	$wrapper = array_filter( $wrapper );

	$args = array
	(
		'before' => sprintf( '<div%s>', theme_esc_attr( $wrapper ) ),
		'after'  => '</div>'
	);

	$layout->render( $args, (array) $instance );
}

/**
 * Render Layouts
 */
function theme_render_layouts( $post_id = 0 )
{
	// Check dependency
	if ( ! function_exists( 'have_rows' ) || ! function_exists( 'the_row' ) || ! function_exists( 'get_row_layout' ) || ! function_exists( 'get_row' ) ) 
	{
		return false;
	}

	while ( have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD, $post_id ) ) 
	{
		the_row();

		theme_render_layout( get_row_layout(), get_row( true ) );
	}
}

/**
 * Auto Render Layouts
 */
function theme_auto_render_layouts( $content )
{
	// Check dependency
	if ( ! function_exists( 'have_rows' ) ) 
	{
		return $content;
	}

	if ( have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD ) )
	{
		remove_filter( current_filter(), __FUNCTION__ );

		ob_start();

		theme_render_layouts();

		$content = ob_get_clean();

		add_filter( current_filter(), __FUNCTION__ );
	}

	return $content;
}

add_filter( 'the_content', 'theme_auto_render_layouts' );

/**
 * Enqueue Layout Scripts
 */
function theme_enqueue_layout_scripts( $name )
{
	$layout = theme_get_layout( $name );

	if ( $layout ) 
	{
		$layout->enqueue_scripts();
	}
}

/**
 * Auto Enqueue Layouts Scripts
 */
function theme_auto_enqueue_layouts_scripts()
{
	// Check dependency
	if ( ! function_exists( 'have_rows' ) || ! function_exists( 'the_row' ) || ! function_exists( 'get_row_layout' ) ) 
	{
		return;
	}

	while ( have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD ) ) 
	{
		the_row();

		theme_enqueue_layout_scripts( get_row_layout() );
	}
}

add_action( 'wp_enqueue_scripts', 'theme_auto_enqueue_layouts_scripts' );

/**
 * Get Field Group
 */
function theme_layouts_get_field_group()
{
	// Layouts

	$layouts = array();

	foreach ( theme_get_layouts() as $instance ) 
	{
		$sub_fields = $instance->fields;

		// ACF needs keys to be removed.
		$sub_fields = array_values( $sub_fields );

		// Sort fields
		usort( $sub_fields, 'theme_sort_order' );

		// Create layout
		$layout = array
		(
			'key'        => "layout_{$instance->name}",
			'name'       => $instance->name,
			'label'      => $instance->title,
			'display'    => 'block',
			'sub_fields' => $sub_fields
		);

		// Add to collection
		$layouts[ $layout['key'] ] = $layout;
	}

	// Location

	$location = array();

	foreach ( get_post_types() as $post_type ) 
	{
		if ( post_type_supports( $post_type, THEME_LAYOUTS_POST_TYPE_FEATURE ) ) 
		{
			$location[] = array
			(
				array( 'param' => 'post_type', 'operator' => '==', 'value' => $post_type ),
			);
		}
	}

	// Field Group

	$field_group = array
	(
		'key'    => 'theme_layouts_field_group',
		'title'  => __( 'Layouts' ),
		'fields' => array
		(
			array
			(
				'key'          => 'theme_flexible_content',
				'label'        => '',
				'name'         => THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD,
				'type'         => 'flexible_content',
				'instructions' => '',
				'layouts'      => $layouts,
				'button_label' => __( 'Add Layout', 'theme' ),
			),
		),
		'location'              => $location,
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'field',
		'hide_on_screen'        => array( 'the_content' ),
		'active'                => true
	);

	return apply_filters( 'theme/layouts_field_group', $field_group );
}

/**
 * Init
 */
function theme_layouts_init()
{
	// Check dependency
	if ( ! function_exists( 'acf_add_local_field_group' ) ) 
	{
		return;
	}
	
	add_post_type_support( 'page', THEME_LAYOUTS_POST_TYPE_FEATURE );
	add_post_type_support( 'section', THEME_LAYOUTS_POST_TYPE_FEATURE );

	$field_group = theme_layouts_get_field_group();

	acf_add_local_field_group( $field_group );
}

add_action( 'init', 'theme_layouts_init' );
