<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

/**
 * Render Main Visual
 *
 */
function theme_main_visual( $name = '' )
{
	if ( ! theme_has_main_visual() ) 
	{
		return;
	}

	get_template_part( 'template-parts/main-visual', $name );
}

/**
 * Has Main Visual
 *
 * @return boolean
 */
function theme_has_main_visual( $post_id = 0 )
{
	// Check dependency
	if ( ! function_exists( 'get_field' ) ) 
	{
		return false;
	}

	if ( ! $post_id && ! ( is_page() && ! is_single() ) ) 
	{
		return false;
	}

	return get_field( 'main_visual_active', $post_id ) ? true : false;
}

/**
 * Widgets init
 */
function theme_main_visuals_widgets_init()
{
	require_once get_theme_file_path( 'includes/widgets/main-visual.php' );
}

add_action( 'widgets_init', 'theme_main_visuals_widgets_init' );

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
