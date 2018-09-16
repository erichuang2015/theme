<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Main Visuals
 *
 * Dependency: Advanced Custom Fields
 *
 * @link https://www.advancedcustomfields.com/
 */

/**
 * Settings Init
 */
function theme_main_visuals_settings_init()
{	
	// Check dependency
	if ( ! function_exists( 'acf_add_local_field_group' ) ) 
	{
		return;
	}

	$field_group = array
	(
		'key'      => 'theme_main_visuals_field_group',
		'title'    => __( 'Main Visual', 'theme' ),
		'fields'   => array(),
		'location' => array
		(
			array
			(
				array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ),
			),
		),
		'menu_order'            => 0,
		'position'              => 'acf_after_title',
		'label_placement'       => 'top',
		'instruction_placement' => 'field'
	);

	// Fields

	// Title
	$field_group['fields'][] = array
	(
		'key'           => 'theme_main_visual_title',
		'label'         => __( 'Title', 'theme' ),
		'name'          => 'main_visual_title',
		'type'          => 'text',
		'instructions'  => '',
		'required'      => 0,
		'default_value' => '',
		'placeholder'   => '',
		'prepend'       => '',
		'append'        => '',
		'maxlength'     => 255,
	);

	// Content
	$field_group['fields'][] = array
	(
		'key'               => 'theme_main_visual_content',
		'label'             => __( 'Content', 'theme' ),
		'name'              => 'main_visual_content',
		'type'              => 'wysiwyg',
		'instructions'      => '',
		'required'          => false,
		'default_value'     => '',
		'tabs'              => 'visual',
		'toolbar'           => 'full',
		'media_upload'      => false,
		'delay'             => false,
	);

	// Image
	$field_group['fields'][] = array
	(
		'key'           => 'theme_main_visual_image',
		'label'         => 'Image',
		'name'          => 'main_visual_image',
		'type'          => 'image',
		'instructions'  => '',
		'required'      => false,
		'wrapper'       => array( 'width' => 50 ),
		'return_format' => 'id',
	);

	// Image Position
	$field_group['fields'][] = array
	(
		'key'               => 'theme_main_visual_image_position',
		'label'             => __( 'Image Position', 'theme'),
		'name'              => 'main_visual_image_position',
		'type'              => 'select',
		'instructions'      => '',
		'required'          => true,
		'wrapper'           => array( 'width' => 50 ),
		'choices'           => array
		(
			'left-top'      => __( 'left top', 'theme'),
			'left-center'   => __( 'left center', 'theme'),
			'left-bottom'   => __( 'left bottom', 'theme'),
			'center-top'    => __( 'center top', 'theme'),
			'center-center' => __( 'center center', 'theme'),
			'center-bottom' => __( 'center bottom', 'theme'),
			'right-top'     => __( 'right top', 'theme'),
			'right-center'  => __( 'right center', 'theme'),
			'right-bottom'  => __( 'right bottom', 'theme'),
		),
		'default_value'     => array( 'center-center' ),
		'allow_null'        => false,
		'multiple'          => false,
		'return_format'     => 'value',
	);

	// Active
	$field_group['fields'][] = array
	(
		'key'               => 'theme_main_visual_active',
		'label'             => __( 'Active', 'theme' ),
		'name'              => 'main_visual_active',
		'type'              => 'true_false',
		'instructions'      => '',
		'required'          => false,
		'message'           => '',
		'default_value'     => 0
	);

	//

	$field_group = apply_filters( 'theme/main_visual_field_group', $field_group );

	acf_add_local_field_group( $field_group );
}

add_action( 'init', 'theme_main_visuals_settings_init' );

/**
 * Widgets Init
 */
function theme_main_visuals_widgets_init()
{	
	register_widget( 'Theme\Component\Widget\MainVisualWidget' );
}

add_action( 'widgets_init', 'theme_main_visuals_widgets_init' );

/**
 * Render Main Visual
 */
function theme_main_visual( $name = '', $post_id = 0 )
{
	if ( ! theme_has_main_visual( $post_id ) ) 
	{
		return;
	}

	get_template_part( 'template-parts/main-visual', $name );
}

/**
 * Has Main Visual
 */
function theme_has_main_visual( $post_id = 0 )
{
	// Check dependency
	if ( ! function_exists( 'get_field' ) ) 
	{
		return false;
	}

	if ( ! $post_id ) 
	{
		$post_id = get_queried_object();
	}

	return get_field( 'main_visual_active', $post_id ) ? true : false;
}

/**
 * Body Class
 */
function theme_main_visuals_body_class( $classes )
{
	if ( theme_has_main_visual() ) 
	{
		$classes[] = 'has-main-visual';
	}

	return $classes;
}

add_filter( 'body_class', 'theme_main_visuals_body_class' );
