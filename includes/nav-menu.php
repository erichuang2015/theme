<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

/**
 * Nav menu related functions
 * --------------------------------------------------------------------
 */
class Theme_Nav_Menu_Walker extends Walker_Nav_Menu
{
    public function start_lvl( &$output, $depth = 0, $args = array() )
    {
        $indent = str_repeat( "\t", $depth );

        // adds dropdown menu
        
        $class = '';

        if ( $depth == 0 )
        {
           $class = ' dropdown-menu';
        }

        $output .= "\n$indent<ul class=\"sub-menu{$class}\">\n";
    }
}

function theme_nav_menu_css_class( $classes, $item, $args, $depth )
{
    // checks if our walker is used

    if ( is_object( $args->walker ) && get_class( $args->walker ) == 'Theme_Nav_Menu_Walker' )
    {
        // adds Bootstrap classes

        if ( $depth == 0 )
        {
            $classes[] = 'nav-item';
        }

        if ( $depth == 0 && in_array( 'menu-item-has-children', $classes ) )
        {
            $classes[] = 'dropdown';
        }
    }

    return $classes;
}

add_filter( 'nav_menu_css_class' , 'theme_nav_menu_css_class', 10, 4 );

function theme_nav_menu_link_attributes( $atts, $item, $args, $depth )
{
    // checks if our walker is used

    if ( is_object( $args->walker ) && get_class( $args->walker ) == 'Theme_Nav_Menu_Walker' )
    {
        if ( ! isset( $atts['class'] ) ) 
        {
            $atts['class'] = '';
        }

        if ( $depth == 0 ) 
        {
            $atts['class'] .= ' nav-link';
        }

        else
        {
            $atts['class'] .= ' dropdown-item';
        }

        // adds Bootstrap dropdown attributes

        if ( in_array( 'menu-item-has-children', $item->classes ) )
        {
            $atts['class']         .= ' dropdown-toggle';
            $atts['data-toggle']   = 'dropdown';
            $atts['role']          = 'button';
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }

        // sets active

        if ( in_array( 'current-menu-ancestor', $item->classes ) 
          || in_array( 'current-page-ancestor', $item->classes )
          || in_array( 'current-menu-item', $item->classes ) )
        {
            $atts['class'] .= ' active';
        }

        $atts['class'] = trim( $atts['class'] );
    }

    return $atts;
}

add_filter( 'nav_menu_link_attributes', 'theme_nav_menu_link_attributes', 10, 4 );

function theme_widget_nav_menu_args( $nav_menu_args, $nav_menu, $args, $instance )
{
    // Footer sidebar
    if ( $args['id'] == 'footer' ) 
    {
        $nav_menu_args['walker'] = new Theme_Nav_Menu_Walker();
    }

    return $nav_menu_args;
}

add_filter( 'widget_nav_menu_args', 'theme_widget_nav_menu_args', 10, 4 );

function theme_nav_menu_args( $args )
{
    // checks if our walker is used
    
    if ( is_object( $args['walker'] ) && get_class( $args['walker'] ) == 'Theme_Nav_Menu_Walker' )
    {
        // Makes sure 'nav' class is set

        if ( ! preg_match( '/(^| )nav( |$)/', $args['menu_class'] ) ) 
        {
           $args['menu_class'] .= ' nav';
        }

        $args['container'] = false;
    }

    return $args;
}

add_filter( 'wp_nav_menu_args', 'theme_nav_menu_args' );

/**
 * Modal Toggle
 *
 * class `menu-item-modal` sets link attribute data-toggle="model"
 */
function theme_menu_item_modal( $atts, $item, $args, $depth )
{
    if ( in_array( 'menu-item-modal', $item->classes ) ) 
    {
        $atts['data-toggle'] = 'modal';
    }

    return $atts;
}

add_filter( 'nav_menu_link_attributes', 'theme_menu_item_modal', 10, 4 );

/**
 * Button
 *
 * Converts a menu item link to a button.
 *
 * Sample: `menu-item-btn-primary` sets link class 'btn-primary'
 */
function theme_menu_item_button( $atts, $item, $args, $depth )
{
    // gets button classes

    $buttons = array();

    foreach ( $item->classes as $class ) 
    {
        if ( ! preg_match( '/^menu-item-btn-([a-z0-9_-]+)/', $class, $matches ) ) 
        {
            continue;
        }

        if ( $matches[1] === 'btn' ) 
        {
            continue;
        }

        $buttons[] = sprintf( 'btn-%s', $matches[1] );
    }

    // checks if button classes

    if ( ! count( $buttons ) ) 
    {
        return $atts;
    }

    // adds 'btn' class to button classes

    array_unshift( $buttons, 'btn' );

    if ( isset( $atts['class'] ) ) 
    {
        // removes 'nav-link' class
        $atts['class'] = preg_replace( '/(^| )nav-link( |$)/', '', $atts['class'] );
    }

    else
    {
        $atts['class'] = '';
    }

    // adds button classes

    if ( $atts['class'] ) 
    {
        $atts['class'] .= ' ';
    }

    $atts['class'] .= implode( ' ', $buttons );

    return $atts;
}

add_filter( 'nav_menu_link_attributes', 'theme_menu_item_button', 15, 4 );
