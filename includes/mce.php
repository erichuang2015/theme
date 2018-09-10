<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

/**
 * Editor Style
 */
function theme_mce_editor_style()
{
	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'assets/css/editor-style.min.css' ) );
}

add_action( 'init', 'theme_mce_editor_style' );

/**
 * MCE Before Init
 * 
 * @link https://www.tiny.cloud/docs-3x/reference/configuration/Configuration3x@style_formats/
 */
function theme_tiny_mce_before_init( $init_array ) 
{  
	$colors = apply_filters( 'theme/mce_colors', array
    (
		'primary'   => __( 'Primary', 'theme' ),
		'secondary' => __( 'Secondary', 'theme' ),
		//'success'   => __( 'Success', 'theme' ),
		//'danger'    => __( 'Danger', 'theme' ),
		//'warning'   => __( 'Warning', 'theme' ),
		//'info'      => __( 'Info', 'theme' ),
		'light'     => __( 'Light', 'theme' ),
		'dark'      => __( 'Dark', 'theme' ),
		'body'      => __( 'Body', 'theme' ),
		'muted'     => __( 'Muted', 'theme' ),
		'white'     => __( 'White', 'theme' ),
		'black'     => __( 'Black', 'theme' ),
		'white'     => __( 'White', 'theme' )
    ));

    /**
     * Style Formats
     */

    $color_items = array();

    foreach ( $colors as $type => $title ) 
    {
    	$color_items[ $type ] = array
        (  
			'title'   => $title,  
			'inline'  => 'span',  
			'classes' => "text-$type",
			'wrapper' => true,
        );
    }

    $style_formats = (array) apply_filters( 'theme/mce_style_formats', array
    (  
    	// Size
    	'size' => array
    	(
    		'title' => __( 'Size' ),
			'items' => array
			(
				// Lead
				'lead' => array
		        (  
					'title'   => __( 'Large' ),  
					'inline'  => 'span',  
					'classes' => 'lead',
					'wrapper' => true,
		        ),

		        // Small
		        'small' => array
		        (  
					'title'   => __( 'Small' ),  
					'inline'  => 'small',  
					'classes' => '',
					'wrapper' => true,
		        ),
			),
    	),

    	// Weight
    	'weight' => array
    	(
    		'title' => __( 'Weight' ),
			'items' => array
			(
				// Light
		        'light' => array
		        (  
					'title'   => __( 'Light' ),  
					'inline'  => 'span',  
					'classes' => 'font-weight-light',
					'wrapper' => true,
		        ),

		        // Normal
		        'normal' => array
		        (  
					'title'   => __( 'Normal' ),  
					'inline'  => 'span',  
					'classes' => 'font-weight-normal',
					'wrapper' => true,
		        ),

		        // Bold
		        'bold' => array
		        (  
					'title'   => __( 'Bold' ),  
					'inline'  => 'span',  
					'classes' => 'font-weight-bold',
					'wrapper' => true,
		        ),
			),
    	),

    	// Font Family
    	'font_family' => array
    	(
    		'title' => __( 'Font Family' ),
			'items' => array
			(
				// Serif
		        'serif' => array
		        (  
					'title'   => __( 'Serif' ),  
					'inline'  => 'span',  
					'classes' => 'font-family-serif',
					'wrapper' => true,
		        ),

		        // Sans Serif
		        'sans-serif' => array
		        (  
					'title'   => __( 'Sans Serif' ),  
					'inline'  => 'span',  
					'classes' => 'font-family-sans-serif',
					'wrapper' => true,
		        ),
			),
    	),

        // Colors
        'colors' => array
        (
			'title' => __( 'Colors' ),
			'items' => $color_items,
		),
    ));

    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );

    //
     
    return $init_array;  
}

add_filter( 'tiny_mce_before_init', 'theme_tiny_mce_before_init' );

/**
 * Add Buttons
 */
function theme_mce_buttons( $buttons ) 
{
	// Add buttons
	$buttons[] = 'button';
	$buttons[] = 'styleselect'; // needed for style formats.

    return $buttons;
}

add_filter( 'mce_buttons_2', 'theme_mce_buttons' );

/**
 * Remove Buttons
 */
function theme_mce_remove_buttons( $buttons ) 
{
	// Buttons to remove
	$remove = array( 'forecolor' );

	// Remove
	foreach ( $remove as $button ) 
	{
		$index = array_search( $button, $buttons );

		if ( $index !== false ) 
		{
			array_splice( $buttons, $index, 1 );
		}
	}

    return $buttons;
}

add_filter( 'mce_buttons'  , 'theme_mce_remove_buttons', 999 );
add_filter( 'mce_buttons_2', 'theme_mce_remove_buttons', 999 );

/**
 * Plugins
 */
function theme_mce_external_plugins( $plugins ) 
{
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

    $plugins['theme'] = get_theme_file_uri( "assets/js/mce-plugins$min.js" );
    
    return $plugins;
}

add_filter( 'mce_external_plugins', 'theme_mce_external_plugins' );
