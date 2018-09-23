<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Post Loader
 *
 * Renders posts via ajax.
 */

theme_register_post_loader( 'Theme\Component\PostLoader\SamplePostLoader' );

$theme_post_loaders = array();

/**
 * Register Post Loader
 */
function theme_register_post_loader( $loader )
{
	global $theme_post_loaders;

	if ( ! $loader instanceof Theme\Core\PostLoader ) 
	{
		$loader = new $loader();
	}

	$theme_post_loaders[ $loader->id ] = $loader;
}

/**
 * Unregister Post Loader
 */
function theme_unregister_post_loader( $loader_id )
{
	global $theme_post_loaders;

	unset( $theme_post_loaders[ $loader_id ] );
}

/**
 * Get Post Loader
 */
function theme_get_post_loader( $loader_id )
{
	global $theme_post_loaders;

	if ( isset( $theme_post_loaders[ $loader_id ] ) ) 
	{
		return $theme_post_loaders[ $loader_id ];
	}

	return null;
}

/**
 * Render Post Loader
 */
function theme_post_loader( $loader_id )
{
	$loader = theme_get_post_loader( $loader_id );

	if ( $loader ) 
	{
		$loader->render();
	}
}

/**
 * Post Loader Shortcode
 */
function theme_post_loader_shortcode( $atts, $content = null, $tag )
{
	$defaults = array
	(
		'id' => '',
	);

	$atts = shortcode_atts( $defaults, $atts, $tag );

	ob_start();

	theme_post_loader( $atts['id'] );

	return ob_get_clean();
}

add_shortcode( 'post-loader', 'theme_post_loader_shortcode' );
