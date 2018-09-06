<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly
/**
 * Nav menu related functions
 */

/**
 * Get Bootstrap nav type.
 *
 * @return string|null
 */
function theme_get_bootstrap_nav_class( $menu_args )
{
    if ( preg_match( '/(?:^| )(nav|navbar-nav)(?: |$)/', $menu_args->menu_class, $matches ) ) 
    {
        return $matches[1];
    }

    return null;
}

/**
 * Check if Bootstrap navigation.
 *
 * @return boolean
 */
function theme_is_bootstrap_nav( $menu_args )
{
    return theme_get_bootstrap_nav_class( $menu_args ) ? true : false;
}

/**
 * Bootstrap Menu Item
 */
function theme_bootstrap_nav_menu_item( $classes, $item, $args, $depth )
{
    // Check Bootstrap nav.
    if ( ! theme_is_bootstrap_nav( $args ) )
    {
        return $classes;
    }

    if ( $depth == 0 )
    {
        $classes[] = 'nav-item';
    }

    // TODO : Don't use CSS class.
    if ( $depth == 0 && in_array( 'menu-item-has-children', $classes ) )
    {
        $classes[] = 'dropdown';
    }

    return $classes;
}

add_filter( 'nav_menu_css_class' , 'theme_bootstrap_nav_menu_item', 10, 4 );

/**
 * Bootstrap Submenu
 */
function theme_bootstrap_nav_menu_submenu( $classes, $args, $depth )
{
    // Check Bootstrap nav.
    if ( ! theme_is_bootstrap_nav( $args ) )
    {
        return $classes;
    }

    if ( $depth == 0 )
    {
        $classes[] = 'dropdown-menu';
    }

    return $classes;
}

add_filter( 'nav_menu_submenu_css_class', 'theme_bootstrap_nav_menu_submenu', 10, 3 );

/**
 * Bootstrap Menu Item Link
 */
function theme_bootstrap_nav_menu_link( $atts, $item, $args, $depth )
{
    // Check Bootstrap nav.
    if ( ! theme_is_bootstrap_nav( $args ) )
    {
        return $atts;
    }

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

    // Set dropdown attributes. (Only when menu depth is not equal to 1).
    // TODO : Don't use CSS class.
    if ( in_array( 'menu-item-has-children', $item->classes ) && $args->depth != 1 )
    {
        $atts['class']         .= ' dropdown-toggle';
        $atts['data-toggle']   = 'dropdown';
        $atts['role']          = 'button';
        $atts['aria-haspopup'] = 'true';
        $atts['aria-expanded'] = 'false';
    }

    // Set active.
    if ( $item->current || $item->current_item_ancestor || $item->current_item_parent )
    {
        $atts['class'] .= ' active';
    }

    $atts['class'] = trim( $atts['class'] );

    return $atts;
}

add_filter( 'nav_menu_link_attributes', 'theme_bootstrap_nav_menu_link', 10, 4 );

/**
 * Button
 *
 * Usage: Add button CSS classes to item 'CSS Classes' setting by
 * using format `menu-item-{button_class}`.
 *
 * e.g. `menu-item-btn-primary menu-item-btn-sm` 
 * adds link classes `btn btn-primary btn-sm`.
 *
 * Note: `btn` class is automatically added.
 */
function theme_menu_item_button( $atts, $item, $args )
{
    $btn_classes = array();

    // Loop item classes.
    foreach ( $item->classes as $class ) 
    {
        // Get button class.
        if ( preg_match( '/^menu-item-(btn|btn-[a-z0-9_]+)$/', $class, $matches ) ) 
        {
            $btn_class = $matches[1];

            // Add to collection
            $btn_classes[ $btn_class ] = $btn_class;
        }
    }

    // Check button classes.
    if ( $btn_classes ) 
    {
        // Make sure `btn` class is added.
        $btn_classes = array( 'btn' => 'btn') + $btn_classes;

        // Convert to string.
        $btn_classes = implode( ' ', $btn_classes );

        // Make sure link class property is set.
        if ( ! isset( $atts['class'] ) ) 
        {
            $atts['class'] = '';
        }
        
        // Remove `nav-link` class. (to prevent conflict)
        $atts['class'] = preg_replace( '/(?:^| )nav-link(?: |$)/', '', $atts['class'] );

        // Remove spaces.
        $atts['class'] = trim( $atts['class'] );

        // Make room.
        if ( $atts['class'] !== '' ) 
        {
            $atts['class'] .= ' ';
        }

        // Add button classes to link class.
        $atts['class'] .= $btn_classes;
    }

    return $atts;
}

add_filter( 'nav_menu_link_attributes', 'theme_menu_item_button', 10, 3 );

/**
 * Hide title
 *
 * Hides menu item title.
 *
 * Usage: Set menu item CSS class to `menu-item-hide-title`.
 */
function theme_menu_item_hide_title( $title, $item, $args, $depth )
{
    // Search `hide-title` class
    $class_index = array_search( 'menu-item-hide-title', $item->classes );

    // Check if class exists.
    if ( $class_index !== false ) 
    {
        // Hide title and make it available for screenreaders.
        $title = sprintf( '<span class="sr-only">%s</span>', $item->title );
    }

    return $title;
}

add_filter( 'nav_menu_item_title', 'theme_menu_item_hide_title', 5, 4 );

/**
 * Unlink
 *
 * Removes link from menu item.
 *
 * Usage: Set menu item CSS class `menu-item-unlink`.
 */
function theme_menu_item_unlink( $item_output, $item, $depth, $args )
{
    // Check class
    if ( in_array( 'menu-item-unlink', $item->classes ) ) 
    {
        // Remove Link
        $item_output = preg_replace( '#<a.*?>(.*)</a>#', '$1', $item_output );

        // Get nav type
        $nav_type = theme_get_bootstrap_nav_class( $args );
        
        // Add nav related 'text' class
        // TODO : Only wrap link content
        if ( $nav_type == 'navbar-nav' ) 
        {
            $item_output = sprintf( '<span class="navbar-text">%s</span>', $item_output );
        }

        else if ( $nav_type == 'nav' )
        {
            $item_output = sprintf( '<span class="nav-text">%s</span>', $item_output );
        }
    }

    return $item_output;
}

add_filter( 'walker_nav_menu_start_el', 'theme_menu_item_unlink', 10, 4 );

/**
 * Modal
 *
 * Makes item able to toggle a modal.
 *
 * Usage: Set menu item CSS class to `menu-item-modal`.
 *
 * Note: Works only with 'Custom Links'. You need to set
 * link to e.g. `#my-modal-id` to refer to the modal.
 */
function theme_menu_item_modal( $atts, $item, $args )
{
    if ( in_array( 'menu-item-modal', $item->classes ) ) 
    {
        $atts['data-toggle'] = 'modal';
    }

    return $atts;
}

add_filter( 'nav_menu_link_attributes', 'theme_menu_item_modal', 10, 3 );

/**
 * Template
 *
 * Replaces item content by use of filter.
 * 
 * Usage: Template can be set by using CSS class: `menu-item-template-{template_name}`.
 * 
 * Filter tag will be: `theme/render_nav_menu_template/template={template_name}`.
 * 
 * Example
 * 
 * `menu-item-template-search_form`.
 *    
 * // Set Search Form
 * add_filter( 'theme/render_nav_menu_template/template=search_form', function( $output, $item, $depth, $args )
 * {
 *      return get_search_form();
 * }, 10, 4 );
 */
function theme_menu_item_template( $item_output, $item, $depth, $args )
{
    foreach ( $item->classes as $class ) 
    {
        // Check class
        if ( preg_match( '/^menu-item-template-([a-z0-9_]+)$/', $class, $matches ) ) 
        {
            // Get template
            $template = $matches[1];

            // Filter
            $item_output = apply_filters( "theme/render_nav_menu_template/template=$template", $item_output, $item, $depth, $args );
        }
    }

    return $item_output;
}

add_filter( 'walker_nav_menu_start_el', 'theme_menu_item_template', 10, 4 );
