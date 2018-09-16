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
defined( 'THEME_LAYOUTS_POST_TYPE_FEATURE' ) or define( 'THEME_LAYOUTS_POST_TYPE_FEATURE', 'theme-flexible-content' );

/**
 * Init
 */
function theme_layouts_init()
{
	add_post_type_support( 'page', THEME_LAYOUTS_POST_TYPE_FEATURE );
	
	$layout_manager = Theme\Core\Layouts\Layout\LayoutManager::get_instance();
	$layout_manager->init();
}

add_action( 'init', 'theme_layouts_init' );

/*
----------------------------------------------------------------------
 Layouts
----------------------------------------------------------------------
*/

function theme_register_layout( $layout )
{
	$manager = Theme\Core\Layouts\Layout\LayoutManager::get_instance();

	$manager->register_layout( $layout );
}

function theme_unregister_layout( $name )
{
	$manager = Theme\Core\Layouts\Layout\LayoutManager::get_instance();

	$manager->unregister_layout( $name );
}

function theme_get_layouts()
{
	$manager = Theme\Core\Layouts\Layout\LayoutManager::get_instance();

	return $manager->get_layouts();
}

function theme_get_layout( $name )
{
	$manager = Theme\Core\Layouts\Layout\LayoutManager::get_instance();

	return $manager->get_layout( $name );
}

function theme_render_layouts( $post_id = 0 )
{
	$manager = Theme\Core\Layouts\Layout\LayoutManager::get_instance();

	$manager->render_layouts( $post_id );
}

function theme_render_layout( $name, $instance )
{
	$manager = Theme\Core\Layouts\Layout\LayoutManager::get_instance();

	$manager->render_layout( $name, $instance );
}

function theme_has_layouts( $post_id = 0 )
{
	$manager = Theme\Core\Layouts\Layout\LayoutManager::get_instance();

	$manager->has_layouts( $post_id );
}

function theme_has_layout( $name, $post_id = 0 )
{
	$manager = Theme\Core\Layouts\Layout\LayoutManager::get_instance();

	return $manager->render_layout( $name, $post_id );
}

function theme_enqueue_layout_scripts( $name )
{
	$manager = Theme\Core\Layouts\Layout\LayoutManager::get_instance();

	$manager->enqueue_layout_scripts( $name );
}

/*
----------------------------------------------------------------------
 Features
----------------------------------------------------------------------
*/

function theme_register_layout_feature( $feature )
{
	$manager = Theme\Core\Layouts\Feature\FeatureManager::get_instance();

	$manager->register_feature( $feature );
}

function theme_unregister_layout_feature( $id )
{
	$manager = Theme\Core\Layouts\Feature\FeatureManager::get_instance();

	$manager->unregister_feature( $id );
}

function theme_get_layout_features()
{
	$manager = Theme\Core\Layouts\Feature\FeatureManager::get_instance();

	return $manager->get_features();
}

function theme_get_layout_feature( $id )
{
	$manager = Theme\Core\Layouts\Feature\FeatureManager::get_instance();

	return $manager->get_feature( $id );
}
