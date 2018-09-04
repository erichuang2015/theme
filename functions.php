<?php
/**
 * Theme functions and definitions
 */

// Load constants.
require get_theme_file_path( 'includes/constants.php' );

// Load common helper functions
require get_parent_theme_file_path( 'includes/common.php' );

/**
 * Theme Setup
 */
function theme_setup() 
{
	// Make theme available for translation.
	load_theme_textdomain( 'theme', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'assets/css/editor-style.min.css' ) );

	// Nav Menus
	register_nav_menus( array
	(
		'top_1'     => __( 'Top menu left', 'theme' ),
		'top_2'     => __( 'Top menu right', 'theme' ),
		'primary_1' => __( 'Primary menu left', 'theme' ),
		'primary_2' => __( 'Primary menu right', 'theme' )
	));

	// Features
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'theme-settings' );
	add_theme_support( 'theme-breadcrumbs' );

	// Custom image sizes
	add_image_size( 'theme-full-width', 1920, 1080 );
}

add_action( 'after_setup_theme', 'theme_setup' );

/**
 * Support Init
 */
function theme_support_init() 
{
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// Settings
	if ( current_theme_supports( 'theme-settings' ) && is_plugin_active( THEME_PLUGIN_ACF_PRO ) ) 
	{
		require_once get_theme_file_path( 'includes/settings.php' );
	}

	// Flexible Content
	if ( current_theme_supports( 'theme-flexible-content' ) && is_plugin_active( THEME_PLUGIN_ACF_PRO ) ) 
	{
		require_once get_theme_file_path( 'includes/flexible-content.php' );
	}

	// Breadcrumbs
	if ( current_theme_supports( 'theme-breadcrumbs' ) && is_plugin_active( THEME_PLUGIN_BREADCRUMB_NAVXT ) ) 
	{
		require_once get_theme_file_path( 'includes/breadcrumbs.php' );
	}
}

add_action( 'after_setup_theme', 'theme_support_init', 99 );

/**
 * Widgets Init
 */
function theme_widgets_init()
{
	// Registers sidebars

	register_sidebar( array
	(
		'id'            => 'sidebar-1',
		'name'          => __( 'Primary Sidebar', 'theme' ),
		'description'   => __( 'Section on the left side.', 'theme' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));

	register_sidebar( array
	(
		'id'            => 'sidebar-2',
		'name'          => __( 'Content Sidebar', 'theme' ),
		'description'   => __( 'Section on the right side.', 'theme' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
	
	register_sidebar( array
	(
		'id'            => 'sidebar-3',
		'name'          => __( 'Footer', 'theme' ),
		'description'   => __( 'The footer section.', 'theme' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
}

add_action( 'widgets_init', 'theme_widgets_init' );

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
	wp_enqueue_style( 'theme-style', get_theme_file_uri( 'assets/css/theme.min.css' ) );
	wp_enqueue_script( 'theme-script', get_theme_file_uri( "assets/js/theme{$min}.js" ), array( 'jquery' ), false, true );

	wp_localize_script( 'theme-script', 'theme', (array) apply_filters( 'theme_js_vars', array
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

/**
 * Options
 */
function theme_get_option( $name )
{
	return apply_filters( 'theme/option', null, $name );
}

// Icon functions
require_once get_theme_file_path( 'includes/icons.php' );

// Custom Nav Menu for this theme.
require get_parent_theme_file_path( 'includes/nav-menu.php' );

// Custom template tags for this theme.
require get_parent_theme_file_path( 'includes/template-tags.php' );

// Additional features to allow styling of the templates.
require get_parent_theme_file_path( 'includes/template-functions.php' );
