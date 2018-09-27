<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.
/**
 * Customizer
 */

/**
 * Customizer Init
 */
function theme_customizer_init( $wp_customize ) 
{
	// Remove 'Additional CSS' section.
	$wp_customize->remove_section( 'custom_css' );

	/**
	 * Site Logos
	 */

	// Dark

	$wp_customize->add_setting( 'site_logo_dark', array
	(
		'sanitize_callback' => 'esc_url',
	));

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'site_logo_dark', array
	(
		'label'       => __( 'Site Logo (dark)', 'theme' ),
		'description' => __( 'Used on light backgrounds.', 'theme' ),
		'section'     => 'title_tagline',
	)));

	// Dark (compact)

	$wp_customize->add_setting( 'site_logo_dark_small', array
	(
		'sanitize_callback' => 'esc_url',
	));

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'site_logo_dark_small', array
	(
		'label'       => __( 'Site Logo (dark, compact)', 'theme' ),
		'description' => __( 'Compact version for use on light backgrounds. (optional)', 'theme' ),
		'section'     => 'title_tagline',
	)));

	// Light

	$wp_customize->add_setting( 'site_logo_light', array
	(
		'sanitize_callback' => 'esc_url',
	));

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'site_logo_light', array
	(
		'label'       => __( 'Site Logo (light)', 'theme' ),
		'description' => __( 'Used on dark backgrounds.', 'theme' ),
		'section'     => 'title_tagline',
	)));

	// Light (compact)

	$wp_customize->add_setting( 'site_logo_light_small', array
	(
		'sanitize_callback' => 'esc_url',
	));

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'site_logo_light_small', array
	(
		'label'       => __( 'Site Logo (light, compact)', 'theme' ),
		'description' => __( 'Compact version for use on dark backgrounds. (optional)', 'theme' ),
		'section'     => 'title_tagline',
	)));
}

add_filter( 'customize_register', 'theme_customizer_init' );
