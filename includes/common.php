<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Common functions.
 */

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