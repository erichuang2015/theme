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
 * Menu Item Modal
 * 
 * Menu item with CSS class 'menu-item-modal' sets link attribute `data-toggle="modal"`.
 * Set item 'URL' setting to '#my-modal-id' to refer to the modal.
 * 
 * Note: Only works with 'Custom Links'.
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
 * Menu Item Button
 *
 * Converts a menu item a button.
 *
 * Example: Menu item with CSS class `menu-item-button-primary` sets link class 'btn btn-primary'.
 */
function theme_menu_item_button( $atts, $item, $args, $depth )
{
    // Get button classes

    $classes = array();

    foreach ( $item->classes as $class ) 
    {
        if ( preg_match( '/^menu-item-button-([a-z0-9_-]+)/', $class, $matches ) && $matches[1] != 'btn' ) 
        {
            $classes[] = "btn-{$matches[1]}";
        }
    }

    // Check if button classes
    if ( count( $classes ) ) 
    {
        // Add 'btn' class 
        array_unshift( $classes, 'btn' );
        
        // Make sure class is set
        if ( ! isset( $atts['class'] ) ) 
        {
           $atts['class'] = '';
        }

        else
        {
            // Removes 'nav-link' class
            $atts['class'] = preg_replace( '/(^| )nav-link( |$)/', '', $atts['class'] );
        }

        if ( $atts['class'] )
        {
            $atts['class'] .= ' ';
        }

        // Add button classes to link attributes
        $atts['class'] .= implode( ' ', $classes );
    }

    return $atts;
}

add_filter( 'nav_menu_link_attributes', 'theme_menu_item_button', 15, 4 );
