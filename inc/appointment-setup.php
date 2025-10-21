<?php
/**
 * Appointment System Setup Script
 * Run this once to create necessary pages and setup the appointment system
 * 
 * @package URVENA_Fix
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Setup appointment system pages and data
 */
function urvena_setup_appointment_system() {
	// Create Terminbuchung page
	$booking_page = wp_insert_post( array(
		'post_title'     => 'Terminbuchung',
		'post_content'   => 'Diese Seite verwendet das Template für die Terminbuchung.',
		'post_status'    => 'publish',
		'post_type'      => 'page',
		'post_name'      => 'terminbuchung',
		'page_template'  => 'page-terminbuchung.php'
	) );
	
	// Create Termine Dashboard page
	$dashboard_page = wp_insert_post( array(
		'post_title'     => 'Termine Dashboard',
		'post_content'   => 'Hier können Sie Ihre Termine verwalten und den Kalender einsehen.',
		'post_status'    => 'publish',
		'post_type'      => 'page',
		'post_name'      => 'termine',
		'page_template'  => 'page-termine.php'
	) );
	
	// Create Datenschutz page if it doesn't exist
	$privacy_page = get_page_by_path( 'datenschutz' );
	if ( ! $privacy_page ) {
		wp_insert_post( array(
			'post_title'   => 'Datenschutzerklärung',
			'post_content' => 'Hier finden Sie unsere Datenschutzerklärung.',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_name'    => 'datenschutz'
		) );
	}
	
	// Set up menu items if menu exists
	$menu_name = 'Hauptmenü';
	$menu = wp_get_nav_menu_object( $menu_name );
	
	if ( $menu ) {
		// Add Terminbuchung to menu
		wp_update_nav_menu_item( $menu->term_id, 0, array(
			'menu-item-title'     => 'Termin buchen',
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $booking_page,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish'
		) );
	}
	
	// Initialize database tables
	if ( class_exists( 'URVENA_Appointments' ) ) {
		$appointments = new URVENA_Appointments();
		$appointments->create_tables();
	}
	
	// Add option to track setup completion
	update_option( 'urvena_appointment_system_setup', true );
	
	return array(
		'booking_page_id'   => $booking_page,
		'dashboard_page_id' => $dashboard_page,
		'message'           => 'Appointment system setup completed successfully!'
	);
}

// Only run setup if not already completed
if ( ! get_option( 'urvena_appointment_system_setup' ) && is_admin() ) {
	add_action( 'admin_init', function() {
		if ( isset( $_GET['setup_appointments'] ) && $_GET['setup_appointments'] === '1' ) {
			$result = urvena_setup_appointment_system();
			wp_redirect( admin_url( 'admin.php?page=urvena-appointments&setup=success' ) );
			exit;
		}
	} );
	
	// Add admin notice to run setup
	add_action( 'admin_notices', function() {
		?>
		<div class="notice notice-info">
			<p>
				<strong>URVENA FIX Terminbuchung:</strong> 
				Das Terminbuchungssystem ist bereit zur Einrichtung. 
				<a href="<?php echo admin_url( '?setup_appointments=1' ); ?>" class="button button-primary">
					Jetzt einrichten
				</a>
			</p>
		</div>
		<?php
	} );
}