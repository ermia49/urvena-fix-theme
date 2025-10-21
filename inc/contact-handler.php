<?php
/**
 * Custom Contact Form Handler
 *
 * @package URVENA_Fix
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle contact form submission
 */
function urvena_handle_contact_form() {
	// Verify nonce.
	if ( ! isset( $_POST['urvena_contact_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( $_POST['urvena_contact_nonce'] ), 'urvena_contact_form' ) ) {
		wp_die( esc_html__( 'Sicherheitsüberprüfung fehlgeschlagen. Bitte versuchen Sie es erneut.', 'urvena-fix' ) );
	}

	// Sanitize and validate inputs.
	$name    = isset( $_POST['contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
	$email   = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
	$phone   = isset( $_POST['contact_phone'] ) ? urvena_sanitize_phone( wp_unslash( $_POST['contact_phone'] ) ) : '';
	$subject = isset( $_POST['contact_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_subject'] ) ) : '';
	$message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';

	// Validate required fields.
	$errors = array();

	if ( empty( $name ) ) {
		$errors[] = __( 'Name ist erforderlich.', 'urvena-fix' );
	}

	if ( empty( $email ) || ! is_email( $email ) ) {
		$errors[] = __( 'Gültige E-Mail-Adresse ist erforderlich.', 'urvena-fix' );
	}

	if ( empty( $message ) ) {
		$errors[] = __( 'Nachricht ist erforderlich.', 'urvena-fix' );
	}

	// If errors, redirect back with error message.
	if ( ! empty( $errors ) ) {
		$error_message = implode( ' ', $errors );
		wp_safe_redirect( add_query_arg( 'contact_error', urlencode( $error_message ), wp_get_referer() ) );
		exit;
	}

	// Prepare email.
	$to           = get_theme_mod( 'urvena_email', get_option( 'admin_email' ) );
	$email_subject = sprintf(
		/* translators: %s: contact form subject */
		__( 'Neue Kontaktanfrage: %s', 'urvena-fix' ),
		$subject ? $subject : __( 'Allgemeine Anfrage', 'urvena-fix' )
	);

	$email_body = sprintf(
		__( 'Sie haben eine neue Kontaktanfrage über Ihre Website erhalten:%1$s%1$sName: %2$s%1$sE-Mail: %3$s%1$sTelefon: %4$s%1$sBetreff: %5$s%1$s%1$sNachricht:%1$s%6$s', 'urvena-fix' ),
		"\r\n",
		$name,
		$email,
		$phone ? $phone : __( 'Nicht angegeben', 'urvena-fix' ),
		$subject ? $subject : __( 'Allgemeine Anfrage', 'urvena-fix' ),
		$message
	);

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . $to . '>',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	// Send email.
	$sent = wp_mail( $to, $email_subject, $email_body, $headers );

	// Redirect with success or error message.
	if ( $sent ) {
		wp_safe_redirect( add_query_arg( 'contact_success', '1', wp_get_referer() ) );
	} else {
		wp_safe_redirect( add_query_arg( 'contact_error', urlencode( __( 'Beim Senden der Nachricht ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.', 'urvena-fix' ) ), wp_get_referer() ) );
	}
	exit;
}
add_action( 'admin_post_nopriv_urvena_contact', 'urvena_handle_contact_form' );
add_action( 'admin_post_urvena_contact', 'urvena_handle_contact_form' );

