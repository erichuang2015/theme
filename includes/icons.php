<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * SVG
 *
 * Include `add_theme_support( 'theme-svg' )` to enable this feature.
 */

/**
 * Include SVG Icons
 *
 * Prints svg icons definitions so we can refere to it for 
 * displaying an icon.
 */
function theme_include_svg_icons()
{
	$file = get_theme_file_path( 'assets/images/icons.svg' );

	echo '<div class="svg-sprite">';

	require_once $file;

	echo '</div>';
}

add_action( 'wp_footer', 'theme_include_svg_icons', 9999 );

/**
 * Get SVG
 *
 * Return SVG markup.
 *
 * This theme doesn't use the SVG title or description attributes; non-decorative icons are described with .sr-only.
 * However, child themes can use the title and description to add information to non-decorative SVG icons to improve accessibility.
 * Example 1 with title: <?php echo theme_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ) ) ); ?>
 * Example 2 with title and description: <?php echo theme_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ), 'desc' => __( 'This is the description', 'textdomain' ) ) ); ?>
 * See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/.
 *
 * @param array $args {
 *     Parameters needed to display an SVG.
 *
 *     @type string $icon     Required SVG icon filename.
 *     @type string $title    Optional SVG title.
 *     @type string $desc     Optional SVG description.
 *     @type string $fallback Optional fallback.
 * }
 * @return string SVG markup.
 */

function theme_get_svg( $args ) 
{
	$defaults = array
	(
		'icon'     => '',
		'title'    => '',
		'desc'     => '',
		'fallback' => false
	);

	$args = wp_parse_args( $args, $defaults );

	if ( $args['icon'] === '' ) 
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

	// The whitespace around `<use>` is intentional - it is a work around to a keyboard navigation bug in Safari 10.
	// See https://core.trac.wordpress.org/ticket/38387.
	$svg .= sprintf( ' <use href="#icon-%1$s" xlink:href="#icon-%1$s"></use> ', esc_attr( $args['icon'] ) );

	// Fallback for browsers that do not support SVGs.
	if ( $args['fallback'] )
	{
		$svg .= sprintf( '<span class="svg-fallback %s"></span>', esc_attr( $atts['class'] ) );
	}

	$svg .= '</svg>';

	return $svg;
}

/**
 * Menu Item Icon
 *
 * Adds an icon inside a menu item.
 *
 * Define the icon by following CSS class format: `menu-item-icon-{icon_name}`
 */
function theme_menu_item_icon( $title, $item, $args, $depth )
{
    foreach ( $item->classes as $class ) 
    {
        if ( preg_match( '/^menu-item-icon-([a-z0-9_]+)$/', $class, $matches ) ) 
        {
            $icon = $matches[1];

            return theme_get_svg( array( 'icon' => $icon ) ) . sprintf( '<span>%s</span>', esc_html( $title ) );
        }
    }

    return $title;
}

add_filter( 'nav_menu_item_title', 'theme_menu_item_icon', 10, 4 );

/**
 * Menu Item Social
 *
 * Item with CSS class `menu-item-social` adds Social Icon based on the item URL.
 */
function theme_menu_item_social_icon( $title, $item, $args, $depth )
{
    if ( in_array( 'menu-item-social', $item->classes ) ) 
    {
        $social_icons = theme_get_social_links_icons();

        foreach ( $social_icons as $attr => $icon ) 
        {
            if ( stripos( $item->url, $attr ) !== false ) 
            {
                $title = theme_get_svg( array( 'icon' => $icon ) ) . sprintf( '<span>%s</span>', esc_html( $title ) );
            }
        }
    }

    return $title;
}

add_filter( 'nav_menu_item_title', 'theme_menu_item_social_icon', 10, 4 );

/**
 * Get Social Links Icons
 *
 * Returns an array of supported social links (URL and icon name).
 *
 * @return array $social_links_icons
 */
function theme_get_social_links_icons() 
{
	// Supported social links icons.
	$social_links_icons = array
	(
		'behance.net'     => 'behance',
		'codepen.io'      => 'codepen',
		'deviantart.com'  => 'deviantart',
		'digg.com'        => 'digg',
		'docker.com'      => 'dockerhub',
		'dribbble.com'    => 'dribbble',
		'dropbox.com'     => 'dropbox',
		'facebook.com'    => 'facebook',
		'flickr.com'      => 'flickr',
		'foursquare.com'  => 'foursquare',
		'plus.google.com' => 'google-plus',
		'github.com'      => 'github',
		'instagram.com'   => 'instagram',
		'linkedin.com'    => 'linkedin',
		'mailto:'         => 'envelope-o',
		'medium.com'      => 'medium',
		'pinterest.com'   => 'pinterest-p',
		'pscp.tv'         => 'periscope',
		'getpocket.com'   => 'get-pocket',
		'reddit.com'      => 'reddit-alien',
		'skype.com'       => 'skype',
		'skype:'          => 'skype',
		'slideshare.net'  => 'slideshare',
		'snapchat.com'    => 'snapchat-ghost',
		'soundcloud.com'  => 'soundcloud',
		'spotify.com'     => 'spotify',
		'stumbleupon.com' => 'stumbleupon',
		'tumblr.com'      => 'tumblr',
		'twitch.tv'       => 'twitch',
		'twitter.com'     => 'twitter',
		'vimeo.com'       => 'vimeo',
		'vine.co'         => 'vine',
		'vk.com'          => 'vk',
		'wordpress.org'   => 'wordpress',
		'wordpress.com'   => 'wordpress',
		'yelp.com'        => 'yelp',
		'youtube.com'     => 'youtube',
	);
	
	return apply_filters( 'theme/social_links_icons', $social_links_icons );
}

