<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

add_shortcode( 'search-form', 'get_search_form' );

/**
 * Style
 *
 * Usage: `[style color="primary|secondary|…" weight="bold|light|…"]Text to style[/style]`
 *
 * @return string
 */
function theme_style( $args, $content )
{
	/**
	 * Arguments
	 */

	$defaults = array
	(
		'color'       => '',
		'weight'      => '',
		'font_family' => '',
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * Attributes
	 */

	$atts = array
	(
		'class' => ''
	);

	// Color
	if ( $args['color'] ) 
	{
		$atts['class'] .= sprintf( ' text-%s', sanitize_html_class( $args['color'] ) );
	}

	// Weight
	if ( $args['weight'] ) 
	{
		$atts['class'] .= sprintf( ' font-weight-%s', sanitize_html_class( $args['weight'] ) );
	}

	// Font Family
	if ( $args['font_family'] ) 
	{
		$atts['class'] .= sprintf( ' font-family-%s', sanitize_html_class( $args['font_family'] ) );
	}

	// Sanitize class
	$atts['class'] = ltrim( $atts['class'] );

	// Remove empty attributes
	$atts = array_filter( $atts );

	/**
	 * Markup
	 */

	// Check if attributes
	if ( $atts ) 
	{
		// Set wrapper

		if ( strpos( $args['content'], '</p>' ) !== false ) 
		{
			$str = sprintf( '<div%s>%s</div>', theme_esc_attr( $atts ), $content );
		}

		else
		{
			$str = sprintf( '<span%s>%s</span>', theme_esc_attr( $atts ), $content );
		}
	}

	else
	{
		$str = $content;
	}

	return $str;
}

add_shortcode( 'style', 'theme_style' );
