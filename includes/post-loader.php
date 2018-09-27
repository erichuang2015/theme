<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Post Loader
 *
 * Render posts via ajax.
 *
 * @link https://github.com/mmaarten/theme/wiki/Post-Loader
 */

$theme_post_loaders = array();

/**
 * Init
 */
function theme_post_loader_init()
{
	theme_register_post_loader( 'Theme\Component\PostLoader\Sample' );
}

add_action( 'init', 'theme_post_loader_init' );

/**
 * Create
 */
function theme_create_post_loader( $id, $args = array() )
{
	$loader = new Theme\Core\PostLoader( $id, $args );

	theme_register_post_loader( $loader );

	return $loader;
}

/**
 * Register
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
 * Unregister
 */
function theme_unregister_post_loader( $loader_id )
{
	global $theme_post_loaders;

	unset( $theme_post_loaders[ $loader_id ] );
}

/**
 * Get
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
 * Render
 */
function theme_post_loader( $loader_id )
{
	$loader = theme_get_post_loader( $loader_id );

	if ( ! $loader ) 
	{
		$loader = theme_create_post_loader( $loader_id );
	}

	$loader->render();
}

/**
 * Shortcode
 */
function theme_post_loader_shortcode( $atts, $content, $tag )
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
