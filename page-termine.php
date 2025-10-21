<?php
/**
 * Template Name: Terminbuchung Dashboard
 * Secure Dashboard - Requires User Login and Ultimate Member Approval
 *
 * @package URVENA_Fix
 * @since 1.1.0
 */

// Security Check: Require user login
if ( ! is_user_logged_in() ) {
	// Redirect to login page with return URL
	$login_url = function_exists( 'um_get_core_page' ) ? um_get_core_page( 'login' ) : wp_login_url();
	$redirect_url = add_query_arg( 'redirect_to', urlencode( get_permalink() ), $login_url );
	wp_redirect( $redirect_url );
	exit;
}

// Additional Ultimate Member Security Check
if ( function_exists( 'um_user' ) && function_exists( 'um_is_user_approved' ) ) {
	$current_user_id = get_current_user_id();
	
	// Check if user is approved
	if ( ! um_is_user_approved( $current_user_id ) ) {
		wp_die( 
			'<h1>Zugriff verweigert</h1>
			<p>Ihr Benutzerkonto muss noch genehmigt werden, bevor Sie auf das Dashboard zugreifen können.</p>
			<p>Bitte wenden Sie sich an unseren Support oder warten Sie auf die Freischaltung.</p>
			<p><a href="' . esc_url( home_url() ) . '">Zurück zur Startseite</a></p>',
			'Zugriff verweigert - URVENA FIX',
			array( 
				'response' => 403,
				'back_link' => true
			)
		);
	}
	
	// Additional role check - ensure user has appropriate capabilities
	$user = wp_get_current_user();
	$allowed_roles = array( 'administrator', 'editor', 'subscriber', 'um_customer' );
	$user_roles = $user->roles;
	
	if ( ! array_intersect( $allowed_roles, $user_roles ) ) {
		wp_die( 
			'<h1>Unzureichende Berechtigung</h1>
			<p>Sie haben nicht die erforderlichen Berechtigungen für den Zugriff auf das Dashboard.</p>
			<p><a href="' . esc_url( home_url() ) . '">Zurück zur Startseite</a></p>',
			'Zugriff verweigert - URVENA FIX',
			array( 
				'response' => 403,
				'back_link' => true
			)
		);
	}
}

// Security: Add nonce for CSRF protection
$dashboard_nonce = wp_create_nonce( 'urvena_dashboard_access' );

get_header(); 

// Get current user for role checking
$user = wp_get_current_user();

// Safety check: ensure user is valid and has roles
if ( ! $user || ! isset( $user->roles ) || ! is_array( $user->roles ) ) {
	wp_die( 
		'<h1>Benutzerfehler</h1>
		<p>Es gab ein Problem beim Laden Ihrer Benutzerdaten. Bitte melden Sie sich erneut an.</p>
		<p><a href="' . wp_login_url( get_permalink() ) . '">Zur Anmeldung</a></p>',
		'Benutzerfehler - URVENA FIX',
		array( 'response' => 500 )
	);
}

// Determine primary role for data attributes
$primary_role = '';
$user_role = '';
$dashboard_title = 'Termine - Dashboard';
$role_badge = '';

// Determine user role and customize interface with safety checks
if ( in_array( 'administrator', $user->roles ) ) {
	$primary_role = 'administrator';
	$user_role = 'Administrator';
	$dashboard_title = 'Admin Dashboard - URVENA FIX';
	$role_badge = '<span class="role-badge admin">👑 Administrator</span>';
} elseif ( in_array( 'um_admin', $user->roles ) ) {
	$primary_role = 'um_admin';
	$user_role = 'Ultimate Member Admin';
	$dashboard_title = 'Admin Dashboard - URVENA FIX';
	$role_badge = '<span class="role-badge admin">🔧 Admin</span>';
} elseif ( in_array( 'um_seller', $user->roles ) ) {
	$primary_role = 'um_seller';
	$user_role = 'Verkäufer';
	$dashboard_title = 'Verkäufer Dashboard - URVENA FIX';
	$role_badge = '<span class="role-badge seller">� Verkäufer</span>';
} elseif ( in_array( 'um_customer', $user->roles ) ) {
	$primary_role = 'um_customer';
	$user_role = 'Kunde';
	$dashboard_title = 'Kunden Dashboard - URVENA FIX';
	$role_badge = '<span class="role-badge customer">� Kunde</span>';
} else {
	$primary_role = 'user';
	$user_role = 'Benutzer';
	$role_badge = '<span class="role-badge user">📋 Benutzer</span>';
}
?>

<div class="termine-dashboard" 
     data-nonce="<?php echo esc_attr( $dashboard_nonce ); ?>"
     data-user-role="<?php echo esc_attr( $primary_role ); ?>"
     data-role-level="<?php echo esc_attr( ucfirst( $user_role ) ); ?>">
	<div class="container">
		<div class="dashboard-header">
			<div class="user-welcome">
				<?php 
				// Debug information for administrators (only visible to admins)
				if ( current_user_can( 'administrator' ) && isset( $_GET['debug'] ) ) {
					echo '<div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 1rem; margin-bottom: 1rem; border-radius: 6px;">';
					echo '<h4>Debug Information (Admin Only):</h4>';
					echo '<p><strong>User ID:</strong> ' . get_current_user_id() . '</p>';
					echo '<p><strong>User Roles:</strong> ' . implode( ', ', $user->roles ) . '</p>';
					echo '<p><strong>Primary Role:</strong> ' . $primary_role . '</p>';
					echo '<p><strong>Ultimate Member Active:</strong> ' . ( function_exists( 'um_user' ) ? 'Yes' : 'No' ) . '</p>';
					if ( function_exists( 'um_is_user_approved' ) ) {
						echo '<p><strong>UM Approved:</strong> ' . ( um_is_user_approved( get_current_user_id() ) ? 'Yes' : 'No' ) . '</p>';
					}
					echo '</div>';
				}
				?>
				<h1><?php echo esc_html( $dashboard_title ); ?></h1>
				<div class="user-info">
					<p>Willkommen, <strong><?php echo esc_html( $user->display_name ); ?></strong>! <?php echo $role_badge; ?></p>
					<p class="user-description">
						<?php
						if ( in_array( 'administrator', $user->roles ) || in_array( 'um_admin', $user->roles ) ) {
							echo 'Vollzugriff auf alle Terminverwaltungs- und Administrationsfunktionen.';
						} elseif ( in_array( 'um_seller', $user->roles ) ) {
							echo 'Verwalten Sie Kundentermine und Services als URVENA FIX Verkäufer.';
						} else {
							echo 'Verwalten Sie Ihre persönlichen Terminbuchungen bei URVENA FIX.';
						}
						?>
					</p>
				</div>
				<div class="user-actions">
					<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="logout-btn">
						🔓 Abmelden
					</a>
					<?php if ( function_exists( 'um_user_profile_url' ) ) : ?>
					<a href="<?php echo esc_url( um_user_profile_url() ); ?>" class="profile-btn">
						👤 Profil bearbeiten
					</a>
					<?php endif; ?>
					<?php if ( in_array( 'administrator', $user->roles ) || in_array( 'um_admin', $user->roles ) ) : ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=urvena-appointments' ) ); ?>" class="admin-btn">
						⚙️ Erweiterte Einstellungen
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="dashboard-tabs">
			<button class="tab-button active" data-tab="book">Termin buchen</button>
			<button class="tab-button" data-tab="manage">Termine verwalten</button>
			<button class="tab-button" data-tab="calendar">Kalender</button>
		</div>

		<!-- Booking Tab -->
		<div class="tab-content active" id="book-tab">
			<div class="booking-widget">
				<iframe src="/terminbuchung" style="width: 100%; height: 800px; border: none; border-radius: 8px;"></iframe>
			</div>
		</div>

		<!-- Management Tab -->
		<div class="tab-content" id="manage-tab">
			<div class="appointment-management">
				<h2>Ihre gebuchten Termine</h2>
				<div class="appointment-lookup">
					<form id="appointment-lookup-form">
						<div class="form-group">
							<label for="lookup_email">E-Mail-Adresse:</label>
							<input type="email" id="lookup_email" name="lookup_email" placeholder="Ihre E-Mail-Adresse eingeben" required>
						</div>
						<button type="submit" class="btn-search">Termine suchen</button>
					</form>
				</div>
				
				<div id="appointment-results" class="appointment-results" style="display: none;">
					<!-- Results will be loaded here -->
				</div>
			</div>
		</div>

		<!-- Calendar Tab -->
		<div class="tab-content" id="calendar-tab">
			<div class="calendar-view">
				<h2>Verfügbare Termine</h2>
				<div id="appointment-calendar-view">
					<!-- Calendar will be loaded here -->
				</div>
			</div>
		</div>
	</div>
</div>

<style>
.termine-dashboard {
	padding: 2rem 0;
	background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
	min-height: 80vh;
}

.dashboard-header {
	text-align: center;
	margin-bottom: 2rem;
}

.dashboard-header h1 {
	color: var(--primary-color);
	font-size: 2.5rem;
	margin-bottom: 0.5rem;
}

.dashboard-tabs {
	display: flex;
	justify-content: center;
	margin-bottom: 2rem;
	background: white;
	border-radius: 8px;
	padding: 0.5rem;
	box-shadow: 0 2px 10px rgba(0,0,0,0.1);
	max-width: 600px;
	margin-left: auto;
	margin-right: auto;
	margin-bottom: 2rem;
}

.tab-button {
	flex: 1;
	padding: 1rem 2rem;
	border: none;
	background: transparent;
	cursor: pointer;
	border-radius: 6px;
	font-weight: 600;
	transition: all 0.3s ease;
}

.tab-button:hover {
	background: #f8f9fa;
}

.tab-button.active {
	background: var(--primary-color);
	color: white;
}

.tab-content {
	display: none;
	background: white;
	border-radius: 12px;
	padding: 2rem;
	box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.tab-content.active {
	display: block;
}

/* Appointment Lookup */
.appointment-lookup {
	background: #f8f9fa;
	padding: 2rem;
	border-radius: 8px;
	margin-bottom: 2rem;
}

.form-group {
	margin-bottom: 1rem;
}

.form-group label {
	display: block;
	font-weight: 600;
	margin-bottom: 0.5rem;
	color: var(--text-color);
}

.form-group input {
	width: 100%;
	max-width: 400px;
	padding: 0.75rem;
	border: 2px solid #e9ecef;
	border-radius: 6px;
	font-size: 1rem;
}

.form-group input:focus {
	outline: none;
	border-color: var(--primary-color);
}

.btn-search {
	background: var(--primary-color);
	color: white;
	border: none;
	padding: 0.75rem 2rem;
	border-radius: 6px;
	cursor: pointer;
	font-weight: 600;
	transition: all 0.3s ease;
}

.btn-search:hover {
	background: var(--primary-hover);
	transform: translateY(-2px);
}

.appointment-results {
	margin-top: 2rem;
}

.appointment-card {
	background: white;
	border: 1px solid #e9ecef;
	border-radius: 8px;
	padding: 1.5rem;
	margin-bottom: 1rem;
	box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.appointment-card h3 {
	color: var(--primary-color);
	margin-bottom: 1rem;
}

.appointment-details {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 1rem;
}

.appointment-detail {
	display: flex;
	align-items: center;
	gap: 0.5rem;
}

.appointment-detail i {
	color: var(--primary-color);
	width: 20px;
}

.appointment-status {
	display: inline-block;
	padding: 0.25rem 0.75rem;
	border-radius: 20px;
	font-size: 0.875rem;
	font-weight: 600;
	text-transform: uppercase;
}

.status-pending {
	background: #fff3cd;
	color: #856404;
}

.status-confirmed {
	background: #d4edda;
	color: #155724;
}

.status-completed {
	background: #d1ecf1;
	color: #0c5460;
}

.status-cancelled {
	background: #f8d7da;
	color: #721c24;
}

/* Calendar View */
.calendar-view {
	min-height: 400px;
}

#appointment-calendar-view {
	background: #f8f9fa;
	border-radius: 8px;
	padding: 2rem;
	text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
	.dashboard-tabs {
		flex-direction: column;
		max-width: 100%;
	}
	
	.tab-button {
		margin-bottom: 0.5rem;
	}
	
	.tab-button:last-child {
		margin-bottom: 0;
	}
	
	.tab-content {
		padding: 1rem;
	}
	
	.appointment-details {
		grid-template-columns: 1fr;
	}
}
</style>

<script>
jQuery(document).ready(function($) {
	// Tab functionality
	$('.tab-button').click(function() {
		const tabId = $(this).data('tab');
		
		// Update active tab button
		$('.tab-button').removeClass('active');
		$(this).addClass('active');
		
		// Update active tab content
		$('.tab-content').removeClass('active');
		$('#' + tabId + '-tab').addClass('active');
	});
	
	// Appointment lookup
	$('#appointment-lookup-form').submit(function(e) {
		e.preventDefault();
		const email = $('#lookup_email').val();
		
		if (email) {
			lookupAppointments(email);
		}
	});
	
	function lookupAppointments(email) {
		$('#appointment-results').html('<p>Termine werden gesucht...</p>').show();
		
		$.ajax({
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			type: 'POST',
			data: {
				action: 'lookup_appointments',
				email: email,
				nonce: '<?php echo wp_create_nonce('urvena_appointment_lookup'); ?>'
			},
			success: function(response) {
				if (response.success) {
					displayAppointments(response.data);
				} else {
					$('#appointment-results').html('<p>Keine Termine gefunden oder Fehler beim Laden.</p>');
				}
			},
			error: function() {
				$('#appointment-results').html('<p>Fehler beim Laden der Termine.</p>');
			}
		});
	}
	
	function displayAppointments(appointments) {
		const container = $('#appointment-results');
		container.empty();
		
		if (appointments.length === 0) {
			container.html('<p>Keine Termine gefunden.</p>');
			return;
		}
		
		appointments.forEach(function(appointment) {
			const appointmentCard = $(`
				<div class="appointment-card">
					<h3>Termin #${appointment.id}</h3>
					<div class="appointment-details">
						<div class="appointment-detail">
							<i class="fas fa-calendar"></i>
							<span>${appointment.formatted_date}</span>
						</div>
						<div class="appointment-detail">
							<i class="fas fa-clock"></i>
							<span>${appointment.formatted_time} Uhr</span>
						</div>
						<div class="appointment-detail">
							<i class="fas fa-cog"></i>
							<span>${appointment.service_name}</span>
						</div>
						<div class="appointment-detail">
							<i class="fas fa-info-circle"></i>
							<span class="appointment-status status-${appointment.status}">${appointment.status_text}</span>
						</div>
					</div>
					${appointment.notes ? `<p><strong>Anmerkungen:</strong> ${appointment.notes}</p>` : ''}
				</div>
			`);
			
			container.append(appointmentCard);
		});
	}
});
</script>

<style>
/* Dashboard Security and User Interface Styles */
.dashboard-header .user-welcome {
	text-align: center;
	margin-bottom: 2rem;
}

.user-info {
	margin-bottom: 1.5rem;
}

.user-description {
	font-size: 1rem;
	color: #666;
	font-style: italic;
	margin-top: 0.5rem;
}

/* Role Badges */
.role-badge {
	display: inline-block;
	padding: 0.25rem 0.75rem;
	border-radius: 20px;
	font-size: 0.85rem;
	font-weight: 600;
	margin-left: 0.5rem;
	vertical-align: middle;
}

.role-badge.admin {
	background: linear-gradient(135deg, #dc3545, #b71c1c);
	color: white;
	box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.role-badge.seller {
	background: linear-gradient(135deg, #28a745, #1e7e34);
	color: white;
	box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.role-badge.customer {
	background: linear-gradient(135deg, #007bff, #0056b3);
	color: white;
	box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
}

.role-badge.user {
	background: linear-gradient(135deg, #6c757d, #495057);
	color: white;
	box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
}

.user-actions {
	display: flex;
	justify-content: center;
	gap: 1rem;
	margin-top: 1.5rem;
	flex-wrap: wrap;
}

.logout-btn,
.profile-btn,
.admin-btn {
	display: inline-flex;
	align-items: center;
	gap: 0.5rem;
	padding: 0.75rem 1.5rem;
	border-radius: 8px;
	text-decoration: none;
	font-weight: 600;
	font-size: 0.9rem;
	transition: all 0.3s ease;
}

.logout-btn {
	background: #dc3545;
	color: white;
	border: 2px solid #dc3545;
}

.logout-btn:hover {
	background: #c82333;
	border-color: #c82333;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.profile-btn {
	background: transparent;
	color: #dc3545;
	border: 2px solid #dc3545;
}

.profile-btn:hover {
	background: #dc3545;
	color: white;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.admin-btn {
	background: linear-gradient(135deg, #28a745, #20c997);
	color: white;
	border: 2px solid #28a745;
}

.admin-btn:hover {
	background: linear-gradient(135deg, #218838, #1fa884);
	border-color: #218838;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

/* Enhanced security indicator with role awareness */
.termine-dashboard::before {
	content: "🔒 Sichere Verbindung - " attr(data-role-level);
	position: fixed;
	top: 80px;
	right: 20px;
	background: #28a745;
	color: white;
	padding: 0.5rem 1rem;
	border-radius: 6px;
	font-size: 0.85rem;
	font-weight: 600;
	z-index: 1000;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Role-specific dashboard styling */
.termine-dashboard[data-user-role="administrator"]::before,
.termine-dashboard[data-user-role="um_admin"]::before {
	background: linear-gradient(135deg, #dc3545, #b71c1c);
	content: "🔒 Admin Zugriff";
}

.termine-dashboard[data-user-role="um_seller"]::before {
	background: linear-gradient(135deg, #28a745, #1e7e34);
	content: "🔒 Verkäufer Zugriff";
}

.termine-dashboard[data-user-role="um_customer"]::before {
	background: linear-gradient(135deg, #007bff, #0056b3);
	content: "🔒 Kunden Zugriff";
}

/* Enhanced appointment cards with role-based styling */
.appointment-card {
	border-left: 4px solid #28a745;
	position: relative;
	margin-bottom: 1rem;
}

.appointment-card::after {
	content: "✓";
	position: absolute;
	top: 10px;
	right: 15px;
	color: #28a745;
	font-weight: bold;
	font-size: 1.2rem;
}

/* Responsive design for user actions */
@media (max-width: 768px) {
	.user-actions {
		flex-direction: column;
		align-items: center;
	}
	
	.logout-btn,
	.profile-btn {
		width: 200px;
		justify-content: center;
	}
	
	.termine-dashboard::before {
		top: 60px;
		right: 10px;
		font-size: 0.8rem;
		padding: 0.4rem 0.8rem;
	}
}

/* Loading and error states for security */
.security-loading {
	display: inline-block;
	width: 20px;
	height: 20px;
	border: 3px solid #f3f3f3;
	border-top: 3px solid #dc3545;
	border-radius: 50%;
	animation: spin 1s linear infinite;
}

@keyframes spin {
	0% { transform: rotate(0deg); }
	100% { transform: rotate(360deg); }
}

.security-error {
	background: #f8d7da;
	color: #721c24;
	border: 1px solid #f5c6cb;
	border-radius: 6px;
	padding: 1rem;
	margin: 1rem 0;
}

.security-success {
	background: #d4edda;
	color: #155724;
	border: 1px solid #c3e6cb;
	border-radius: 6px;
	padding: 1rem;
	margin: 1rem 0;
}
</style>

<?php
// Add AJAX handler for appointment lookup
add_action('wp_ajax_lookup_appointments', 'handle_appointment_lookup');
add_action('wp_ajax_nopriv_lookup_appointments', 'handle_appointment_lookup');

function handle_appointment_lookup() {
	if (!wp_verify_nonce($_POST['nonce'], 'urvena_appointment_lookup')) {
		wp_die('Security check failed');
	}
	
	global $wpdb;
	$email = sanitize_email($_POST['email']);
	
	if (empty($email)) {
		wp_send_json_error('E-Mail-Adresse ist erforderlich.');
	}
	
	$appointments_table = $wpdb->prefix . 'urvena_appointments';
	$appointments = $wpdb->get_results($wpdb->prepare(
		"SELECT a.*, p.post_title as service_name 
		FROM $appointments_table a 
		LEFT JOIN {$wpdb->posts} p ON a.service_id = p.ID 
		WHERE a.customer_email = %s 
		ORDER BY a.appointment_date ASC, a.appointment_time ASC",
		$email
	));
	
	$services = array(
		1 => 'Reifenwechsel',
		2 => 'Reifenreparatur', 
		3 => 'Reifeneinlagerung',
		4 => 'Achsvermessung',
		5 => 'Wuchtung',
		6 => 'Beratung'
	);
	
	$status_texts = array(
		'pending' => 'Wartend',
		'confirmed' => 'Bestätigt',
		'completed' => 'Abgeschlossen',
		'cancelled' => 'Storniert'
	);
	
	$formatted_appointments = array();
	foreach ($appointments as $appointment) {
		$formatted_appointments[] = array(
			'id' => $appointment->id,
			'service_name' => isset($services[$appointment->service_id]) ? $services[$appointment->service_id] : 'Unbekannter Service',
			'formatted_date' => date('d.m.Y', strtotime($appointment->appointment_date)),
			'formatted_time' => date('H:i', strtotime($appointment->appointment_time)),
			'status' => $appointment->status,
			'status_text' => isset($status_texts[$appointment->status]) ? $status_texts[$appointment->status] : $appointment->status,
			'notes' => $appointment->notes
		);
	}
	
	wp_send_json_success($formatted_appointments);
}

get_footer(); ?>