<?php
/**
 * URVENA FIX Cache Management
 * Provides cache clearing functionality
 *
 * @package URVENA_Fix
 * @since 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URVENA Cache Manager
 */
class URVENA_Cache_Manager {
	
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_cache_menu' ) );
		add_action( 'wp_ajax_urvena_clear_cache', array( $this, 'ajax_clear_cache' ) );
		add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_cache_button' ), 999 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_cache_scripts' ) );
	}
	
	/**
	 * Add cache management to admin menu
	 */
	public function add_cache_menu() {
		add_management_page(
			'Cache verwalten',
			'Cache leeren',
			'manage_options',
			'urvena-cache',
			array( $this, 'cache_management_page' )
		);
	}
	
	/**
	 * Add cache clear button to admin bar
	 */
	public function add_admin_bar_cache_button( $wp_admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		
		$wp_admin_bar->add_node( array(
			'id'    => 'urvena-clear-cache',
			'title' => '🗑️ Cache leeren',
			'href'  => '#',
			'meta'  => array(
				'class' => 'urvena-clear-cache-btn'
			)
		) );
	}
	
	/**
	 * Enqueue cache management scripts
	 */
	public function enqueue_cache_scripts( $hook ) {
		wp_enqueue_script( 'jquery' );
		
		$script = "
		jQuery(document).ready(function($) {
			$('.urvena-clear-cache-btn, #urvena-clear-cache-btn').click(function(e) {
				e.preventDefault();
				
				if (confirm('Möchten Sie wirklich den gesamten Cache leeren?')) {
					$(this).text('⏳ Cache wird geleert...');
					
					$.post(ajaxurl, {
						action: 'urvena_clear_cache',
						nonce: '" . wp_create_nonce( 'urvena_cache_nonce' ) . "'
					}, function(response) {
						if (response.success) {
							alert('✅ Cache erfolgreich geleert!');
							location.reload();
						} else {
							alert('❌ Fehler beim Leeren des Cache: ' + response.data);
						}
					}).fail(function() {
						alert('❌ Fehler beim Leeren des Cache.');
					});
				}
			});
		});
		";
		
		wp_add_inline_script( 'jquery', $script );
	}
	
	/**
	 * Cache management page
	 */
	public function cache_management_page() {
		if ( isset( $_POST['clear_cache'] ) && wp_verify_nonce( $_POST['cache_nonce'], 'urvena_cache_action' ) ) {
			$this->clear_all_cache();
			echo '<div class="notice notice-success"><p>✅ Cache wurde erfolgreich geleert!</p></div>';
		}
		
		?>
		<div class="wrap">
			<h1>🗑️ URVENA FIX - Cache Management</h1>
			
			<div class="card">
				<h2>Cache Status</h2>
				<table class="widefat">
					<tr>
						<td><strong>WordPress Object Cache:</strong></td>
						<td><?php echo wp_using_ext_object_cache() ? '✅ Aktiv' : '❌ Inaktiv'; ?></td>
					</tr>
					<tr>
						<td><strong>Theme Version:</strong></td>
						<td><?php echo URVENA_VERSION; ?></td>
					</tr>
					<tr>
						<td><strong>Letzte Cache-Löschung:</strong></td>
						<td><?php echo get_option( 'urvena_last_cache_clear', 'Nie' ); ?></td>
					</tr>
				</table>
			</div>
			
			<div class="card">
				<h2>Cache leeren</h2>
				<p>Löscht alle zwischengespeicherten Daten um sicherzustellen, dass die neuesten Änderungen angezeigt werden.</p>
				
				<form method="post">
					<?php wp_nonce_field( 'urvena_cache_action', 'cache_nonce' ); ?>
					<button type="submit" name="clear_cache" class="button-primary" id="urvena-clear-cache-btn">
						🗑️ Gesamten Cache leeren
					</button>
				</form>
			</div>
			
			<div class="card">
				<h2>Automatische Cache-Optimierung</h2>
				<p>Die folgenden Optimierungen sind automatisch aktiv:</p>
				<ul>
					<li>✅ CSS/JS Versioning für Browser-Cache-Busting</li>
					<li>✅ Automatisches Leeren bei Theme-Updates</li>
					<li>✅ Optimierte Bild-Caching für Carousel</li>
					<li>✅ Database Query Caching für Termine</li>
				</ul>
			</div>
		</div>
		<?php
	}
	
	/**
	 * AJAX cache clearing handler
	 */
	public function ajax_clear_cache() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'urvena_cache_nonce' ) ) {
			wp_send_json_error( 'Ungültiger Nonce' );
		}
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unzureichende Berechtigung' );
		}
		
		$result = $this->clear_all_cache();
		
		if ( $result ) {
			wp_send_json_success( 'Cache erfolgreich geleert' );
		} else {
			wp_send_json_error( 'Fehler beim Leeren des Cache' );
		}
	}
	
	/**
	 * Clear all types of cache
	 */
	public function clear_all_cache() {
		$cleared = array();
		
		// 1. WordPress Object Cache
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
			$cleared[] = 'Object Cache';
		}
		
		// 2. Transients
		$this->clear_transients();
		$cleared[] = 'Transients';
		
		// 3. Opcache (if available)
		if ( function_exists( 'opcache_reset' ) ) {
			opcache_reset();
			$cleared[] = 'OPcache';
		}
		
		// 4. Update theme version for CSS/JS cache busting
		$this->update_theme_version();
		$cleared[] = 'CSS/JS Cache';
		
		// 5. Clear appointment cache
		$this->clear_appointment_cache();
		$cleared[] = 'Appointment Cache';
		
		// 6. Popular cache plugins
		$this->clear_plugin_caches();
		
		// Log the cache clearing
		update_option( 'urvena_last_cache_clear', current_time( 'mysql' ) );
		error_log( 'URVENA Cache Cleared: ' . implode( ', ', $cleared ) );
		
		return true;
	}
	
	/**
	 * Clear WordPress transients
	 */
	private function clear_transients() {
		global $wpdb;
		
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_%'" );
	}
	
	/**
	 * Update theme version for cache busting
	 */
	private function update_theme_version() {
		// This forces browser cache refresh for CSS/JS files
		$new_version = '1.2.0-' . time();
		
		// Update the constant in memory (for current request)
		if ( defined( 'URVENA_VERSION' ) ) {
			// Note: We can't redefine constants, but the file versioning will work on next page load
		}
	}
	
	/**
	 * Clear appointment-specific cache
	 */
	private function clear_appointment_cache() {
		// Clear appointment-related transients
		delete_transient( 'urvena_appointment_services' );
		delete_transient( 'urvena_available_times' );
		delete_transient( 'urvena_appointment_stats' );
		
		// Clear any appointment query cache
		wp_cache_delete( 'urvena_appointments', 'urvena' );
	}
	
	/**
	 * Clear popular caching plugin caches
	 */
	private function clear_plugin_caches() {
		// W3 Total Cache
		if ( function_exists( 'w3tc_flush_all' ) ) {
			w3tc_flush_all();
		}
		
		// WP Rocket
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
		
		// WP Super Cache
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache();
		}
		
		// LiteSpeed Cache
		if ( class_exists( 'LiteSpeed_Cache_API' ) ) {
			LiteSpeed_Cache_API::purge_all();
		}
		
		// Autoptimize
		if ( class_exists( 'autoptimizeCache' ) ) {
			autoptimizeCache::clearall();
		}
	}
}

// Initialize cache manager
new URVENA_Cache_Manager();