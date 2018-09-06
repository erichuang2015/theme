<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Flexible Content
 *
 * Dependency: Advanced Custom Fields PRO. 
 *
 * @link https://www.advancedcustomfields.com/
 */

defined( 'THEME_FLEXIBLE_CONTENT_FIELD' ) or define( 'THEME_FLEXIBLE_CONTENT_FIELD', 'content' );

/**
 * Render Layouts
 *
 * Renders flexible content layouts
 *
 * @param int $post_id The post id. (optional, default: current post id)
 */
function theme_render_layouts( $post_id = 0 )
{
	// Check dependency
	if ( ! function_exists( 'have_rows' ) 
	  || ! function_exists( 'the_row' )
	  || ! function_exists( 'get_row_layout' )
	  || ! function_exists( 'get_row' ) ) 
	{
		return;
	}

	while ( have_rows( THEME_FLEXIBLE_CONTENT_FIELD, $post_id ) ) 
	{
		the_row();

		do_action( 'theme/render_layout/name=' . get_row_layout(), get_row( true ) );
	}
}

/**
 * Auto Render Layouts
 *
 * Replaces post/page content with layout content.
 *
 * @param string $content The post content.
 *
 * @return string The layouts if found. Otherwise the post content.
 */
function theme_auto_render_layouts( $content )
{
	// Check dependency
	if ( ! function_exists( 'have_rows' ) ) 
	{
		return $content;
	}

	if ( is_main_query() && in_the_loop() && ( is_single() || is_page() ) && have_rows( THEME_FLEXIBLE_CONTENT_FIELD ) ) 
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
 * Has Layout
 *
 * Checks if a post has a layout.
 *
 * @param string $layout  The layout name.
 * @param int    $post_id The post id. (optional, default: current post id)
 *
 * @return boolean True if layout is found.
 */
function theme_has_layout( $layout, $post_id = 0 )
{
	// Check dependency
	if ( ! function_exists( 'have_rows' ) 
	  || ! function_exists( 'the_row' )
	  || ! function_exists( 'get_row_layout' ) ) 
	{
		return;
	}

	$return = false;

	while ( have_rows( THEME_FLEXIBLE_CONTENT_FIELD, $post_id ) ) 
	{
		the_row();

		if ( get_row_layout() == $layout ) 
		{
			$return = true;
		}
	}

	return $return;
}
