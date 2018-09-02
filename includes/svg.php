<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * SVG
 *
 * Include `add_theme_support( 'theme-svg' )` to enable this feature.
 */

/**
 * Include
 */
function theme_svg_include()
{
	$paths = array
	(
		'icons' => get_theme_file_path( 'assets/images/icons.svg' )
	);

	$paths = apply_filters( 'theme/svg_include', $paths );

	if ( ! is_array( $paths ) || ! count( $paths ) ) 
	{
		return;
	}

	echo '<div class="svg-sprite">';

	foreach ( $paths as $path ) 
	{
		load_template( $path, true );
	}

	echo '</div>';
}

add_action( 'wp_footer', 'theme_svg_include', 9999 );

/**
 * Return SVG markup.
 *
 * @param array $args {
 *     Parameters needed to display an SVG.
 *
 *     @type string $id       Required SVG id.
 *     @type string $title    Optional SVG title.
 *     @type string $desc     Optional SVG description.
 *     @type string $fallback Optional fallback.
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
		trigger_error( __( 'id is reguired.', 'theme' ) );

		return;
	}

	// Attributes

	$atts = array
	(
		'aria-hidden' => 'true',
		'class'       => "icon icon-{$args['icon']}"
	);

	// -- Add icon class
	if ( stripos( $args['id'], 'icon-' ) === 0 ) 
	{
		$atts['class'] = "icon {$args['id']}";
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

function theme_svg_menu_item( $title, $item, $args, $depth )
{
    $svg_id = null;

    foreach ( $item->classes as $class ) 
    {
        if ( preg_match( '/^svg-([a-z0-9_-]+)$/', $class, $matches ) ) 
        {
            $svg_id = $matches[1];

            break;
        }
    }

    if ( $svg_id )
    {
        return theme_get_svg( $svg_id ) . sprintf( '<span>%s</span>', esc_html( $title ) );
    }

    return $title;
}

add_filter( 'nav_menu_item_title', 'theme_svg_menu_item', 10, 4 );

function theme_svg_body_class( $classes )
{
	$classes[] = 'no-svg';

	return $classes;
}

add_filter( 'body_class', 'theme_svg_body_class' );
