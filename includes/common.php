<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

function theme()
{
	static $instance = null;

	if ( ! $instance ) 
	{
		$instance = new Theme();
	}

	return $instance;
}

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