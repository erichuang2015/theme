<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Post Loader
 *
 * Render posts via ajax.
 *
 * dependency: /src/js/post-loader.js
 */

/**
 * Init
 */
function theme_post_loader_init()
{
	// Register Sample
	theme_register_post_loader( 'Theme\Component\PostLoader\SamplePostLoader' );
}

add_action( 'init', 'theme_post_loader_init' );

/**
 * Register Post Loader
 */
function theme_register_post_loader( $loader )
{
	$manager = Theme\Core\PostLoader\PostLoaderManager::get_instance();

	$manager->register_loader( $loader );
}

/**
 * Unregister Post Loader
 */
function theme_unregister_post_loader( $loader_id )
{
	$manager = Theme\Core\PostLoader\PostLoaderManager::get_instance();

	$manager->unregister_loader( $loader_id );
}

/**
 * Get Post Loaders
 */
function theme_get_post_loaders( $loader_id )
{
	$manager = Theme\Core\PostLoader\PostLoaderManager::get_instance();

	return $manager->get_loaders( $loader_id );
}

/**
 * Get Post Loader
 */
function theme_get_post_loader( $loader_id )
{
	$manager = Theme\Core\PostLoader\PostLoaderManager::get_instance();

	return $manager->get_loader( $loader_id );
}

/**
 * Render Post Loader
 */
function theme_post_loader( $loader_id )
{
	$manager = Theme\Core\PostLoader\PostLoaderManager::get_instance();

	$manager->render_loader( $loader_id );
}

/**
 *  Post Loader Shortcode
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
