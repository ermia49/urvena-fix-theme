<?php
/**
 * Theme Customizer Settings
 *
 * @package URVENA_Fix
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function urvena_customize_register( $wp_customize ) {

	// Add URVENA FIX Settings Section.
	$wp_customize->add_section(
		'urvena_contact_settings',
		array(
			'title'    => __( 'Kontaktinformationen', 'urvena-fix' ),
			'priority' => 30,
		)
	);

	// Phone Number.
	$wp_customize->add_setting(
		'urvena_phone',
		array(
			'default'           => '+49 6151 123456',
			'sanitize_callback' => 'urvena_sanitize_phone',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'urvena_phone',
		array(
			'label'       => __( 'Telefonnummer', 'urvena-fix' ),
			'description' => __( 'Wird im Header und Footer angezeigt.', 'urvena-fix' ),
			'section'     => 'urvena_contact_settings',
			'type'        => 'text',
		)
	);

	// Email Address.
	$wp_customize->add_setting(
		'urvena_email',
		array(
			'default'           => 'info@urvenafix.de',
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'urvena_email',
		array(
			'label'       => __( 'E-Mail-Adresse', 'urvena-fix' ),
			'description' => __( 'Hauptkontakt-E-Mail.', 'urvena-fix' ),
			'section'     => 'urvena_contact_settings',
			'type'        => 'email',
		)
	);

	// Address.
	$wp_customize->add_setting(
		'urvena_address',
		array(
			'default'           => 'Mainzer Str. 70, 64293 Darmstadt',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'urvena_address',
		array(
			'label'       => __( 'Adresse', 'urvena-fix' ),
			'description' => __( 'Geschäftsadresse.', 'urvena-fix' ),
			'section'     => 'urvena_contact_settings',
			'type'        => 'textarea',
		)
	);

	// Google Maps Link.
	$wp_customize->add_setting(
		'urvena_maps_link',
		array(
			'default'           => 'https://www.google.com/maps/place/Mainzer+Str.+70,+64293+Darmstadt',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'urvena_maps_link',
		array(
			'label'       => __( 'Google Maps Link', 'urvena-fix' ),
			'description' => __( 'Link zu Ihrem Standort in Google Maps.', 'urvena-fix' ),
			'section'     => 'urvena_contact_settings',
			'type'        => 'url',
		)
	);

	// Opening Hours Section.
	$wp_customize->add_section(
		'urvena_hours_settings',
		array(
			'title'    => __( 'Öffnungszeiten', 'urvena-fix' ),
			'priority' => 31,
		)
	);

	// Weekdays Hours.
	$wp_customize->add_setting(
		'urvena_hours_weekdays',
		array(
			'default'           => 'Mo - Fr: 08:00 - 18:00 Uhr',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'urvena_hours_weekdays',
		array(
			'label'   => __( 'Wochentags', 'urvena-fix' ),
			'section' => 'urvena_hours_settings',
			'type'    => 'text',
		)
	);

	// Saturday Hours.
	$wp_customize->add_setting(
		'urvena_hours_saturday',
		array(
			'default'           => 'Sa: 09:00 - 14:00 Uhr',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'urvena_hours_saturday',
		array(
			'label'   => __( 'Samstag', 'urvena-fix' ),
			'section' => 'urvena_hours_settings',
			'type'    => 'text',
		)
	);

	// Sunday Hours.
	$wp_customize->add_setting(
		'urvena_hours_sunday',
		array(
			'default'           => 'So: Geschlossen',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'urvena_hours_sunday',
		array(
			'label'   => __( 'Sonntag', 'urvena-fix' ),
			'section' => 'urvena_hours_settings',
			'type'    => 'text',
		)
	);

	// Social Media Section.
	$wp_customize->add_section(
		'urvena_social_settings',
		array(
			'title'    => __( 'Social Media', 'urvena-fix' ),
			'priority' => 32,
		)
	);

	// Facebook URL.
	$wp_customize->add_setting(
		'urvena_facebook',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'urvena_facebook',
		array(
			'label'   => __( 'Facebook URL', 'urvena-fix' ),
			'section' => 'urvena_social_settings',
			'type'    => 'url',
		)
	);

	// Instagram URL.
	$wp_customize->add_setting(
		'urvena_instagram',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'urvena_instagram',
		array(
			'label'   => __( 'Instagram URL', 'urvena-fix' ),
			'section' => 'urvena_social_settings',
			'type'    => 'url',
		)
	);
}
add_action( 'customize_register', 'urvena_customize_register' );

