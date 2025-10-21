<?php
/**
 * Template Name: Terminbuchung
 *
 * @package URVENA_Fix
 * @since 1.1.0
 */

get_header(); ?>

<div class="booking-page">
	<div class="container">
		<div class="booking-header" itemscope itemtype="https://schema.org/Service">
			<h1 itemprop="name">Termin buchen bei URVENA FIX</h1>
			<p itemprop="description">Buchen Sie schnell und einfach Ihren Termin bei URVENA FIX in Darmstadt. Wählen Sie Ihren gewünschten Service und einen passenden Terminslot. Professioneller Reifenservice mit fairen Preisen.</p>
			
			<div class="booking-hero-image" style="text-align: center; margin: 2rem 0;">
				<img 
					src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1.png' ) ); ?>" 
					alt="URVENA FIX Reifenservice Terminbuchung - Professionelle Werkstatt in Darmstadt"
					title="Jetzt Termin buchen bei URVENA FIX - Reifenservice Darmstadt"
					style="max-width: 400px; width: 100%; height: auto; border-radius: 12px; box-shadow: 0 8px 24px rgba(220, 53, 69, 0.15);"
					loading="eager"
					itemprop="image"
				/>
			</div>
		</div>

		<div class="booking-form-container">
			<form id="appointment-booking-form" class="booking-form">
				<?php wp_nonce_field( 'urvena_appointment_nonce', 'appointment_nonce' ); ?>
				
				<div class="form-step active" id="step-1">
					<h2>1. Service auswählen</h2>
					<div class="service-selection">
						<?php
						// Get available services (you can create custom post type or use predefined services)
						$services = array(
							1 => array( 
								'name' => 'Reifenwechsel', 
								'description' => 'Professioneller Wechsel von Sommer- auf Winterreifen oder umgekehrt',
								'duration' => '30 Min',
								'price' => 'ab 25€'
							),
							2 => array( 
								'name' => 'Reifenreparatur', 
								'description' => 'Fachgerechte Reparatur von Reifenschäden und Plattfüßen',
								'duration' => '45 Min',
								'price' => 'ab 15€'
							),
							3 => array( 
								'name' => 'Reifeneinlagerung', 
								'description' => 'Sichere und fachgerechte Einlagerung Ihrer Reifen über die Saison',
								'duration' => '15 Min',
								'price' => 'ab 40€/Saison'
							),
							4 => array( 
								'name' => 'Achsvermessung', 
								'description' => 'Präzise Vermessung und Einstellung der Fahrzeugachsen',
								'duration' => '60 Min',
								'price' => 'ab 60€'
							),
							5 => array( 
								'name' => 'Radwuchtung', 
								'description' => 'Professionelle Auswuchtung für ruhigen Lauf und weniger Verschleiß',
								'duration' => '30 Min',
								'price' => 'ab 20€'
							),
							6 => array( 
								'name' => 'Kostenlose Beratung', 
								'description' => 'Umfassende Beratung zu Reifen, Service und Fahrzeugtechnik',
								'duration' => '20 Min',
								'price' => 'Kostenlos'
							)
						);
						
						foreach ( $services as $id => $service ) : ?>
							<div class="service-option">
								<input type="radio" id="service-<?php echo $id; ?>" name="service_id" value="<?php echo $id; ?>" required>
								<label for="service-<?php echo $id; ?>" class="service-card">
									<div class="service-info">
										<h3><?php echo esc_html( $service['name'] ); ?></h3>
										<p><?php echo esc_html( $service['description'] ); ?></p>
										<div class="service-details">
											<span class="duration"><i class="fas fa-clock"></i> <?php echo esc_html( $service['duration'] ); ?></span>
											<span class="price"><i class="fas fa-euro-sign"></i> <?php echo esc_html( $service['price'] ); ?></span>
										</div>
									</div>
								</label>
							</div>
						<?php endforeach; ?>
					</div>
					<button type="button" class="btn-next" onclick="nextStep()">Weiter</button>
				</div>

				<div class="form-step" id="step-2">
					<h2>2. Datum und Uhrzeit wählen</h2>
					<div class="datetime-selection">
						<div class="date-picker-wrapper">
							<label for="appointment_date">Datum auswählen:</label>
							<input type="date" id="appointment_date" name="appointment_date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
						</div>
						
						<div class="time-picker-wrapper">
							<label for="appointment_time">Verfügbare Zeiten:</label>
							<div id="available-times" class="time-slots">
								<p>Bitte wählen Sie zunächst ein Datum aus.</p>
							</div>
						</div>
					</div>
					<div class="step-navigation">
						<button type="button" class="btn-prev" onclick="prevStep()">Zurück</button>
						<button type="button" class="btn-next" onclick="nextStep()">Weiter</button>
					</div>
				</div>

				<div class="form-step" id="step-3">
					<h2>3. Ihre Kontaktdaten</h2>
					<div class="contact-form">
						<div class="form-row">
							<div class="form-group">
								<label for="customer_name">Name *</label>
								<input type="text" id="customer_name" name="customer_name" required>
							</div>
						</div>
						
						<div class="form-row">
							<div class="form-group">
								<label for="customer_email">E-Mail *</label>
								<input type="email" id="customer_email" name="customer_email" required>
							</div>
							<div class="form-group">
								<label for="customer_phone">Telefon *</label>
								<input type="tel" id="customer_phone" name="customer_phone" required>
							</div>
						</div>
						
						<div class="form-group">
							<label for="notes">Anmerkungen (optional)</label>
							<textarea id="notes" name="notes" rows="3" placeholder="Besondere Wünsche oder Anmerkungen..."></textarea>
						</div>
						
						<div class="privacy-notice">
							<label class="checkbox-wrapper">
								<input type="checkbox" id="privacy_consent" required>
								<span class="checkmark"></span>
								Ich stimme der <a href="/datenschutz" target="_blank">Datenschutzerklärung</a> zu und bin mit der Verarbeitung meiner Daten einverstanden.
							</label>
						</div>
					</div>
					<div class="step-navigation">
						<button type="button" class="btn-prev" onclick="prevStep()">Zurück</button>
						<button type="button" class="btn-next" onclick="nextStep()">Weiter</button>
					</div>
				</div>

				<div class="form-step" id="step-4">
					<h2>4. Terminübersicht</h2>
					<div class="appointment-summary">
						<div class="summary-card">
							<h3>Ihre Termindetails</h3>
							<div class="summary-item">
								<span class="label">Service:</span>
								<span class="value" id="summary-service"></span>
							</div>
							<div class="summary-item">
								<span class="label">Datum:</span>
								<span class="value" id="summary-date"></span>
							</div>
							<div class="summary-item">
								<span class="label">Uhrzeit:</span>
								<span class="value" id="summary-time"></span>
							</div>
							<div class="summary-item">
								<span class="label">Name:</span>
								<span class="value" id="summary-name"></span>
							</div>
							<div class="summary-item">
								<span class="label">E-Mail:</span>
								<span class="value" id="summary-email"></span>
							</div>
							<div class="summary-item">
								<span class="label">Telefon:</span>
								<span class="value" id="summary-phone"></span>
							</div>
						</div>
					</div>
					<div class="step-navigation">
						<button type="button" class="btn-prev" onclick="prevStep()">Zurück</button>
						<button type="submit" class="btn-submit" id="submit-booking">Termin buchen</button>
					</div>
				</div>
			</form>

			<div id="booking-result" class="booking-result" style="display: none;">
				<div class="result-content">
					<div class="success-icon">
						<i class="fas fa-check-circle"></i>
					</div>
					<h2>Termin erfolgreich gebucht!</h2>
					<p>Vielen Dank für Ihre Buchung. Sie erhalten in Kürze eine Bestätigungsmail mit allen Details.</p>
					<div class="next-steps">
						<h3>Nächste Schritte:</h3>
						<ul>
							<li>Sie erhalten eine Bestätigungsmail</li>
							<li>Wir melden uns bei Ihnen zur Terminbestätigung</li>
							<li>Bringen Sie Ihr Fahrzeug zum vereinbarten Termin</li>
						</ul>
					</div>
					<a href="/" class="btn-home">Zur Startseite</a>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
.booking-page {
	padding: 2rem 0;
	background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
	min-height: 80vh;
}

.booking-header {
	text-align: center;
	margin-bottom: 3rem;
}

.booking-header h1 {
	color: var(--primary-color);
	font-size: 2.5rem;
	margin-bottom: 1rem;
}

.booking-form-container {
	max-width: 800px;
	margin: 0 auto;
	background: white;
	border-radius: 12px;
	box-shadow: 0 10px 30px rgba(0,0,0,0.1);
	overflow: hidden;
}

.booking-form {
	padding: 2rem;
}

.form-step {
	display: none;
}

.form-step.active {
	display: block;
}

.form-step h2 {
	color: var(--primary-color);
	margin-bottom: 1.5rem;
	padding-bottom: 0.5rem;
	border-bottom: 2px solid var(--primary-color);
}

/* Service Selection */
.service-selection {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
	gap: 1rem;
	margin-bottom: 2rem;
}

.service-option input[type="radio"] {
	display: none;
}

.service-card {
	display: block;
	padding: 1.5rem;
	border: 2px solid #e9ecef;
	border-radius: 8px;
	background: #f8f9fa;
	cursor: pointer;
	transition: all 0.3s ease;
}

.service-card:hover {
	border-color: var(--primary-color);
	background: white;
	transform: translateY(-2px);
}

.service-option input[type="radio"]:checked + .service-card {
	border-color: var(--primary-color);
	background: var(--primary-color);
	color: white;
}

.service-info h3 {
	margin-bottom: 0.5rem;
	font-size: 1.2rem;
}

.service-details {
	display: flex;
	justify-content: space-between;
	margin-top: 1rem;
	font-size: 0.9rem;
}

/* Date and Time Selection */
.datetime-selection {
	display: grid;
	grid-template-columns: 1fr 2fr;
	gap: 2rem;
	margin-bottom: 2rem;
}

.date-picker-wrapper label,
.time-picker-wrapper label {
	display: block;
	font-weight: 600;
	margin-bottom: 0.5rem;
	color: var(--text-color);
}

#appointment_date {
	width: 100%;
	padding: 0.75rem;
	border: 2px solid #e9ecef;
	border-radius: 6px;
	font-size: 1rem;
}

.time-slots {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
	gap: 0.5rem;
}

.time-slot {
	padding: 0.75rem;
	background: #f8f9fa;
	border: 2px solid #e9ecef;
	border-radius: 6px;
	text-align: center;
	cursor: pointer;
	transition: all 0.3s ease;
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

.time-slot.unavailable {
	background: #6c757d;
	color: white;
	cursor: not-allowed;
	opacity: 0.6;
}

/* Contact Form */
.form-row {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 1rem;
	margin-bottom: 1rem;
}

.form-group {
	margin-bottom: 1rem;
}

.form-group:last-child {
	margin-bottom: 0;
}

.form-group label {
	display: block;
	font-weight: 600;
	margin-bottom: 0.5rem;
	color: var(--text-color);
}

.form-group input,
.form-group textarea {
	width: 100%;
	padding: 0.75rem;
	border: 2px solid #e9ecef;
	border-radius: 6px;
	font-size: 1rem;
	transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
	outline: none;
	border-color: var(--primary-color);
}

.privacy-notice {
	margin-top: 1.5rem;
	padding: 1rem;
	background: #f8f9fa;
	border-radius: 6px;
}

.checkbox-wrapper {
	display: flex;
	align-items: flex-start;
	cursor: pointer;
	font-size: 0.9rem;
	line-height: 1.4;
}

.checkbox-wrapper input[type="checkbox"] {
	display: none;
}

.checkmark {
	width: 20px;
	height: 20px;
	background: white;
	border: 2px solid #e9ecef;
	border-radius: 3px;
	margin-right: 0.75rem;
	flex-shrink: 0;
	position: relative;
	margin-top: 0.1rem;
}

.checkbox-wrapper input[type="checkbox"]:checked + .checkmark {
	background: var(--primary-color);
	border-color: var(--primary-color);
}

.checkbox-wrapper input[type="checkbox"]:checked + .checkmark::after {
	content: '✓';
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	color: white;
	font-size: 12px;
	font-weight: bold;
}

/* Appointment Summary */
.appointment-summary {
	margin-bottom: 2rem;
}

.summary-card {
	background: #f8f9fa;
	border-radius: 8px;
	padding: 2rem;
}

.summary-card h3 {
	color: var(--primary-color);
	margin-bottom: 1.5rem;
	text-align: center;
}

.summary-item {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 0.75rem 0;
	border-bottom: 1px solid #e9ecef;
}

.summary-item:last-child {
	border-bottom: none;
}

.summary-item .label {
	font-weight: 600;
	color: var(--text-color);
}

.summary-item .value {
	font-weight: 500;
	color: var(--primary-color);
}

/* Navigation Buttons */
.step-navigation {
	display: flex;
	justify-content: space-between;
	margin-top: 2rem;
}

.btn-next,
.btn-prev,
.btn-submit,
.btn-home {
	padding: 0.75rem 2rem;
	border: none;
	border-radius: 6px;
	font-size: 1rem;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.3s ease;
	text-decoration: none;
	display: inline-block;
	text-align: center;
}

.btn-next,
.btn-submit {
	background: var(--primary-color);
	color: white;
}

.btn-next:hover,
.btn-submit:hover {
	background: var(--primary-hover);
	transform: translateY(-2px);
}

.btn-prev {
	background: #6c757d;
	color: white;
}

.btn-prev:hover {
	background: #5a6268;
}

.btn-home {
	background: var(--primary-color);
	color: white;
	margin-top: 1rem;
}

/* Booking Result */
.booking-result {
	padding: 3rem 2rem;
	text-align: center;
}

.success-icon i {
	font-size: 4rem;
	color: #28a745;
	margin-bottom: 1rem;
}

.booking-result h2 {
	color: var(--primary-color);
	margin-bottom: 1rem;
}

.next-steps {
	background: #f8f9fa;
	border-radius: 8px;
	padding: 1.5rem;
	margin: 2rem 0;
	text-align: left;
}

.next-steps h3 {
	color: var(--primary-color);
	margin-bottom: 1rem;
}

.next-steps ul {
	list-style: none;
	padding: 0;
}

.next-steps li {
	padding: 0.5rem 0;
	position: relative;
	padding-left: 1.5rem;
}

.next-steps li::before {
	content: '✓';
	position: absolute;
	left: 0;
	color: #28a745;
	font-weight: bold;
}

/* Loading State */
.loading {
	opacity: 0.6;
	pointer-events: none;
}

.loading::after {
	content: '';
	position: absolute;
	top: 50%;
	left: 50%;
	width: 30px;
	height: 30px;
	border: 3px solid #f3f3f3;
	border-top: 3px solid var(--primary-color);
	border-radius: 50%;
	animation: spin 1s linear infinite;
	transform: translate(-50%, -50%);
}

@keyframes spin {
	0% { transform: translate(-50%, -50%) rotate(0deg); }
	100% { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
	.booking-page {
		padding: 1rem 0;
	}
	
	.booking-form {
		padding: 1.5rem;
	}
	
	.service-selection {
		grid-template-columns: 1fr;
	}
	
	.datetime-selection {
		grid-template-columns: 1fr;
		gap: 1.5rem;
	}
	
	.form-row {
		grid-template-columns: 1fr;
	}
	
	.step-navigation {
		flex-direction: column;
		gap: 1rem;
	}
	
	.btn-next,
	.btn-prev,
	.btn-submit {
		width: 100%;
	}
}
</style>

<script>
jQuery(document).ready(function($) {
	let currentStep = 1;
	const totalSteps = 4;
	let selectedTime = null;
	
	// Service selection
	$('input[name="service_id"]').change(function() {
		updateSummary();
	});
	
	// Date selection
	$('#appointment_date').change(function() {
		const selectedDate = $(this).val();
		if (selectedDate) {
			loadAvailableTimes(selectedDate);
		}
	});
	
	// Form submission
	$('#appointment-booking-form').submit(function(e) {
		e.preventDefault();
		submitBooking();
	});
	
	// Step navigation functions
	window.nextStep = function() {
		if (validateCurrentStep()) {
			if (currentStep < totalSteps) {
				$(`#step-${currentStep}`).removeClass('active');
				currentStep++;
				$(`#step-${currentStep}`).addClass('active');
				updateSummary();
			}
		}
	};
	
	window.prevStep = function() {
		if (currentStep > 1) {
			$(`#step-${currentStep}`).removeClass('active');
			currentStep--;
			$(`#step-${currentStep}`).addClass('active');
		}
	};
	
	function validateCurrentStep() {
		switch (currentStep) {
			case 1:
				if (!$('input[name="service_id"]:checked').length) {
					alert('Bitte wählen Sie einen Service aus.');
					return false;
				}
				break;
			case 2:
				if (!$('#appointment_date').val() || !selectedTime) {
					alert('Bitte wählen Sie Datum und Uhrzeit aus.');
					return false;
				}
				break;
			case 3:
				const name = $('#customer_name').val();
				const email = $('#customer_email').val();
				const phone = $('#customer_phone').val();
				const privacy = $('#privacy_consent').is(':checked');
				
				if (!name || !email || !phone || !privacy) {
					alert('Bitte füllen Sie alle Pflichtfelder aus und stimmen Sie der Datenschutzerklärung zu.');
					return false;
				}
				break;
		}
		return true;
	}
	
	function loadAvailableTimes(date) {
		$('#available-times').html('<p>Verfügbare Zeiten werden geladen...</p>');
		
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
					displayAvailableTimes(response.data);
				} else {
					$('#available-times').html('<p>Fehler beim Laden der verfügbaren Zeiten.</p>');
				}
			},
			error: function() {
				$('#available-times').html('<p>Fehler beim Laden der verfügbaren Zeiten.</p>');
			}
		});
	}
	
	function displayAvailableTimes(times) {
		const container = $('#available-times');
		container.empty();
		
		if (times.length === 0) {
			container.html('<p>Keine verfügbaren Termine an diesem Tag.</p>');
			return;
		}
		
		times.forEach(function(time) {
			const timeSlot = $(`
				<div class="time-slot" data-time="${time.time}">
					${time.display}
				</div>
			`);
			
			timeSlot.click(function() {
				$('.time-slot').removeClass('selected');
				$(this).addClass('selected');
				selectedTime = $(this).data('time');
				updateSummary();
			});
			
			container.append(timeSlot);
		});
	}
	
	function updateSummary() {
		// Update service
		const selectedService = $('input[name="service_id"]:checked');
		if (selectedService.length) {
			const serviceName = selectedService.next('label').find('h3').text();
			$('#summary-service').text(serviceName);
		}
		
		// Update date
		const selectedDate = $('#appointment_date').val();
		if (selectedDate) {
			const formattedDate = new Date(selectedDate).toLocaleDateString('de-DE');
			$('#summary-date').text(formattedDate);
		}
		
		// Update time
		if (selectedTime) {
			$('#summary-time').text(selectedTime.substring(0, 5) + ' Uhr');
		}
		
		// Update contact info
		$('#summary-name').text($('#customer_name').val());
		$('#summary-email').text($('#customer_email').val());
		$('#summary-phone').text($('#customer_phone').val());
	}
	
	function submitBooking() {
		$('#submit-booking').addClass('loading').prop('disabled', true);
		
		const formData = {
			action: 'book_appointment',
			nonce: $('#appointment_nonce').val(),
			customer_name: $('#customer_name').val(),
			customer_email: $('#customer_email').val(),
			customer_phone: $('#customer_phone').val(),
			service_id: $('input[name="service_id"]:checked').val(),
			appointment_date: $('#appointment_date').val(),
			appointment_time: selectedTime,
			notes: $('#notes').val()
		};
		
		$.ajax({
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			type: 'POST',
			data: formData,
			success: function(response) {
				if (response.success) {
					$('.booking-form-container').hide();
					$('#booking-result').show();
				} else {
					alert('Fehler: ' + response.data);
					$('#submit-booking').removeClass('loading').prop('disabled', false);
				}
			},
			error: function() {
				alert('Es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.');
				$('#submit-booking').removeClass('loading').prop('disabled', false);
			}
		});
	}
});
</script>

<?php get_footer(); ?>