<?php
/**
 * Schema Markup for Local Business
 *
 * @package URVENA_Fix
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output Local Business Schema Markup
 */
function urvena_output_schema_markup() {
	$phone   = get_theme_mod( 'urvena_phone', '+49 6151 123456' );
	$email   = get_theme_mod( 'urvena_email', 'info@urvenafix.de' );
	$address = get_theme_mod( 'urvena_address', 'Mainzer Str. 70, 64293 Darmstadt' );

	// Parse address (simple extraction).
	$address_parts = explode( ',', $address );
	$street        = isset( $address_parts[0] ) ? trim( $address_parts[0] ) : '';
	$city_postal   = isset( $address_parts[1] ) ? trim( $address_parts[1] ) : '';

	// Try to extract postal code and city.
	preg_match( '/(\d{5})\s+(.+)/', $city_postal, $matches );
	$postal_code = isset( $matches[1] ) ? $matches[1] : '64283';
	$city        = isset( $matches[2] ) ? $matches[2] : 'Darmstadt';

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'AutoRepair',
		'name'        => get_bloginfo( 'name' ),
		'description' => get_bloginfo( 'description' ),
		'url'         => home_url( '/' ),
		'telephone'   => $phone,
		'email'       => $email,
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $street,
			'addressLocality' => $city,
			'postalCode'      => $postal_code,
			'addressCountry'  => 'DE',
		),
		'priceRange'  => '€€',
		'openingHoursSpecification' => array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
				'opens'     => '08:00',
				'closes'    => '18:00',
			),
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => 'Saturday',
				'opens'     => '09:00',
				'closes'    => '14:00',
			),
		),
	);

	// Add logo if available.
	if ( has_custom_logo() ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo_url       = wp_get_attachment_image_url( $custom_logo_id, 'full' );
		if ( $logo_url ) {
			$schema['logo'] = $logo_url;
			$schema['image'] = $logo_url;
		}
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}

