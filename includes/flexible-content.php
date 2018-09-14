<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Flexible Content
 *
 * Dependency: Advanced Custom Fields PRO
 *
 * @link https://www.advancedcustomfields.com/
 */

// Define flexible content field name
defined( 'THEME_FLEXIBLE_CONTENT_FIELD' ) or define( 'THEME_FLEXIBLE_CONTENT_FIELD', 'content' );

/**
 * Render Layouts
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

	if ( ! $post_id ) 
	{
		// Enable field access other than posts or pages.
		$post_id = get_queried_object();
	}

	// Loop layouts
	while ( have_rows( THEME_FLEXIBLE_CONTENT_FIELD, $post_id ) ) 
	{
		the_row();

		// Render layout
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

	if ( is_main_query() 
	  && in_the_loop() 
	  && ( is_page() || is_single() ) 
	  && have_rows( THEME_FLEXIBLE_CONTENT_FIELD ) ) 
	{
		ob_start();

		theme_render_layouts();

		$content = ob_get_clean();
	}

	return $content;
}

add_filter( 'the_content', 'theme_auto_render_layouts' );

/**
 * Render Layout
 */
function theme_render_layout( $layout, $instance )
{
	// Wrapper

	$wrapper = array
	(
		'class' => "layout $layout-layout"
	);

	$wrapper = apply_filters( "theme/layout_html_attributes/name=$layout", $wrapper, $layout, (array) $instance );
	$wrapper = apply_filters( 'theme/layout_html_attributes', $wrapper, $layout, (array) $instance );

	// Remove empty attributes
	$wrapper = array_filter( $wrapper );

	// Args

	$args = array
	(
		'before' => sprintf( '<div%s>', theme_esc_attr( $wrapper ) ),
		'after'  => '</div>',
	);

	// Render

	do_action( "theme/render_layout/name=$layout", $args, (array) $instance );
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

	if ( ! $post_id ) 
	{
		// Enable field access other than posts or pages.
		$post_id = get_queried_object();
	}

	$return = false;

	// Loop layouts
	while ( have_rows( THEME_FLEXIBLE_CONTENT_FIELD, $post_id ) ) 
	{
		the_row();

		// Check layout
		if ( get_row_layout() == $layout ) 
		{
			$return = true;

			// Don't break loop
		}
	}

	return $return;
}
