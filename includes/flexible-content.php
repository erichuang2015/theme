<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Flexible Content
 *
 * Use `add_theme_support( 'theme-flexible-content' );` to load this feature.
 *
 * Dependency: Advanced Custom Fields PRO 
 *
 * @link https://www.advancedcustomfields.com/
 */

defined( 'THEME_FLEXIBLE_CONTENT_FIELD' ) or define( 'THEME_FLEXIBLE_CONTENT_FIELD', 'content' );

/**
 * Render Layouts
 */
function theme_render_layouts( $post_id = 0 )
{
	while ( have_rows( THEME_FLEXIBLE_CONTENT_FIELD, $post_id ) ) 
	{
		the_row();

		get_template_part( 'template-parts/layout', get_row_layout() );
	}
}

/**
 * Auto Render Layouts
 */
function theme_auto_render_layouts( $content )
{
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
