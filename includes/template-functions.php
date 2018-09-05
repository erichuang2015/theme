<?php
/**
 * Additional features to allow styling of the templates
 */

/**
 * Has Container
 *
 * Returns true if the #content element has a .container element.
 *
 * @return boolean
 */
function theme_has_container()
{
	$return = ! is_page_template( 'page-templates/full-width.php' );

	return apply_filters( 'theme_has_container', $return );
}

/**
 * Is Full Width
 * 
 * Returns true when no left or right sidebar is displayed. 
 *
 * @return boolean
 */
function theme_is_full_width()
{
	$return = false;

	if ( ! ( is_active_sidebar( 'sidebar-1' ) && ! is_active_sidebar( 'sidebar-2' ) )
		|| is_page_template( 'page-templates/full-width-fixed.php' ) 
		|| is_page_template( 'page-templates/full-width.php' ) ) 
	{
		$return = true;
	}

	return apply_filters( 'theme_is_full_width', $return );
}

/**
 * Body Class
 *
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function theme_body_class( $classes )
{
	$classes[] = 'no-js';
	$classes[] = 'no-svg';

	// Add class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) 
	{
		$classes[] = 'group-blog';
	}

	// Add class of hfeed to non-singular pages.
	if ( ! is_singular() ) 
	{
		$classes[] = 'hfeed';
	}

	// Add class on front page.
	if ( is_front_page() && 'posts' !== get_option( 'show_on_front' ) ) 
	{
		$classes[] = 'theme-front-page';
	}

	// Browser and device Info
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	    if ( $is_lynx )     $classes[] = 'browser-lynx';
	elseif ( $is_gecko ) 	$classes[] = 'browser-gecko';
	elseif ( $is_opera ) 	$classes[] = 'browser-opera';
	elseif ( $is_NS4 ) 		$classes[] = 'browser-ns4';
	elseif ( $is_safari ) 	$classes[] = 'browser-safari';
	elseif ( $is_chrome ) 	$classes[] = 'browser-chrome';
	elseif ( $is_IE ) 		$classes[] = 'browser-ie';
	else 					$classes[] = 'browser-unknown';

	if ( $is_iphone ) $classes[] = 'iphone';

	//

	return $classes;
}

add_filter( 'body_class', 'theme_body_class' );
