<?php
/**
 * Additional features to allow styling of the templates
 */

/**
 * Has Container
 *
 * Return true if the #content element has a .container element.
 */
function theme_has_container()
{
	return ! is_page_template( 'page-templates/full-width.php' );
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function theme_body_classes( $classes )
{
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

	return $classes;
}
add_filter( 'body_class', 'theme_body_classes' );

/**
 * Checks to see if we're on the front page or not.
 */
function theme_is_frontpage()
{
	return ( is_front_page() && ! is_home() );
}


if ( ! function_exists( 'theme_site_logo' ) ) :
/**
 * Site Logo
 *
 * Outputs the site's logo if available.
 * If not, the blog name will be displayed.
 */
function theme_site_logo()
{
	$logos = array
	(
		'dark'        => array( 'attachment' => theme_get_option( 'site_logo_dark' )	   , 'type' => array( 'dark', 'large' ) ),
		'dark_small'  => array( 'attachment' => theme_get_option( 'site_logo_dark_small' ) , 'type' => array( 'dark', 'small' ) ),
		'light'       => array( 'attachment' => theme_get_option( 'site_logo_light' )	   , 'type' => array( 'light', 'large' ) ),
		'light_small' => array( 'attachment' => theme_get_option( 'site_logo_light_small' ), 'type' => array( 'light', 'small' ) )
	);

	// Filters logo's with attachments

	$logos = array_filter( $logos, function( $logo )
	{
		return $logo['attachment'] && get_post_type( $logo['attachment'] );
	});

	// Large logo's are also small logo's when small version is not set
	
	if ( isset( $logos['dark'] ) && ! isset( $logos['dark_small'] ) ) 
	{
		$logos['dark']['type'] = array_merge( $logos['dark']['type'], $logos['dark_small']['type'] );
	}

	if ( isset( $logos['light'] ) && ! isset( $logos['light_small'] ) ) 
	{
		$logos['dark']['type'] = array_merge( $logos['light']['type'], $logos['light_small']['type'] );
	}

	// Checks if there are any logos

	if ( count( $logos ) ) 
	{
		// Prints images

		foreach ( $logos as $logo ) 
		{
			$class = array( 'site-logo' );

			foreach ( $logo['type'] as $type ) 
			{
				$class[ $type ] = "logo-$type";
			}

			$class = implode( ' ', $class );

			echo wp_get_attachment_image( $logo['attachment'], 'site_logo', false, array
	        (
	        	'class' => $class
	        ));
		}
	}

	// Displays blog name

    else
    {
        echo esc_html( get_bloginfo( 'name' ) );
    }
}
endif; // theme_site_logo

