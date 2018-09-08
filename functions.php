<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Theme functions and definitions
 */

// Load constants.
require get_theme_file_path( 'includes/constants.php' );

// Load common helper functions
require get_parent_theme_file_path( 'includes/common.php' );

/**
 * Setup
 */
function theme_setup()
{
	// Make theme available for translation.
	load_theme_textdomain( 'theme', get_template_directory() . '/languages' );

	/**
	 * Register menu locations.
	 *
	 * Available locations used in templates:
	 * - top_left
	 * - top_right
	 * - main_left
	 * - main_right
	 * - bottom_left
	 * - bottom_right
	 */

	// Register those needed for the project.
	register_nav_menus( array
	(
		'top_right'    => __( 'Top Menu', 'theme' ),
		'main_right'   => __( 'Main Menu', 'theme' ),
		'bottom_right' => __( 'Bottom Menu', 'theme' ),
	));

	// Features
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'theme-settings' );
	add_theme_support( 'theme-main-visuals' );
	add_theme_support( 'theme-sections' );
	add_theme_support( 'theme-layouts' );
	add_theme_support( 'theme-breadcrumbs' );
	add_theme_support( 'theme-icons' );
	add_theme_support( 'theme-shortcodes' );
	add_theme_support( 'theme-mce' );
	
	// Custom image sizes
	add_image_size( 'theme-full-width', 1920, 1080 );
}

add_action( 'after_setup_theme', 'theme_setup' );

/**
 * Support Init
 */
function theme_support_init()
{
	// Settings
	if ( current_theme_supports( 'theme-settings' ) ) 
	{
		require_once get_parent_theme_file_path( 'includes/settings.php' );
	}

	// Main Visuals
	if ( current_theme_supports( 'theme-main-visuals' ) ) 
	{
		require_once get_parent_theme_file_path( 'includes/main-visuals.php' );
	}

	// Sections
	if ( current_theme_supports( 'theme-sections' ) ) 
	{
		require_once get_parent_theme_file_path( 'includes/sections.php' );
	}

	// Layouts
	if ( current_theme_supports( 'theme-layouts' ) ) 
	{
		require_once get_parent_theme_file_path( 'includes/layouts.php' );
	}

	// Breadcrumbs
	if ( current_theme_supports( 'theme-breadcrumbs' ) ) 
	{
		require_once get_parent_theme_file_path( 'includes/breadcrumbs.php' );
	}

	// Icons
	if ( current_theme_supports( 'theme-icons' ) ) 
	{
		require_once get_parent_theme_file_path( 'includes/icons.php' );
	}

	// Shortcodes
	if ( current_theme_supports( 'theme-shortcodes' ) ) 
	{
		require_once get_parent_theme_file_path( 'includes/shortcodes.php' );
	}

	// TinyMCE
	if ( current_theme_supports( 'theme-mce' ) ) 
	{
		require_once get_parent_theme_file_path( 'includes/mce.php' );
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

	register_sidebar( array
	(
		'id'            => 'sidebar-3',
		'name'          => __( 'Footer Column 1', 'theme' ),
		'description'   => __( 'First column in footer section.', 'theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));

	register_sidebar( array
	(
		'id'            => 'sidebar-4',
		'name'          => __( 'Footer Column 2', 'theme' ),
		'description'   => __( 'Second column in footer section.', 'theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));

	register_sidebar( array
	(
		'id'            => 'sidebar-5',
		'name'          => __( 'Footer Column 3', 'theme' ),
		'description'   => __( 'Thirth column in footer section.', 'theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
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
	wp_register_script( 'jquery-fancybox', get_theme_file_uri( 'assets/js/jquery.fancybox.min.js' ), array( 'jquery' ), '3.3.5', true );
	wp_register_style( 'jquery-fancybox', get_theme_file_uri( 'assets/css/jquery-fancybox.min.css' ), null, '3.3.5' );

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

// Custom nav menu features.
require_once get_parent_theme_file_path( 'includes/nav-menus.php' );

// Custom template tags for this theme.
require get_parent_theme_file_path( '/includes/template-tags.php' );

// Additional features to allow styling of the templates.
require get_parent_theme_file_path( '/includes/template-functions.php' );
