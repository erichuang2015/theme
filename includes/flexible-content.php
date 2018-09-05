<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Flexible Content
 *
 * Dependency: Advanced Custom Fields PRO 
 */

defined( 'THEME_FLEXIBLE_CONTENT_FIELD' ) or define( 'THEME_FLEXIBLE_CONTENT_FIELD', 'content' );

/**
 * Render Layouts
 */
function theme_render_layouts( $post_id = 0 )
{
	// Check dependency
	if ( ! theme_function_exists( 'have_rows', 'the_row', 'get_row', 'get_row_layout' ) ) 
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
 */
function theme_auto_render_layouts( $content )
{
	// Check dependency
	if ( ! theme_function_exists( 'have_rows' ) ) 
	{
		return $content;
	}

	if ( is_main_query() 
		&& in_the_loop() 
		&& ( is_single() || is_page() ) 
		&& have_rows( THEME_FLEXIBLE_CONTENT_FIELD ) ) 
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
 */
function theme_has_layout( $layout, $post_id = 0 )
{
	$return = false;

	// Check dependency
	if ( theme_function_exists( 'have_rows', 'the_row', 'get_row_layout' ) ) 
	{
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
	}

	return $return;
}
