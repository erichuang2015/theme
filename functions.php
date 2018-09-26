<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Theme functions and definitions
 */

// Constants
require_once get_theme_file_path( 'includes/constants.php' );

// Autoloader
require_once get_theme_file_path( 'includes/autoload.php' );

// Common helper functions
require_once get_parent_theme_file_path( 'includes/common.php' );

/**
 * Setup
 */
function theme_setup()
{
	// Make theme available for translation.
	load_theme_textdomain( 'theme', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'assets/css/editor-style.min.css' ) );

	// Register menu locations.
	register_nav_menus( array
	(
		//'top_left'     => __( 'Top Menu (left)', 'theme' ),
		//'top_right'    => __( 'Top Menu (right)', 'theme' ),
		//'main_left'    => __( 'Main Menu (left)', 'theme' ),
		//'main_right'   => __( 'Main Menu (right)', 'theme' ),
		//'bottom_right' => __( 'Bottom Menu (right)', 'theme' ),
		//'bottom_left'  => __( 'Bottom Menu (left)', 'theme' ),
		'top_right'    => __( 'Top Menu', 'theme' ),
		'main_right'   => __( 'Main Menu', 'theme' ),
		'bottom_right' => __( 'Bottom Menu', 'theme' ),
	));

	// Features
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );

	add_theme_support( 'theme-post-loader' );

	// Custom image sizes
	add_image_size( 'theme-full-width', 1920, 1080 );
}

add_action( 'after_setup_theme', 'theme_setup' );

/**
 * Support Init
 */
function theme_support_init()
{
	// Options Page
	if ( current_theme_supports( 'theme-options-page' ) ) 
	{
		require_once get_parent_theme_file_path( 'includes/options-page.php' );
	}

	// Options Page
	if ( current_theme_supports( 'theme-post-loader' ) ) 
	{
		require_once get_parent_theme_file_path( 'includes/post-loader.php' );
	}
}

add_action( 'after_setup_theme', 'theme_support_init', 999 );

/**
 * Widgets Init
 */
function theme_widgets_init()
{
	/**
	 * Register widget areas.
	 */

	// Primary Sidebar
	register_sidebar( array
	(
		'id'            => 'sidebar-1',
		'name'          => __( 'Primary Sidebar', 'theme' ),
		'description'   => __( 'Section on the left side.', 'theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));

	// Content Sidebar
	register_sidebar( array
	(
		'id'            => 'sidebar-2',
		'name'          => __( 'Content Sidebar', 'theme' ),
		'description'   => __( 'Section on the right side.', 'theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));

	// Footer Sidebars

	$amount = 3; $index = 4;

	$cardinals = array
	(
		1 => __( 'first', 'theme' ),
		2 => __( 'second', 'theme' ),
		3 => __( 'thirth', 'theme' ),
		4 => __( 'fourth', 'theme' ),
		5 => __( 'fifth', 'theme' ),
		6 => __( 'sixth', 'theme' ),
		7 => __( 'seventh', 'theme' ),
		8 => __( 'eighth', 'theme' ),
		9 => __( 'ninth', 'theme' ),
	);

	for ( $n = 1; $n <= $amount; $n++ ) 
	{ 
		if ( $amount == 1 ) 
		{
			$name        = __( 'Footer', 'theme' );
			$description = __( 'Footer section.', 'theme' );
		}

		else
		{
			$name        = sprintf( __( 'Footer Column %d', 'theme' ), $n );
			$description = sprintf( __( '%s column in footer section.', 'theme' ), ucfirst( $cardinals[ $n ] ) );
		}

		register_sidebar( array
		(
			'id'            => sprintf( 'sidebar-%d', $index++ ),
			'name'          => $name,
			'description'   => $description,
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));
	}
}

add_action( 'widgets_init', 'theme_widgets_init' );

/**
 * Get Option
 *
 * @param string $name The option name.
 *
 * @return mixed The option value.
 */
function theme_get_option( $name )
{
	return apply_filters( 'theme/option', null, $name );
}

/**
 * Get Grid Breakpoints
 *
 * @return array
 */
function theme_get_grid_breakpoints()
{
	return (array) apply_filters( 'theme/grid_breakpoints', array
	(
		// slug => width
		'xs' => 0,
		'sm' => 576,
		'md' => 768,
		'lg' => 992,
		'xl' => 1200
	));
}

/**
 * JavaScript Detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 */
function theme_javascript_detection() 
{
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}

add_action( 'wp_head', 'theme_javascript_detection', 0 );

/**
 * Scripts
 */
function theme_scripts()
{
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Scroll To
	wp_register_script( 'jquery-scrollto', get_theme_file_uri( 'assets/js/jquery.scrollTo.min.js' ), array( 'jquery' ), '2.1.2', true );
	
	// Fancybox
	wp_register_script( 'jquery-fancybox', get_theme_file_uri( 'assets/js/jquery.fancybox.min.js' ), array( 'jquery' ), '3.4.2', true );
	wp_register_style( 'jquery-fancybox', get_theme_file_uri( 'assets/css/jquery-fancybox.min.css' ), null, '3.4.2' );

	// Owl Carousel
	wp_register_script( 'owl-carousel', get_theme_file_uri( 'assets/js/owl.carousel.min.js' ), array( 'jquery' ), '2.3.4', true );
	wp_register_style( 'owl-carousel', get_theme_file_uri( 'assets/css/owl-carousel.min.css' ), null, '2.3.4' );
	wp_register_style( 'owl-carousel-theme', get_theme_file_uri( 'assets/css/owl-theme-default.min.css' ), null, '2.3.4' );

	// Sticky Kit
	wp_enqueue_script( 'jquery-sticky-kit', get_theme_file_uri( 'assets/js/jquery.sticky-kit.min.js' ), array( 'jquery' ), '1.1.2', true );
	
	// Bootstrap
	wp_enqueue_script( 'popper-js', get_theme_file_uri( 'assets/js/popper.min.js' ), false, '1.14.3', true );
	wp_enqueue_script( 'bootstrap', get_theme_file_uri( 'assets/js/bootstrap.min.js' ), array( 'jquery' ), '4.1.3', true );
	
	// HTML 5
	wp_enqueue_script( 'html5', get_theme_file_uri( 'assets/js/html5shiv.min.js' ), array(), '3.7.3' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	// Core
	wp_enqueue_style( 'theme', get_theme_file_uri( 'assets/css/theme.min.css' ) );
	wp_enqueue_script( 'theme', get_theme_file_uri( "assets/js/theme{$min}.js" ), array( 'jquery' ), false, true );

	wp_localize_script( 'theme', 'theme', (array) apply_filters( 'theme_js_vars', array
	(
		'ajaxurl'         => admin_url( 'admin-ajax.php' ),
		'gridBreakpoints' => theme_get_grid_breakpoints(),
	)));

	// Comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) 
	{
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'theme_scripts' );

// Custom Customizer Settings
require_once get_parent_theme_file_path( 'includes/customizer.php' );
	
// MCE
require_once get_parent_theme_file_path( 'includes/mce.php' );

// Icons
require_once get_parent_theme_file_path( 'includes/icons.php' );

// Custom nav menu features.
require_once get_parent_theme_file_path( 'includes/nav-menus.php' );

// Custom template tags for this theme.
require_once get_parent_theme_file_path( '/includes/template-tags.php' );

// Additional features to allow styling of the templates.
require_once get_parent_theme_file_path( '/includes/template-functions.php' );
