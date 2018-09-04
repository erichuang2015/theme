<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Icons
 */

/**
 * Add SVG definitions to the footer.
 */
function theme_include_svg_icons()
{
	// Define SVG sprite file.
	$svg_icons = get_theme_file_path( '/assets/images/icons.svg' );

	// If it exists, include it.
	if ( file_exists( $svg_icons ) ) 
	{
		require_once( $svg_icons );
	}
}

add_action( 'wp_footer', 'theme_include_svg_icons', 9999 );

/**
 * Get Icon
 *
 * Return SVG markup.
 *
 * @param array $args {
 *     Parameters needed to display an SVG.
 *
 *     @type string  $icon     Required SVG icon filename.
 *     @type string  $title    Optional SVG title.
 *     @type string  $desc     Optional SVG description.
 *     @type boolean $fallback Optional fallback.
 * }
 * @return string SVG markup.
 */
function theme_get_icon( $args ) 
{
	if ( ! is_array( $args ) ) 
	{
		$args = array( 'icon' => $args );
	}

	// Set defaults.
	$defaults = array
	(
		'icon'     => '',
		'title'    => '',
		'desc'     => '',
		'fallback' => false,
	);

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Define an icon.
	if ( $args['icon'] === '' ) 
	{
		return __( 'Please define an SVG icon filename.', 'theme' );
	}

	$atts = array
	(
		'class'       => "icon icon-{$args['icon']}",
		'aria-hidden' => 'true',
		'role'        => 'img'
	);

	/*
	 * This theme doesn't use the SVG title or description attributes; non-decorative icons are described with .sr-only.
	 *
	 * However, child themes can use the title and description to add information to non-decorative SVG icons to improve accessibility.
	 *
	 * Example 1 with title: <?php echo theme_get_icon( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ) ) ); ?>
	 *
	 * Example 2 with title and description: <?php echo theme_get_icon( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ), 'desc' => __( 'This is the description', 'textdomain' ) ) ); ?>
	 *
	 * See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/.
	 */
	if ( $args['title'] ) 
	{
		$unique_id = uniqid();

		$atts['aria-hidden']     = '';
		$atts['aria-labelledby'] = "title-$unique_id";
		
		if ( $args['desc'] ) 
		{
			$atts['aria-labelledby'] .= " desc-$unique_id";
		}
	}

	// Begin SVG markup.
	$svg = sprintf( '<svg%s>', theme_esc_attr( $atts ) );

	// Display the title.
	if ( $args['title'] ) 
	{
		$svg .= sprintf( '<title id="title-%s">%s</title>', $unique_id, esc_html( $args['title'] ) );

		// Display the desc only if the title is already set.
		if ( $args['desc'] ) 
		{
			$svg .= sprintf( '<desc id="desc-%s">%s</desc>', $unique_id, esc_html( $args['desc'] ) );
		}
	}

	/*
	 * Display the icon.
	 *
	 * The whitespace around `<use>` is intentional - it is a work around to a keyboard navigation bug in Safari 10.
	 *
	 * See https://core.trac.wordpress.org/ticket/38387.
	 */

	$svg .= sprintf( ' <use href="#icon-%1$s" xlink:href="#icon-%1$s"></use> ', esc_html( $args['icon'] ) );

	// Add some markup to use as a fallback for browsers that do not support SVGs.
	if ( $args['fallback'] )
	{
		$svg .= sprintf( '<span class="svg-fallback icon-%s></span>', esc_attr( $args['icon'] ) );
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
    	// Check class
        if ( preg_match( '/^menu-item-icon-([a-z0-9_]+)$/', $class, $matches ) ) 
        {
            $icon = $matches[1];

            return theme_get_icon( $icon ) . sprintf( '<span>%s</span>', esc_html( $title ) );
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
	// Check class
    if ( in_array( 'menu-item-social', $item->classes ) ) 
    {
        $social_icons = theme_get_social_links_icons();

        foreach ( $social_icons as $domain => $icon ) 
        {
        	// Check if url contains icon domain
            if ( stripos( $item->url, $domain ) !== false ) 
            {
            	// Add icon
                $title = theme_get_icon( $icon ) . sprintf( '<span>%s</span>', esc_html( $title ) );
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
