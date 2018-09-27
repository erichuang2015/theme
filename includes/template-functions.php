<?php
/**
 * Additional features to allow styling of the templates
 */

if ( ! function_exists( 'theme_has_container' ) ) :
/**
 * Has Container
 *
 * Returns true if the #content element has a .container element.
 *
 * @return boolean
 */
function theme_has_container()
{
	return ! is_page_template( 'page-templates/full-width.php' );
}

endif; // theme_has_container

if ( ! function_exists( 'theme_is_full_width' ) ) :
/**
 * Is Full Width
 * 
 * Returns true when no left or right sidebar is displayed. 
 *
 * @return boolean
 */
function theme_is_full_width()
{
	if ( ( ! is_active_sidebar( 'sidebar-1' ) && ! is_active_sidebar( 'sidebar-2' ) )
		|| is_page_template( 'page-templates/full-width-fixed.php' ) 
		|| is_page_template( 'page-templates/full-width.php' ) ) 
	{
		return true;
	}

	return false;
}

endif; // theme_is_full_width

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
	/**
	 * Browser
	 *
	 * @link https://codex.wordpress.org/Global_Variables
	 */

	global $is_iphone, $is_chrome, $is_safari, $is_NS4, $is_opera, $is_macIE, $is_winIE, $is_gecko, $is_lynx, $is_IE, $is_edge;

	$browsers = array
	(
		'iphone' => $is_iphone,
		'chrome' => $is_chrome,
		'safari' => $is_safari,
		'ns4'    => $is_NS4,
		'opera'  => $is_opera,
		'mac-ie' => $is_macIE,
		'win-ie' => $is_winIE,
		'gecko'  => $is_gecko,
		'lynx'   => $is_lynx,
		'ie'     => $is_IE,
		'edge'   => $is_edge,
	);

	foreach ( array_filter( $browsers ) as $browser => $value ) 
	{
		 $classes[] = "browser-$browser";
	}

	//

	return $classes;
}

add_filter( 'body_class', 'theme_body_class' );
