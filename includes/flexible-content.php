<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Flexible Content
 *
 * Dependency: Advanced Custom Fields PRO
 *
 * @link https://www.advancedcustomfields.com/
 */

// Define flexible content field name.
defined( 'THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD' ) or define( 'THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD', 'content' );

/**
 * Render Layouts
 */
function theme_render_layouts( $post_id = 0 )
{
	// Check dependency
	if ( ! function_exists( 'have_rows' ) 
	  || ! function_exists( 'the_row' ) 
	  || ! function_exists( 'get_row_layout' ) ) 
	{
		return;
	}

	// Loop layouts
	while ( have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD, $post_id ) ) 
	{
		the_row();

		// Include layout-specific template
		get_template_part( 'template-parts/layout', get_row_layout() );
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

	if ( is_main_query() 
	  && in_the_loop() 
	  && ( is_page() || is_single() ) 
	  && have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD ) ) 
	{
		ob_start();

		theme_render_layouts();

		$content = ob_get_clean();
	}

	return $content;
}

add_filter( 'the_content', 'theme_auto_render_layouts' );

/**
 * Has Layouts
 */
function theme_has_layouts( $post_id = 0 )
{
	// Check dependency
	if ( ! function_exists( 'have_rows' ) ) 
	{
		return false;
	}

	return have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD, $post_id );
}

/**
 * Has Layout
 */
function theme_has_layout( $layout, $post_id = 0 )
{
	// Check dependency
	if ( ! function_exists( 'have_rows' ) 
	  || ! function_exists( 'the_row' ) 
	  || ! function_exists( 'get_row_layout' ) ) 
	{
		return false;
	}

	$return = false;

	// Loop layouts
	while ( have_rows( THEME_LAYOUTS_FLEXIBLE_CONTENT_FIELD, $post_id ) ) 
	{
		the_row();

		if ( get_row_layout() == $layout ) 
		{
			$return = true;

			// Don't break loop
		}
	}

	return $return;
}

add_filter( 'the_content', 'theme_auto_render_layouts' );

function theme_flexible_content_body_class( $classes )
{
	if ( theme_has_layouts() ) 
	{
		$classes[] = 'has-flexible-content';
	}

	return $classes;
}

add_filter( 'body_class', 'theme_flexible_content_body_class' );
