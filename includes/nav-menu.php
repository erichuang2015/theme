<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly
/**
 * Nav menu related functions
 */

class Theme_Nav_Menu_Walker extends Walker_Nav_Menu
{
    public function start_lvl( &$output, $depth = 0, $args = array() )
    {
        $indent = str_repeat( "\t", $depth );

        $class = '';

        // Check depth
        if ( $depth == 0 )
        {
            // Add dropdown menu
            $class = ' dropdown-menu';
        }

        $output .= "\n$indent<ul class=\"sub-menu{$class}\">\n";
    }
}

function theme_nav_menu_css_class( $classes, $item, $args, $depth )
{
    // Check walker
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
    // Check walker
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

        // Add Bootstrap dropdown attributes
        if ( in_array( 'menu-item-has-children', $item->classes ) )
        {
            $atts['class']         .= ' dropdown-toggle';
            $atts['data-toggle']   = 'dropdown';
            $atts['role']          = 'button';
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }

        // Set active
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
        if ( preg_match( '/^menu-item-button-([a-z0-9_-]+)$/', $class, $matches ) && $matches[1] != 'btn' ) 
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

/**
 * Menu Item Text
 *
 * Use CSS class `menu-item-unlink` to remove link.
 * Item class `navbar-text` or `nav-text` is added depending on the menu location.
 */
function theme_menu_item_text( $classes, $item, $args, $depth )
{
    // Check class
    if ( in_array( 'menu-item-unlink', $item->classes ) ) 
    {
        // Check menu location
        if ( $args->theme_location == 'primary_1' || $args->theme_location == 'primary_2' ) 
        {
            $classes[] = 'navbar-text';
        }

        else
        {
            $classes[] = 'nav-text';
        }
    }

    return $classes;
}

add_filter( 'nav_menu_css_class', 'theme_menu_item_text', 10, 4 );

/**
 * Menu Item Unlink
 *
 * Use CSS class `menu-item-unlink` to remove link.
 */
function theme_menu_item_unlink( $item_output, $item, $depth, $args )
{
    // Check class
    if ( in_array( 'menu-item-unlink', $item->classes ) ) 
    {
        // Remove Link
        $item_output = preg_replace( '#<a.*?>(.*)</a>#', '$1', $item_output );
    }

    return $item_output;
}

add_filter( 'walker_nav_menu_start_el', 'theme_menu_item_unlink', 10, 4 );

/**
 * Menu Item Template
 *
 * Replaces the all item content with content rendered in a template.
 *
 * Template can be set by following CSS class format: `menu-item-template-{template_name}`.
 * The loaded template will be: `template-parts/menu-item-{template_name}.php`
 */
function theme_menu_item_template( $item_output, $item, $depth, $args )
{
    foreach ( $item->classes as $class ) 
    {
        // Check class
        if ( preg_match( '/^menu-item-template-([a-z0-9_]+)$/', $class, $matches ) ) 
        {
            $located = locate_template( "template-parts/menu-item-{$matches[1]}.php", false );

            if ( $located ) 
            {
                ob_start();

                load_template( $located, false );

                return ob_get_clean();
            }
        }
    }

    return $item_output;
}

add_filter( 'walker_nav_menu_start_el', 'theme_menu_item_template', 10, 4 );
