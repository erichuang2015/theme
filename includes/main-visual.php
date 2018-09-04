<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Main Visual
 *
 * Dependency: Advanced Custom Fields
 */

function theme_main_visual_widgets_init()
{
	require_once get_theme_file_path( 'includes/widgets/main-visual.php' );
}

add_action( 'widgets_init', 'theme_main_visual_widgets_init' );

function theme_main_visual( $name = null )
{
	// Check if single post or page

	if ( ! is_single() && ! is_page() ) 
	{
		return;
	}

	// Check if post has main visual

	if ( ! theme_has_main_visual() ) 
	{
		return;
	}

	get_template_part( 'template-parts/main-visual', $name );
}

function theme_has_main_visual( $post_id = 0 )
{
	// Check dependency
	if ( ! function_exists( 'get_field' ) ) 
	{
		return false;
	}

	return get_field( 'main_visual_active', $post_id ) ? true : false;
}

function theme_main_visual_body_class( $classes )
{
	if ( theme_has_main_visual() ) 
	{
		$classes[] = 'has-main-visual';
	}

	return $classes;
}

add_filter( 'body_class', 'theme_main_visual_body_class' );
