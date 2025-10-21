<?php
/**
 * Quick Appointment Booking Widget
 * 
 * @package URVENA_Fix
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Quick Booking Widget Class
 */
class URVENA_Quick_Booking_Widget extends WP_Widget {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'urvena_quick_booking',
			__( 'Schnelle Terminbuchung', 'urvena-fix' ),
			array(
				'description' => __( 'Zeigt ein schnelles Terminbuchungsformular an.', 'urvena-fix' ),
				'classname'   => 'urvena-quick-booking-widget'
			)
		);
	}
	
	/**
	 * Widget output
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Termin buchen', 'urvena-fix' );
		$show_calendar = ! empty( $instance['show_calendar'] ) ? true : false;
		
		echo $args['before_widget'];
		
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
		}
		
		?>
		<div class="quick-booking-widget">
			<?php if ( $show_calendar ) : ?>
			<div class="mini-calendar" id="appointment-calendar">
				<!-- Calendar will be rendered here -->
			</div>
			<?php endif; ?>
			
			<div class="quick-booking-form">
				<form id="quick-booking-form">
					<?php wp_nonce_field( 'urvena_appointment_nonce', 'appointment_nonce' ); ?>
					
					<div class="form-group">
						<label for="quick_service">Service:</label>
						<select id="quick_service" name="service_id" required>
							<option value="">Service wählen...</option>
							<option value="1">Reifenwechsel</option>
							<option value="2">Reifenreparatur</option>
							<option value="3">Reifeneinlagerung</option>
							<option value="4">Achsvermessung</option>
							<option value="5">Wuchtung</option>
							<option value="6">Beratung</option>
						</select>
					</div>
					
					<div class="datetime-selection">
						<div class="form-group">
							<label for="quick_date">Datum:</label>
							<input type="date" id="quick_date" name="appointment_date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
						</div>
						
						<div class="form-group">
							<label>Verfügbare Zeiten:</label>
							<div id="available-times" class="time-slots-mini">
								<p>Bitte Datum wählen</p>
							</div>
						</div>
					</div>
					
					<div class="contact-fields">
						<div class="form-group">
							<input type="text" id="quick_name" name="customer_name" placeholder="Ihr Name" required>
						</div>
						
						<div class="form-group">
							<input type="email" id="quick_email" name="customer_email" placeholder="E-Mail" required>
						</div>
						
						<div class="form-group">
							<input type="tel" id="quick_phone" name="customer_phone" placeholder="Telefon" required>
						</div>
						
						<div class="form-group">
							<textarea id="quick_notes" name="notes" rows="2" placeholder="Anmerkungen (optional)"></textarea>
						</div>
					</div>
					
					<button type="submit" class="btn-book-quick">Termin buchen</button>
				</form>
				
				<div class="booking-links">
					<a href="/terminbuchung" class="detailed-booking-link">Detaillierte Buchung</a>
					<a href="/termine" class="manage-appointments-link">Termine verwalten</a>
				</div>
			</div>
		</div>
		
		<style>
		.quick-booking-widget {
			background: white;
			border-radius: 8px;
			padding: 1.5rem;
			box-shadow: 0 5px 15px rgba(0,0,0,0.1);
			margin-bottom: 2rem;
		}
		
		.mini-calendar {
			margin-bottom: 1.5rem;
		}
		
		.calendar-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 1rem;
			padding: 0.5rem;
			background: var(--primary-color);
			color: white;
			border-radius: 6px;
		}
		
		.calendar-nav {
			background: none;
			border: none;
			color: white;
			font-size: 1.2rem;
			cursor: pointer;
			padding: 0.25rem 0.5rem;
		}
		
		.calendar-nav:hover {
			background: rgba(255,255,255,0.2);
			border-radius: 3px;
		}
		
		.calendar-grid {
			display: grid;
			grid-template-columns: repeat(7, 1fr);
			gap: 2px;
		}
		
		.calendar-day-header {
			background: #f8f9fa;
			padding: 0.5rem 0.25rem;
			text-align: center;
			font-size: 0.75rem;
			font-weight: 600;
			color: var(--text-color);
		}
		
		.calendar-day {
			aspect-ratio: 1;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 0.875rem;
			cursor: pointer;
			transition: all 0.2s ease;
		}
		
		.calendar-day.empty {
			cursor: default;
		}
		
		.calendar-day.past {
			color: #ccc;
			cursor: not-allowed;
		}
		
		.calendar-day.unavailable {
			color: #999;
			background: #f5f5f5;
			cursor: not-allowed;
		}
		
		.calendar-day.available {
			background: #f8f9fa;
			color: var(--text-color);
		}
		
		.calendar-day.available:hover {
			background: var(--primary-color);
			color: white;
		}
		
		.calendar-day.selected {
			background: var(--primary-color);
			color: white;
			font-weight: 600;
		}
		
		.quick-booking-form .form-group {
			margin-bottom: 1rem;
		}
		
		.quick-booking-form label {
			display: block;
			font-weight: 600;
			margin-bottom: 0.25rem;
			color: var(--text-color);
			font-size: 0.875rem;
		}
		
		.quick-booking-form input,
		.quick-booking-form select,
		.quick-booking-form textarea {
			width: 100%;
			padding: 0.5rem;
			border: 1px solid #e9ecef;
			border-radius: 4px;
			font-size: 0.875rem;
		}
		
		.quick-booking-form input:focus,
		.quick-booking-form select:focus,
		.quick-booking-form textarea:focus {
			outline: none;
			border-color: var(--primary-color);
		}
		
		.time-slots-mini {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 0.25rem;
			margin-top: 0.5rem;
		}
		
		.time-slot {
			padding: 0.5rem 0.25rem;
			background: #f8f9fa;
			border: 1px solid #e9ecef;
			border-radius: 4px;
			text-align: center;
			cursor: pointer;
			font-size: 0.75rem;
			transition: all 0.2s ease;
		}
		
		.time-slot:hover {
			border-color: var(--primary-color);
			background: white;
		}
		
		.time-slot.selected {
			background: var(--primary-color);
			border-color: var(--primary-color);
			color: white;
		}
		
		.btn-book-quick {
			width: 100%;
			padding: 0.75rem;
			background: var(--primary-color);
			color: white;
			border: none;
			border-radius: 4px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
			margin-bottom: 1rem;
		}
		
		.btn-book-quick:hover {
			background: var(--primary-hover);
			transform: translateY(-1px);
		}
		
		.booking-links {
			display: flex;
			justify-content: space-between;
			font-size: 0.875rem;
		}
		
		.booking-links a {
			color: var(--primary-color);
			text-decoration: none;
			font-weight: 500;
		}
		
		.booking-links a:hover {
			text-decoration: underline;
		}
		
		/* Responsive adjustments */
		@media (max-width: 768px) {
			.time-slots-mini {
				grid-template-columns: repeat(2, 1fr);
			}
			
			.booking-links {
				flex-direction: column;
				gap: 0.5rem;
			}
		}
		</style>
		
		<script>
		jQuery(document).ready(function($) {
			// Date change handler
			$('#quick_date').change(function() {
				const selectedDate = $(this).val();
				if (selectedDate) {
					loadQuickAvailableTimes(selectedDate);
				}
			});
			
			function loadQuickAvailableTimes(date) {
				$('#available-times').html('<p>Lade...</p>');
				
				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					type: 'POST',
					data: {
						action: 'get_available_times',
						date: date,
						nonce: $('#appointment_nonce').val()
					},
					success: function(response) {
						if (response.success) {
							displayQuickAvailableTimes(response.data);
						} else {
							$('#available-times').html('<p>Keine Zeiten</p>');
						}
					},
					error: function() {
						$('#available-times').html('<p>Fehler</p>');
					}
				});
			}
			
			function displayQuickAvailableTimes(times) {
				const container = $('#available-times');
				container.empty();
				
				if (times.length === 0) {
					container.html('<p>Keine Termine</p>');
					return;
				}
				
				times.forEach(function(time) {
					const timeSlot = $(`<div class="time-slot" data-time="${time.time}">${time.display}</div>`);
					
					timeSlot.click(function() {
						$('.time-slot').removeClass('selected');
						$(this).addClass('selected');
						window.selectedQuickTime = $(this).data('time');
					});
					
					container.append(timeSlot);
				});
			}
			
			// Form submission
			$('#quick-booking-form').submit(function(e) {
				e.preventDefault();
				
				if (!window.selectedQuickTime) {
					alert('Bitte wählen Sie eine Uhrzeit aus.');
					return;
				}
				
				const formData = {
					action: 'book_appointment',
					nonce: $('#appointment_nonce').val(),
					customer_name: $('#quick_name').val(),
					customer_email: $('#quick_email').val(),
					customer_phone: $('#quick_phone').val(),
					service_id: $('#quick_service').val(),
					appointment_date: $('#quick_date').val(),
					appointment_time: window.selectedQuickTime,
					notes: $('#quick_notes').val()
				};
				
				$('.btn-book-quick').text('Wird gebucht...').prop('disabled', true);
				
				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					type: 'POST',
					data: formData,
					success: function(response) {
						if (response.success) {
							alert('Termin erfolgreich gebucht! Sie erhalten eine Bestätigungsmail.');
							$('#quick-booking-form')[0].reset();
							$('.time-slot').removeClass('selected');
							window.selectedQuickTime = null;
							$('#available-times').html('<p>Bitte Datum wählen</p>');
						} else {
							alert('Fehler: ' + response.data);
						}
						$('.btn-book-quick').text('Termin buchen').prop('disabled', false);
					},
					error: function() {
						alert('Es ist ein Fehler aufgetreten.');
						$('.btn-book-quick').text('Termin buchen').prop('disabled', false);
					}
				});
			});
		});
		</script>
		<?php
		
		echo $args['after_widget'];
	}
	
	/**
	 * Widget form in admin
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Termin buchen', 'urvena-fix' );
		$show_calendar = ! empty( $instance['show_calendar'] ) ? true : false;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Titel:', 'urvena-fix' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_calendar' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_calendar' ) ); ?>" <?php checked( $show_calendar ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_calendar' ) ); ?>"><?php _e( 'Mini-Kalender anzeigen', 'urvena-fix' ); ?></label>
		</p>
		<?php
	}
	
	/**
	 * Update widget settings
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['show_calendar'] = ! empty( $new_instance['show_calendar'] ) ? true : false;
		
		return $instance;
	}
}

/**
 * Register the widget
 */
function register_urvena_quick_booking_widget() {
	register_widget( 'URVENA_Quick_Booking_Widget' );
}
add_action( 'widgets_init', 'register_urvena_quick_booking_widget' );