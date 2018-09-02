<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * SVG
 */

/**
 * Add SVG definitions to the footer.
 */
function theme_svg_include()
{
	locate_template( 'assets/images/sprite.svg', true, true );
}

add_action( 'wp_footer', 'theme_svg_include', 9999 );

/**
 * Return SVG markup.
 *
 * @param array $args {
 *     Parameters needed to display an SVG.
 *
 *     @type string $id  Required SVG id.
 *     @type string $title Optional SVG title.
 *     @type string $desc  Optional SVG description.
 * }
 * @return string SVG markup.
 */
function theme_get_svg( $args ) 
{
	if ( ! is_array( $args ) ) 
	{
		$args = array( 'id' => $args );
	}

	$defaults = array
	(
		'id'       => '',
		'title'    => '',
		'desc'     => '',
		'fallback' => false
	);

	$args = wp_parse_args( $args, $defaults );

	if ( ! $args['id'] ) 
	{
		return;
	}

	// Attributes

	$atts = array
	(
		'aria-hidden' => 'true'
	);

	if ( stripos( $args['id'], 'icon-' ) === 0 ) 
	{
		$atts['class'] = "icon {$args['id']}";
	}

	else
	{
		$atts['class'] = $args['id'];
	}

	if ( $args['title'] ) 
	{
		$unique_id = uniqid();

		$atts['aria-labelledby'] = "title-$unique_id";

		if ( $args['desc'] ) 
		{
			$atts['aria-labelledby'] .= " desc-$unique_id";
		}
	}

	// Markup.

	$svg = sprintf( '<svg%s>', theme_esc_attr( $atts ) );

	if ( $args['title'] )
	{
		$svg .= sprintf( '<title id="title-%s">%s</title>', $unique_id, esc_html( $args['title'] ) );

		if ( $args['desc'] ) 
		{
			$svg .= sprintf( '<desc id="desc-%s">%s</desc>', $unique_id, esc_html( $args['desc'] ) );
		}
	}

	$svg .= sprintf( '<use href="#%1$s" xlink:href="#%1$s"></use> ', esc_attr( $args['id'] ) );

	// Add some markup to use as a fallback for browsers that do not support SVGs.
	if ( $args['fallback'] )
	{
		$svg .= sprintf( '<span class="svg-fallback %s"></span>', esc_attr( $atts['class'] ) );
	}

	$svg .= '</svg>';

	return $svg;
}

function theme_menu_item_icon( $title, $item, $args, $depth )
{
    $icon = null;

    foreach ( $item->classes as $class ) 
    {
        if ( preg_match( '/^menu-item-icon-([a-z0-9_-]+)$/', $class, $matches ) ) 
        {
            $icon = $matches[1];

            break;
        }
    }

    if ( $icon ) 
    {
        return theme_get_svg( array( 'id' => "icon-$icon", 'title' => $item->title ) ) . sprintf( '<span>%s</span>', esc_html( $title ) );
    }

    return $title;
}

add_filter( 'nav_menu_item_title', 'theme_menu_item_icon', 10, 4 );

function theme_svg_body_class( $classes )
{
	$classes[] = 'no-svg';

	return $classes;
}

add_filter( 'body_class', 'theme_svg_body_class' );
