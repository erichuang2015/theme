<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Common functions.
 */

function theme_has_shortcode( $tag, $post_id = 0 )
{
	$post = get_post( $post_id );

	if ( ! $post ) 
	{
		return false;
	}

	return has_shortcode( $post->post_content, $tag );
}

function theme_is_localhost()
{
	$whitelist = array
	(
    	'127.0.0.1',
    	'::1',
	);

	return ! in_array( $_SERVER['REMOTE_ADDR'], $whitelist );
}

/**
 * Escape HTML Attributes
 *
 * @param string|array $attribute
 *
 * @return string HTML
 */
function theme_esc_attr( $attribute )
{
	if ( ! is_array( $attribute ) ) 
	{
		return esc_attr( $attribute );
	}

	$str = '';

	foreach ( $attribute as $name => $value ) 
	{
		$str .= sprintf( ' %s="%s"', $name, esc_attr( $value ) );
	}

	return $str;
}

/**
 * Sort order
 */
function theme_sort_order( $a, $b )
{
    if ( $a['order'] == $b['order'] ) 
    {
        return 0;
    }

    return ( $a['order'] < $b['order'] ) ? -1 : 1;
}
