<?php
/**
 * Appointment Booking System
 * 
 * @package URVENA_Fix
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class URVENA_Appointments
 * Handles all appointment booking functionality
 */
class URVENA_Appointments {
	
	/**
	 * Initialize the appointment system
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_ajax_book_appointment', array( $this, 'handle_booking' ) );
		add_action( 'wp_ajax_nopriv_book_appointment', array( $this, 'handle_booking' ) );
		add_action( 'wp_ajax_get_available_times', array( $this, 'get_available_times' ) );
		add_action( 'wp_ajax_nopriv_get_available_times', array( $this, 'get_available_times' ) );
		
		// Admin hooks
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		
		// Database activation
		register_activation_hook( __FILE__, array( $this, 'create_tables' ) );
	}
	
	/**
	 * Initialize the system
	 */
	public function init() {
		$this->create_tables();
	}
	
	/**
	 * Create database tables for appointments
	 */
	public function create_tables() {
		global $wpdb;
		
		$charset_collate = $wpdb->get_charset_collate();
		
		// Appointments table with enhanced security fields
		$appointments_table = $wpdb->prefix . 'urvena_appointments';
		$sql_appointments = "CREATE TABLE $appointments_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			customer_name varchar(100) NOT NULL,
			customer_email varchar(100) NOT NULL,
			customer_phone varchar(20) NOT NULL,
			service_id int(11) NOT NULL,
			appointment_date date NOT NULL,
			appointment_time time NOT NULL,
			status varchar(20) DEFAULT 'pending',
			notes text,
			user_id int(11) DEFAULT NULL,
			booking_ip varchar(45) DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY appointment_date (appointment_date),
			KEY service_id (service_id),
			KEY status (status),
			KEY user_id (user_id),
			KEY booking_ip (booking_ip),
			FOREIGN KEY (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE SET NULL
		) $charset_collate;";
		
		// Time slots table
		$timeslots_table = $wpdb->prefix . 'urvena_timeslots';
		$sql_timeslots = "CREATE TABLE $timeslots_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			day_of_week tinyint(1) NOT NULL COMMENT '1=Monday, 7=Sunday',
			start_time time NOT NULL,
			end_time time NOT NULL,
			is_available tinyint(1) DEFAULT 1,
			max_appointments int(3) DEFAULT 1,
			PRIMARY KEY (id),
			KEY day_of_week (day_of_week)
		) $charset_collate;";
		
		// Blocked dates table
		$blocked_dates_table = $wpdb->prefix . 'urvena_blocked_dates';
		$sql_blocked = "CREATE TABLE $blocked_dates_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			blocked_date date NOT NULL,
			reason varchar(255),
			PRIMARY KEY (id),
			UNIQUE KEY blocked_date (blocked_date)
		) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_appointments );
		dbDelta( $sql_timeslots );
		dbDelta( $sql_blocked );
		
		// Insert default time slots if table is empty
		$this->insert_default_timeslots();
	}
	
	/**
	 * Insert default time slots
	 */
	private function insert_default_timeslots() {
		global $wpdb;
		
		$timeslots_table = $wpdb->prefix . 'urvena_timeslots';
		
		// Check if time slots already exist
		$existing = $wpdb->get_var( "SELECT COUNT(*) FROM $timeslots_table" );
		if ( $existing > 0 ) {
			return;
		}
		
		// Default working hours: Monday-Friday 8:00-17:00, Saturday 8:00-14:00
		$default_slots = array(
			// Monday to Friday
			array( 'day' => 1, 'start' => '08:00:00', 'end' => '09:00:00' ),
			array( 'day' => 1, 'start' => '09:00:00', 'end' => '10:00:00' ),
			array( 'day' => 1, 'start' => '10:00:00', 'end' => '11:00:00' ),
			array( 'day' => 1, 'start' => '11:00:00', 'end' => '12:00:00' ),
			array( 'day' => 1, 'start' => '13:00:00', 'end' => '14:00:00' ),
			array( 'day' => 1, 'start' => '14:00:00', 'end' => '15:00:00' ),
			array( 'day' => 1, 'start' => '15:00:00', 'end' => '16:00:00' ),
			array( 'day' => 1, 'start' => '16:00:00', 'end' => '17:00:00' ),
		);
		
		// Repeat for Monday through Friday (1-5)
		for ( $day = 1; $day <= 5; $day++ ) {
			foreach ( $default_slots as $slot ) {
				$wpdb->insert(
					$timeslots_table,
					array(
						'day_of_week' => $day,
						'start_time' => $slot['start'],
						'end_time' => $slot['end'],
						'is_available' => 1,
						'max_appointments' => 1
					)
				);
			}
		}
		
		// Saturday shorter hours
		$saturday_slots = array(
			array( 'start' => '08:00:00', 'end' => '09:00:00' ),
			array( 'start' => '09:00:00', 'end' => '10:00:00' ),
			array( 'start' => '10:00:00', 'end' => '11:00:00' ),
			array( 'start' => '11:00:00', 'end' => '12:00:00' ),
			array( 'start' => '13:00:00', 'end' => '14:00:00' ),
		);
		
		foreach ( $saturday_slots as $slot ) {
			$wpdb->insert(
				$timeslots_table,
				array(
					'day_of_week' => 6,
					'start_time' => $slot['start'],
					'end_time' => $slot['end'],
					'is_available' => 1,
					'max_appointments' => 1
				)
			);
		}
	}
	
	/**
	 * Handle appointment booking via AJAX with Ultimate Member Security
	 */
	public function handle_booking() {
		// Enhanced Security: Verify nonce
		if ( ! wp_verify_nonce( $_POST['nonce'], 'urvena_appointment_nonce' ) ) {
			wp_send_json_error( array( 
				'message' => 'Sicherheitsprüfung fehlgeschlagen. Bitte laden Sie die Seite neu.',
				'code' => 'nonce_failed'
			) );
		}
		
		// Ultimate Member Security: Check user login and approval status
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array(
				'message' => 'Sie müssen angemeldet sein, um einen Termin zu buchen.',
				'code' => 'login_required',
				'redirect' => function_exists( 'um_get_core_page' ) ? um_get_core_page( 'login' ) : wp_login_url()
			) );
		}
		
		// Additional Ultimate Member approval check
		if ( function_exists( 'um_is_user_approved' ) ) {
			$current_user_id = get_current_user_id();
			if ( ! um_is_user_approved( $current_user_id ) ) {
				wp_send_json_error( array(
					'message' => 'Ihr Benutzerkonto muss noch genehmigt werden, bevor Sie Termine buchen können.',
					'code' => 'approval_required'
				) );
			}
		}
		
		// Rate limiting: Check if user has booked too many appointments recently
		$user_id = get_current_user_id();
		$recent_bookings = $this->get_user_recent_bookings( $user_id, 24 ); // Last 24 hours
		if ( $recent_bookings >= 3 ) {
			wp_send_json_error( array(
				'message' => 'Sie haben bereits mehrere Termine in den letzten 24 Stunden gebucht. Bitte warten Sie oder kontaktieren Sie uns direkt.',
				'code' => 'rate_limit_exceeded'
			) );
		}
		
		global $wpdb;
		
		// Sanitize input data with enhanced validation
		$customer_name = sanitize_text_field( $_POST['customer_name'] );
		$customer_email = sanitize_email( $_POST['customer_email'] );
		$customer_phone = sanitize_text_field( $_POST['customer_phone'] );
		$service_id = intval( $_POST['service_id'] );
		$appointment_date = sanitize_text_field( $_POST['appointment_date'] );
		$appointment_time = sanitize_text_field( $_POST['appointment_time'] );
		$notes = sanitize_textarea_field( $_POST['notes'] );
		
		// Enhanced validation
		if ( empty( $customer_name ) || empty( $customer_email ) || empty( $customer_phone ) || 
		     empty( $service_id ) || empty( $appointment_date ) || empty( $appointment_time ) ) {
			wp_send_json_error( array(
				'message' => 'Alle Pflichtfelder müssen ausgefüllt werden.',
				'code' => 'missing_fields'
			) );
		}
		
		// Validate email format
		if ( ! is_email( $customer_email ) ) {
			wp_send_json_error( array(
				'message' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
				'code' => 'invalid_email'
			) );
		}
		
		// Validate phone number (German format)
		if ( ! preg_match( '/^(\+49|0)[1-9][0-9]{7,11}$/', str_replace( array( ' ', '-', '(', ')' ), '', $customer_phone ) ) ) {
			wp_send_json_error( array(
				'message' => 'Bitte geben Sie eine gültige deutsche Telefonnummer ein.',
				'code' => 'invalid_phone'
			) );
		}
		
		// Validate appointment date (not in the past, not too far in future)
		$appointment_timestamp = strtotime( $appointment_date );
		$today = strtotime( 'today' );
		$max_future = strtotime( '+3 months' );
		
		if ( $appointment_timestamp < $today ) {
			wp_send_json_error( array(
				'message' => 'Der Termin kann nicht in der Vergangenheit liegen.',
				'code' => 'invalid_date_past'
			) );
		}
		
		if ( $appointment_timestamp > $max_future ) {
			wp_send_json_error( array(
				'message' => 'Termine können nur bis zu 3 Monate im Voraus gebucht werden.',
				'code' => 'invalid_date_future'
			) );
		}
		
		// Check if time slot is available
		if ( ! $this->is_time_available( $appointment_date, $appointment_time ) ) {
			wp_send_json_error( array(
				'message' => 'Der gewählte Termin ist nicht verfügbar.',
				'code' => 'time_unavailable'
			) );
		}
		
		// Insert appointment with user association
		$appointments_table = $wpdb->prefix . 'urvena_appointments';
		$result = $wpdb->insert(
			$appointments_table,
			array(
				'customer_name' => $customer_name,
				'customer_email' => $customer_email,
				'customer_phone' => $customer_phone,
				'service_id' => $service_id,
				'appointment_date' => $appointment_date,
				'appointment_time' => $appointment_time,
				'status' => 'pending',
				'notes' => $notes,
				'user_id' => $user_id, // Associate with logged-in user
				'booking_ip' => $_SERVER['REMOTE_ADDR'], // Security: Track IP
				'created_at' => current_time( 'mysql' )
			),
			array( '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
		);
		
		if ( $result ) {
			$appointment_id = $wpdb->insert_id;
			
			// Send confirmation emails
			$this->send_confirmation_emails( $appointment_id );
			
			wp_send_json_success( array(
				'message' => 'Ihr Termin wurde erfolgreich gebucht! Sie erhalten eine Bestätigungsmail.',
				'appointment_id' => $appointment_id
			) );
		} else {
			wp_send_json_error( 'Fehler beim Speichern des Termins. Bitte versuchen Sie es erneut.' );
		}
	}
	
	/**
	 * Get available times for a specific date via AJAX
	 */
	public function get_available_times() {
		$date = sanitize_text_field( $_POST['date'] );
		
		if ( empty( $date ) ) {
			wp_send_json_error( 'Datum ist erforderlich.' );
		}
		
		$available_times = $this->get_available_times_for_date( $date );
		wp_send_json_success( $available_times );
	}
	
	/**
	 * Get available times for a specific date
	 */
	private function get_available_times_for_date( $date ) {
		global $wpdb;
		
		$day_of_week = date( 'N', strtotime( $date ) ); // 1 = Monday, 7 = Sunday
		
		// Get time slots for this day
		$timeslots_table = $wpdb->prefix . 'urvena_timeslots';
		$appointments_table = $wpdb->prefix . 'urvena_appointments';
		$blocked_dates_table = $wpdb->prefix . 'urvena_blocked_dates';
		
		// Check if date is blocked
		$blocked = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM $blocked_dates_table WHERE blocked_date = %s",
			$date
		) );
		
		if ( $blocked > 0 ) {
			return array();
		}
		
		// Get available time slots
		$slots = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM $timeslots_table WHERE day_of_week = %d AND is_available = 1 ORDER BY start_time",
			$day_of_week
		) );
		
		$available_times = array();
		
		foreach ( $slots as $slot ) {
			// Count existing appointments for this time slot
			$booked_count = $wpdb->get_var( $wpdb->prepare(
				"SELECT COUNT(*) FROM $appointments_table 
				WHERE appointment_date = %s 
				AND appointment_time = %s 
				AND status IN ('pending', 'confirmed')",
				$date,
				$slot->start_time
			) );
			
			// If less than max appointments, add to available times
			if ( $booked_count < $slot->max_appointments ) {
				$available_times[] = array(
					'time' => $slot->start_time,
					'display' => date( 'H:i', strtotime( $slot->start_time ) ),
					'available_spots' => $slot->max_appointments - $booked_count
				);
			}
		}
		
		return $available_times;
	}
	
	/**
	 * Check if a specific time is available
	 */
	private function is_time_available( $date, $time ) {
		global $wpdb;
		
		$appointments_table = $wpdb->prefix . 'urvena_appointments';
		$timeslots_table = $wpdb->prefix . 'urvena_timeslots';
		$blocked_dates_table = $wpdb->prefix . 'urvena_blocked_dates';
		
		// Check if date is blocked
		$blocked = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM $blocked_dates_table WHERE blocked_date = %s",
			$date
		) );
		
		if ( $blocked > 0 ) {
			return false;
		}
		
		$day_of_week = date( 'N', strtotime( $date ) );
		
		// Check if time slot exists and is available
		$slot = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM $timeslots_table 
			WHERE day_of_week = %d AND start_time = %s AND is_available = 1",
			$day_of_week,
			$time
		) );
		
		if ( ! $slot ) {
			return false;
		}
		
		// Count existing appointments
		$booked_count = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM $appointments_table 
			WHERE appointment_date = %s 
			AND appointment_time = %s 
			AND status IN ('pending', 'confirmed')",
			$date,
			$time
		) );
		
		return $booked_count < $slot->max_appointments;
	}
	
	/**
	 * Send confirmation emails
	 */
	private function send_confirmation_emails( $appointment_id ) {
		global $wpdb;
		
		$appointments_table = $wpdb->prefix . 'urvena_appointments';
		$appointment = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM $appointments_table WHERE id = %d",
			$appointment_id
		) );
		
		if ( ! $appointment ) {
			return;
		}
		
		// Get service details
		$service = get_post( $appointment->service_id );
		$service_name = $service ? $service->post_title : 'Service';
		
		// Format date and time
		$formatted_date = date( 'd.m.Y', strtotime( $appointment->appointment_date ) );
		$formatted_time = date( 'H:i', strtotime( $appointment->appointment_time ) );
		
		// Customer email
		$customer_subject = 'Terminbestätigung - URVENA FIX';
		$customer_message = "
		Hallo {$appointment->customer_name},
		
		vielen Dank für Ihre Terminbuchung bei URVENA FIX!
		
		Ihre Termindetails:
		- Service: {$service_name}
		- Datum: {$formatted_date}
		- Uhrzeit: {$formatted_time}
		- Telefon: {$appointment->customer_phone}
		
		Wir freuen uns auf Ihren Besuch!
		
		Mit freundlichen Grüßen
		Ihr URVENA FIX Team
		";
		
		wp_mail( $appointment->customer_email, $customer_subject, $customer_message );
		
		// Admin notification
		$admin_email = get_option( 'admin_email' );
		$admin_subject = 'Neue Terminbuchung - URVENA FIX';
		$admin_message = "
		Neue Terminbuchung eingegangen:
		
		Kunde: {$appointment->customer_name}
		E-Mail: {$appointment->customer_email}
		Telefon: {$appointment->customer_phone}
		Service: {$service_name}
		Datum: {$formatted_date}
		Uhrzeit: {$formatted_time}
		Notizen: {$appointment->notes}
		
		Termin-ID: {$appointment_id}
		";
		
		wp_mail( $admin_email, $admin_subject, $admin_message );
	}
	
	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		add_menu_page(
			'Termine',
			'Termine',
			'manage_options',
			'urvena-appointments',
			array( $this, 'admin_page' ),
			'dashicons-calendar-alt',
			30
		);
		
		add_submenu_page(
			'urvena-appointments',
			'Alle Termine',
			'Alle Termine',
			'manage_options',
			'urvena-appointments',
			array( $this, 'admin_page' )
		);
		
		add_submenu_page(
			'urvena-appointments',
			'Kalender',
			'Kalender',
			'manage_options',
			'urvena-appointments-calendar',
			array( $this, 'calendar_page' )
		);
		
		add_submenu_page(
			'urvena-appointments',
			'Öffnungszeiten',
			'Öffnungszeiten',
			'manage_options',
			'urvena-timeslots',
			array( $this, 'timeslots_page' )
		);
	}
	
	/**
	 * Admin scripts
	 */
	public function admin_scripts( $hook ) {
		if ( strpos( $hook, 'urvena-appointments' ) !== false ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-ui-datepicker-style', 'https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css' );
		}
	}
	
	/**
	 * Admin page for appointments
	 */
	public function admin_page() {
		global $wpdb;
		
		// Handle status updates
		if ( isset( $_POST['update_status'] ) && isset( $_POST['appointment_id'] ) && isset( $_POST['status'] ) ) {
			$appointment_id = intval( $_POST['appointment_id'] );
			$status = sanitize_text_field( $_POST['status'] );
			
			$appointments_table = $wpdb->prefix . 'urvena_appointments';
			$wpdb->update(
				$appointments_table,
				array( 'status' => $status ),
				array( 'id' => $appointment_id ),
				array( '%s' ),
				array( '%d' )
			);
			
			echo '<div class="notice notice-success"><p>Status aktualisiert!</p></div>';
		}
		
		// Get appointments
		$appointments_table = $wpdb->prefix . 'urvena_appointments';
		$appointments = $wpdb->get_results(
			"SELECT a.*, p.post_title as service_name 
			FROM $appointments_table a 
			LEFT JOIN {$wpdb->posts} p ON a.service_id = p.ID 
			ORDER BY a.appointment_date DESC, a.appointment_time ASC 
			LIMIT 50"
		);
		
		?>
		<div class="wrap">
			<h1>Termine verwalten</h1>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Kunde</th>
						<th>Kontakt</th>
						<th>Service</th>
						<th>Datum & Zeit</th>
						<th>Status</th>
						<th>Aktionen</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $appointments as $appointment ) : ?>
					<tr>
						<td><?php echo esc_html( $appointment->id ); ?></td>
						<td><?php echo esc_html( $appointment->customer_name ); ?></td>
						<td>
							<strong>E-Mail:</strong> <?php echo esc_html( $appointment->customer_email ); ?><br>
							<strong>Telefon:</strong> <?php echo esc_html( $appointment->customer_phone ); ?>
						</td>
						<td><?php echo esc_html( $appointment->service_name ); ?></td>
						<td>
							<?php echo date( 'd.m.Y', strtotime( $appointment->appointment_date ) ); ?><br>
							<?php echo date( 'H:i', strtotime( $appointment->appointment_time ) ); ?> Uhr
						</td>
						<td>
							<span class="status-<?php echo esc_attr( $appointment->status ); ?>">
								<?php echo esc_html( ucfirst( $appointment->status ) ); ?>
							</span>
						</td>
						<td>
							<form method="post" style="display: inline;">
								<input type="hidden" name="appointment_id" value="<?php echo esc_attr( $appointment->id ); ?>">
								<select name="status" onchange="this.form.submit()">
									<option value="pending" <?php selected( $appointment->status, 'pending' ); ?>>Wartend</option>
									<option value="confirmed" <?php selected( $appointment->status, 'confirmed' ); ?>>Bestätigt</option>
									<option value="completed" <?php selected( $appointment->status, 'completed' ); ?>>Abgeschlossen</option>
									<option value="cancelled" <?php selected( $appointment->status, 'cancelled' ); ?>>Storniert</option>
								</select>
								<input type="hidden" name="update_status" value="1">
								<?php wp_nonce_field( 'update_appointment_status', 'status_nonce' ); ?>
							</form>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<style>
		.status-pending { color: #856404; background: #fff3cd; padding: 2px 8px; border-radius: 3px; }
		.status-confirmed { color: #155724; background: #d4edda; padding: 2px 8px; border-radius: 3px; }
		.status-completed { color: #0c5460; background: #d1ecf1; padding: 2px 8px; border-radius: 3px; }
		.status-cancelled { color: #721c24; background: #f8d7da; padding: 2px 8px; border-radius: 3px; }
		</style>
		<?php
	}
	
	/**
	 * Calendar page
	 */
	public function calendar_page() {
		?>
		<div class="wrap">
			<h1>Terminkalender</h1>
			<div id="appointment-calendar">
				<p>Kalenderansicht wird hier implementiert...</p>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Time slots management page
	 */
	public function timeslots_page() {
		?>
		<div class="wrap">
			<h1>Öffnungszeiten verwalten</h1>
			<p>Hier können Sie die verfügbaren Terminzeiten verwalten.</p>
		</div>
		<?php
	}
	
	/**
	 * Security: Get user's recent bookings count for rate limiting
	 */
	public function get_user_recent_bookings( $user_id, $hours = 24 ) {
		global $wpdb;
		
		$appointments_table = $wpdb->prefix . 'urvena_appointments';
		$since = date( 'Y-m-d H:i:s', strtotime( "-{$hours} hours" ) );
		
		$count = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$appointments_table} 
			WHERE user_id = %d 
			AND created_at >= %s",
			$user_id,
			$since
		) );
		
		return intval( $count );
	}
	
	/**
	 * Security: Get user's appointments with permission check
	 */
	public function get_user_appointments( $user_id = null ) {
		global $wpdb;
		
		// If no user_id provided, use current user
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		
		// Security: Users can only see their own appointments unless admin
		if ( ! current_user_can( 'manage_options' ) && $user_id !== get_current_user_id() ) {
			return array();
		}
		
		$appointments_table = $wpdb->prefix . 'urvena_appointments';
		$services_table = $wpdb->prefix . 'urvena_services';
		
		$appointments = $wpdb->get_results( $wpdb->prepare(
			"SELECT a.*, s.service_name, s.price, s.duration 
			FROM {$appointments_table} a 
			LEFT JOIN {$services_table} s ON a.service_id = s.id 
			WHERE a.user_id = %d 
			ORDER BY a.appointment_date DESC, a.appointment_time DESC",
			$user_id
		) );
		
		return $appointments;
	}
	
	/**
	 * Enhanced user permission check for Ultimate Member with role-based access
	 */
	public function check_user_permissions( $action = 'view' ) {
		// Must be logged in
		if ( ! is_user_logged_in() ) {
			return false;
		}
		
		$user_id = get_current_user_id();
		$user = wp_get_current_user();
		
		// Ultimate Member approval check
		if ( function_exists( 'um_is_user_approved' ) ) {
			if ( ! um_is_user_approved( $user_id ) ) {
				return false;
			}
		}
		
		// Get user's primary role
		$user_role = $this->get_user_primary_role( $user );
		
		// Role-based permissions with Ultimate Member integration
		switch ( $action ) {
			case 'admin':
			case 'manage_all':
				return in_array( $user_role, array( 'administrator', 'um_admin' ) );
				
			case 'seller_access':
				return in_array( $user_role, array( 'administrator', 'um_admin', 'um_seller' ) );
				
			case 'view_calendar':
				return in_array( $user_role, array( 'administrator', 'um_admin', 'um_seller', 'editor' ) );
				
			case 'modify_appointments':
				return in_array( $user_role, array( 'administrator', 'um_admin', 'um_seller' ) );
				
			case 'book':
			case 'view':
			case 'edit_own':
			default:
				$allowed_roles = array( 'administrator', 'um_admin', 'um_seller', 'um_customer', 'editor', 'subscriber' );
				return in_array( $user_role, $allowed_roles );
		}
	}
	
	/**
	 * Get user's primary Ultimate Member role (same as security redirects)
	 */
	private function get_user_primary_role( $user ) {
		$roles = $user->roles;
		
		// Priority order for role detection
		$role_priority = array(
			'administrator' => 5,
			'um_admin' => 4,
			'um_seller' => 3,
			'editor' => 2,
			'um_customer' => 1,
			'subscriber' => 0
		);
		
		$highest_priority = -1;
		$primary_role = 'subscriber';
		
		foreach ( $roles as $role ) {
			$priority = isset( $role_priority[$role] ) ? $role_priority[$role] : 0;
			if ( $priority > $highest_priority ) {
				$highest_priority = $priority;
				$primary_role = $role;
			}
		}
		
		return $primary_role;
	}
	
	/**
	 * Security: Log security events
	 */
	public function log_security_event( $event_type, $message, $user_id = null, $data = array() ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		
		$log_data = array(
			'timestamp' => current_time( 'mysql' ),
			'event_type' => $event_type,
			'message' => $message,
			'user_id' => $user_id,
			'ip_address' => $_SERVER['REMOTE_ADDR'],
			'user_agent' => $_SERVER['HTTP_USER_AGENT'],
			'additional_data' => wp_json_encode( $data )
		);
		
		// Log to WordPress error log
		error_log( 'URVENA Security Event: ' . wp_json_encode( $log_data ) );
		
		// Optionally store in database or send alerts for critical events
		if ( in_array( $event_type, array( 'unauthorized_access', 'rate_limit_exceeded', 'suspicious_activity' ) ) ) {
			// Send email alert to admin
			$admin_email = get_option( 'admin_email' );
			$subject = 'URVENA FIX - Sicherheitswarnung';
			$message = sprintf( 
				"Sicherheitsereignis erkannt:\n\nTyp: %s\nNachricht: %s\nBenutzer ID: %s\nIP-Adresse: %s\nZeitpunkt: %s",
				$event_type,
				$message,
				$user_id,
				$_SERVER['REMOTE_ADDR'],
				current_time( 'mysql' )
			);
			
			wp_mail( $admin_email, $subject, $message );
		}
	}
}

// Initialize the appointment system
new URVENA_Appointments();