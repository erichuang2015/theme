<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Breadcrumbs
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
	// Dependency check
	if ( ! function_exists( 'bcn_display_list' ) )
	{
		return '';
	}

	// Arguments

	$defaults = array
	(
		'before' => '', 
		'after'  => '',
		'echo'   => true
	);

	$args = wp_parse_args( $args, $defaults );

	//
	
	$str = '';

	// Enable rendering custom item attributes.
	add_filter( 'bcn_display_attributes', 'theme_breadcrumb_item_attributes', 10, 3 );

	// Render items
	$items = bcn_display_list( $_return = true, $_linked = true, $_reverse = false, $_force = false );

	// Disable rendering custom item attributes
	remove_filter( 'bcn_display_attributes', 'theme_breadcrumb_item_attributes' );

	// Check items
	if ( trim( $items ) ) 
	{
		// Render navigation
		$str = sprintf( '%s<nav class="breadcrumb-nav" aria-label="breadcrumb"><ol class="breadcrumb">%s</ol></nav>%s', 
			$args['before'], $items, $args['after'] );
	}
	
	// Echo
	if ( $args['echo'] ) 
	{
		echo $str;
	}

	// Return
	return $str;
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

	// Replace existing class with custom class.

	$attributes = preg_replace( '/(^| )class=".*?"/', sprintf( '$1class="%s"', $class ), $attributes );

	return $attributes;
}
