<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Breadcrumbs
 *
 * Use `add_theme_support( 'theme-breadcrumbs' );` to load this feature.
 *
 * Dependency: Breadcrumb NavXT. 
 *
 * @link https://wordpress.org/plugins/breadcrumb-navxt/
 */

/**
 * Breadcrumb Navigation
 */
function theme_breadcrumb_navigation( $args = array() )
{
	$defaults = array
	(
		'before' => '', 
		'after'  => '',
		'echo'   => true
	);

	$args = wp_parse_args( $args, $defaults );

	$str = '';

	if ( function_exists( 'bcn_display_list' ) ) 
	{
		add_filter( 'bcn_display_attributes', 'theme_breadcrumb_item_attributes', 10, 3 );

		// https://mtekk.us/code/breadcrumb-navxt/breadcrumb-navxt-doc/#Usingbcn_displayandbcn_display_list
		$items = bcn_display_list( $_return = true, $_linked = true, $_reverse = false, $_force = false );

		remove_filter( 'bcn_display_attributes', 'theme_breadcrumb_item_attributes' );

		// Check if breadcrumb items
		if ( trim( $items ) ) 
		{
			// Render navigation
			$str = sprintf( '%s<nav class="breadcrumb-nav" aria-label="breadcrumb"><ol class="breadcrumb">%s</ol></nav>%s', 
				$args['before'], $items, $args['after'] );
		}
	}
	
	if ( ! $args['echo'] ) 
	{
		return $str;
	}

	echo $str;
}

/**
 * Breadcrumb Item Attributes
 */
function theme_breadcrumb_item_attributes( $attributes, $type, $id )
{
	// Set custom class.

	$class = 'breadcrumb-item';

	if ( in_array( 'current-item', (array) $type ) ) 
	{
		$class .= ' active';
	}

	// Replace class with custom class.

	$attributes = preg_replace( '/(^| )class=".*?"/', sprintf( '$1class="%s"', $class ), $attributes );

	return $attributes;
}
