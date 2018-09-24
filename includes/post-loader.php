<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Post Loader
 *
 * Renders posts via ajax.
 */

theme_register_post_loader( 'Theme\Component\PostLoader\SamplePostLoader' );

/**
 * Create Post Loader
 */
function theme_create_post_loader( $loader_id, $register = true )
{
	$manager = Theme\Core\Manager\PostLoaderManager::get_instance();

	return $manager->create_loader( $loader_id, $register );
}

/**
 * Register Post Loader
 */
function theme_register_post_loader( $loader )
{
	$manager = Theme\Core\Manager\PostLoaderManager::get_instance();

	$manager->register_loader( $loader );
}

/**
 * Unregister Post Loader
 */
function theme_unregister_post_loader( $loader_id )
{
	$manager = Theme\Core\Manager\PostLoaderManager::get_instance();

	$manager->unregister_loader( $loader_id );
}

/**
 * Get Post Loader
 */
function theme_get_post_loader( $loader_id )
{
	$manager = Theme\Core\Manager\PostLoaderManager::get_instance();

	return $manager->get_loader( $loader_id );
}

/**
 * Render Post Loader
 */
function theme_post_loader( $loader_id, $include_content = true )
{
	$manager = Theme\Core\Manager\PostLoaderManager::get_instance();

	$manager->render_loader( $loader_id, $include_content );
}

/**
 * Post Loader Content
 */
function theme_post_loader_content( $loader_id )
{
	$manager = Theme\Core\Manager\PostLoaderManager::get_instance();

	$manager->render_loader_content( $loader_id );
}

/**
 * Post Loader Shortcode
 */

function theme_post_loader_shortcode( $args )
{
	$defaults = array
	(
		'id'      => '',
		'content' => true
	);

	$args = wp_parse_args( $args, $defaults );

	$include_content = ! $args['content'] || $args['content'] !== 'false';

	ob_start();

	theme_post_loader( $args['id'], $include_content );

	return ob_get_clean();
}

add_shortcode( 'post-loader', 'theme_post_loader_shortcode' );

/**
 *  Post Loader Content Shortcode
 */
function theme_post_loader_content_shortcode( $args )
{
	$defaults = array
	(
		'loader' => '',
	);

	$args = wp_parse_args( $args, $defaults );

	ob_start();

	theme_post_loader_content( $args['loader'] );

	return ob_get_clean();
}

add_shortcode( 'post-loader-content', 'theme_post_loader_content_shortcode' );
