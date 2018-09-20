<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Main Visuals
 *
 * Dependency: Advanced Custom Fields
 *
 * @link https://www.advancedcustomfields.com/
 */

/**
 * Widgets Init
 */
function theme_main_visuals_widgets_init()
{	
	require_once get_parent_theme_file_path( 'includes/widgets/main-visual.php' );
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
