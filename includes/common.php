<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Common Helper Functions
 */

/**
 * Escape HTML Attributes
 *
 * @param string|array $attribute
 *
 * @return string
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
 * Load Template
 */
function theme_load_template( $template_name, $vars = array(), $__return = false )
{
	$__located = locate_template( $template_name, false );

	$vars = apply_filters( 'theme/template_vars', $vars, $template_name );

	if ( $__located ) 
	{
		if ( $__return ) 
		{
			ob_start();
		}

		extract( $vars );

		include $__located;

		if ( $__return ) 
		{
			return ob_get_clean();
		}
	}
}

/**
 * Function Exists
 *
 * @param string|array $function_name
 *
 * @return boolean
 */
function theme_function_exists( $function_name )
{
	if ( func_num_args() > 1 ) 
	{
		$function_names = func_get_args();
	}

	else
	{
		$function_names = (array) $function_name;
	}

	foreach ( $function_names as $function_name ) 
	{
		if ( ! function_exists( $function_name ) ) 
		{
			return false;
		}
	}

	return true;
}
