<?php
/**
 * Security Redirects and Access Control
 * Ultimate Member Integration for URVENA FIX
 *
 * @package URVENA_Fix
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class URVENA_Security_Redirects
 * Handles secure redirections and access control
 */
class URVENA_Security_Redirects {
	
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'check_page_access' ) );
		add_action( 'init', array( $this, 'handle_login_redirects' ) );
		add_filter( 'login_redirect', array( $this, 'custom_login_redirect' ), 10, 3 );
		
		// Ultimate Member hooks
		add_action( 'um_user_login', array( $this, 'um_after_login_redirect' ) );
		add_action( 'um_user_logout', array( $this, 'um_after_logout_redirect' ) );
	}
	
	/**
	 * Check access to protected pages
	 */
	public function check_page_access() {
		global $post;
		
		// Skip checks for admin area
		if ( is_admin() ) {
			return;
		}
		
		// Protected pages that require login
		$protected_pages = array(
			'termine',
			'terminbuchung', // For dashboard booking
			'mein-profil',
			'dashboard'
		);
		
		// Check if current page is protected
		$is_protected = false;
		if ( $post && in_array( $post->post_name, $protected_pages ) ) {
			$is_protected = true;
		}
		
		// Check for termine page specifically
		if ( $post && $post->post_name === 'termine' ) {
			$this->secure_dashboard_access();
		}
		
		// General protection for other pages
		if ( $is_protected && ! is_user_logged_in() ) {
			$this->redirect_to_login( get_permalink() );
		}
	}
	
	/**
	 * Secure dashboard access with Ultimate Member integration
	 */
	private function secure_dashboard_access() {
		// Require login
		if ( ! is_user_logged_in() ) {
			$this->redirect_to_login( get_permalink() );
			return;
		}
		
		$user_id = get_current_user_id();
		
		// Ultimate Member specific checks
		if ( function_exists( 'um_user' ) ) {
			um_fetch_user( $user_id );
			
			// Check if user is approved
			if ( function_exists( 'um_is_user_approved' ) && ! um_is_user_approved( $user_id ) ) {
				$this->show_approval_pending_page();
				return;
			}
			
			// Check if user is active
			if ( function_exists( 'um_is_user_active' ) && ! um_is_user_active( $user_id ) ) {
				$this->show_account_inactive_page();
				return;
			}
			
			// Check user role restrictions
			if ( function_exists( 'um_user' ) ) {
				$user_status = um_user( 'account_status' );
				if ( $user_status === 'inactive' || $user_status === 'rejected' ) {
					$this->show_access_denied_page( 'Ihr Konto ist nicht aktiv.' );
					return;
				}
			}
		}
		
		// Additional role check with Ultimate Member roles
		$user = wp_get_current_user();
		$allowed_roles = array( 
			'administrator',     // WordPress Admin
			'editor',           // WordPress Editor  
			'subscriber',       // WordPress Subscriber
			'um_customer',      // Ultimate Member Customer
			'um_seller',        // Ultimate Member Seller
			'um_admin'          // Ultimate Member Admin
		);
		
		if ( ! array_intersect( $allowed_roles, $user->roles ) ) {
			$this->show_access_denied_page( 'Sie haben nicht die erforderlichen Berechtigungen für den Zugriff auf das Dashboard.' );
			return;
		}
		
		// Role-specific access levels
		$user_role = $this->get_user_primary_role( $user );
		$access_level = $this->get_role_access_level( $user_role );
		
		// Store access level in session for dashboard features
		if ( ! session_id() ) {
			session_start();
		}
		$_SESSION['urvena_user_access_level'] = $access_level;
		$_SESSION['urvena_user_role'] = $user_role;
		
		// Log successful access
		$this->log_access_event( 'dashboard_access_granted', $user_id );
	}
	
	/**
	 * Redirect to login with Ultimate Member integration
	 */
	private function redirect_to_login( $redirect_url ) {
		// Use Ultimate Member login page if available
		if ( function_exists( 'um_get_core_page' ) ) {
			$login_url = um_get_core_page( 'login' );
			if ( $login_url ) {
				$redirect_url_encoded = urlencode( $redirect_url );
				wp_redirect( add_query_arg( 'redirect_to', $redirect_url_encoded, $login_url ) );
				exit;
			}
		}
		
		// Fallback to WordPress login
		wp_redirect( wp_login_url( $redirect_url ) );
		exit;
	}
	
	/**
	 * Show approval pending page
	 */
	private function show_approval_pending_page() {
		wp_die( 
			'<div style="text-align: center; padding: 2rem; font-family: -apple-system, BlinkMacSystemFont, sans-serif;">
				<h1 style="color: #dc3545;">⏳ Genehmigung ausstehend</h1>
				<p style="font-size: 1.1rem; margin-bottom: 2rem;">Ihr Benutzerkonto muss noch von unserem Team genehmigt werden.</p>
				<div style="background: #f8f9fa; border-radius: 8px; padding: 1.5rem; margin: 2rem auto; max-width: 500px;">
					<h3 style="margin-top: 0;">Was passiert als Nächstes?</h3>
					<ul style="text-align: left; line-height: 1.8;">
						<li>Unser Team prüft Ihre Anmeldung</li>
						<li>Sie erhalten eine E-Mail-Benachrichtigung</li>
						<li>Nach der Genehmigung können Sie das Dashboard nutzen</li>
					</ul>
				</div>
				<p style="margin-top: 2rem;">
					<a href="' . esc_url( home_url() ) . '" style="background: #dc3545; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px;">Zurück zur Startseite</a>
				</p>
				<p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
					Fragen? Kontaktieren Sie uns: <a href="tel:+4961512345678">+49 6151 123456</a>
				</p>
			</div>',
			'Genehmigung ausstehend - URVENA FIX',
			array( 
				'response' => 403,
				'back_link' => false
			)
		);
	}
	
	/**
	 * Show account inactive page
	 */
	private function show_account_inactive_page() {
		wp_die( 
			'<div style="text-align: center; padding: 2rem; font-family: -apple-system, BlinkMacSystemFont, sans-serif;">
				<h1 style="color: #dc3545;">🚫 Konto inaktiv</h1>
				<p style="font-size: 1.1rem; margin-bottom: 2rem;">Ihr Benutzerkonto ist derzeit inaktiv.</p>
				<div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 1.5rem; margin: 2rem auto; max-width: 500px;">
					<h3 style="margin-top: 0; color: #856404;">Konto reaktivieren</h3>
					<p style="margin-bottom: 0; color: #856404;">Bitte wenden Sie sich an unser Support-Team, um Ihr Konto zu reaktivieren.</p>
				</div>
				<p style="margin-top: 2rem;">
					<a href="' . esc_url( home_url() ) . '" style="background: #dc3545; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px;">Zurück zur Startseite</a>
				</p>
			</div>',
			'Konto inaktiv - URVENA FIX',
			array( 
				'response' => 403,
				'back_link' => false
			)
		);
	}
	
	/**
	 * Show generic access denied page
	 */
	private function show_access_denied_page( $message = 'Zugriff verweigert.' ) {
		wp_die( 
			'<div style="text-align: center; padding: 2rem; font-family: -apple-system, BlinkMacSystemFont, sans-serif;">
				<h1 style="color: #dc3545;">🔒 Zugriff verweigert</h1>
				<p style="font-size: 1.1rem; margin-bottom: 2rem;">' . esc_html( $message ) . '</p>
				<p style="margin-top: 2rem;">
					<a href="' . esc_url( home_url() ) . '" style="background: #dc3545; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px;">Zurück zur Startseite</a>
				</p>
			</div>',
			'Zugriff verweigert - URVENA FIX',
			array( 
				'response' => 403,
				'back_link' => false
			)
		);
	}
	
	/**
	 * Handle login redirects
	 */
	public function handle_login_redirects() {
		// Process redirect parameter
		if ( isset( $_GET['redirect_to'] ) && is_user_logged_in() ) {
			$redirect_url = urldecode( $_GET['redirect_to'] );
			
			// Validate redirect URL for security
			if ( $this->is_safe_redirect_url( $redirect_url ) ) {
				wp_redirect( $redirect_url );
				exit;
			}
		}
	}
	
	/**
	 * Custom login redirect for WordPress
	 */
	public function custom_login_redirect( $redirect_to, $request, $user ) {
		// Check if this is a successful login
		if ( isset( $user->user_login ) ) {
			// If there's a redirect_to parameter, use it
			if ( ! empty( $request ) && $this->is_safe_redirect_url( $request ) ) {
				return $request;
			}
			
			// Default redirect to dashboard for URVENA users
			return home_url( '/termine/' );
		}
		
		return $redirect_to;
	}
	
	/**
	 * Ultimate Member after login redirect
	 */
	public function um_after_login_redirect() {
		$redirect_to = isset( $_REQUEST['redirect_to'] ) ? urldecode( $_REQUEST['redirect_to'] ) : '';
		
		if ( ! empty( $redirect_to ) && $this->is_safe_redirect_url( $redirect_to ) ) {
			wp_redirect( $redirect_to );
			exit;
		}
	}
	
	/**
	 * Ultimate Member after logout redirect
	 */
	public function um_after_logout_redirect() {
		wp_redirect( home_url() );
		exit;
	}
	
	/**
	 * Validate redirect URL for security
	 */
	private function is_safe_redirect_url( $url ) {
		// Must be within same domain
		$parsed_url = parse_url( $url );
		$site_url = parse_url( home_url() );
		
		// Check if domain matches
		if ( isset( $parsed_url['host'] ) && $parsed_url['host'] !== $site_url['host'] ) {
			return false;
		}
		
		// Prevent redirect loops and admin area access
		$blocked_paths = array( '/wp-admin/', '/wp-login.php', '/xmlrpc.php' );
		foreach ( $blocked_paths as $blocked_path ) {
			if ( strpos( $url, $blocked_path ) !== false ) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Get user's primary Ultimate Member role
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
	 * Get access level based on user role
	 */
	private function get_role_access_level( $role ) {
		$access_levels = array(
			'administrator' => 'full_admin',
			'um_admin' => 'admin',
			'um_seller' => 'seller',
			'editor' => 'editor',
			'um_customer' => 'customer',
			'subscriber' => 'basic'
		);
		
		return isset( $access_levels[$role] ) ? $access_levels[$role] : 'basic';
	}
	
	/**
	 * Check role-specific permissions for dashboard features
	 */
	public function check_feature_permission( $feature, $user_role = null ) {
		if ( ! $user_role ) {
			$user = wp_get_current_user();
			$user_role = $this->get_user_primary_role( $user );
		}
		
		$permissions = array(
			'view_all_appointments' => array( 'administrator', 'um_admin', 'um_seller' ),
			'manage_all_appointments' => array( 'administrator', 'um_admin' ),
			'view_calendar' => array( 'administrator', 'um_admin', 'um_seller', 'editor' ),
			'book_appointments' => array( 'administrator', 'um_admin', 'um_seller', 'um_customer', 'editor', 'subscriber' ),
			'cancel_appointments' => array( 'administrator', 'um_admin', 'um_seller', 'um_customer', 'editor' ),
			'modify_appointments' => array( 'administrator', 'um_admin', 'um_seller' ),
			'view_reports' => array( 'administrator', 'um_admin' ),
			'manage_services' => array( 'administrator', 'um_admin' ),
			'manage_users' => array( 'administrator', 'um_admin' )
		);
		
		return isset( $permissions[$feature] ) && in_array( $user_role, $permissions[$feature] );
	}
	
	/**
	 * Log access events for security monitoring
	 */
	private function log_access_event( $event_type, $user_id = null, $additional_data = array() ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		
		$log_data = array(
			'timestamp' => current_time( 'mysql' ),
			'event_type' => $event_type,
			'user_id' => $user_id,
			'ip_address' => $_SERVER['REMOTE_ADDR'],
			'user_agent' => $_SERVER['HTTP_USER_AGENT'],
			'page_url' => $_SERVER['REQUEST_URI'],
			'additional_data' => $additional_data
		);
		
		// Log to WordPress error log
		error_log( 'URVENA Access Event: ' . wp_json_encode( $log_data ) );
	}
}

// Initialize security redirects
new URVENA_Security_Redirects();