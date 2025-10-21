<?php
/**
 * Template Name: Kontakt
 *
 * @package URVENA_Fix
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="page-header">
	<div class="container">
		<h1 class="page-title"><?php esc_html_e( 'Kontakt', 'urvena-fix' ); ?></h1>
		<p class="page-subtitle">
			<?php esc_html_e( 'Nehmen Sie Kontakt mit uns auf – wir freuen uns auf Ihre Nachricht!', 'urvena-fix' ); ?>
		</p>
	</div>
</div>

<div class="contact-page">
	<div class="container">
		<div class="contact-grid">
			<!-- Contact Form -->
			<div class="contact-form-section">
				<h2><?php esc_html_e( 'Senden Sie uns eine Nachricht', 'urvena-fix' ); ?></h2>

				<?php
				// Display success or error messages.
				if ( isset( $_GET['contact_success'] ) && '1' === $_GET['contact_success'] ) {
					?>
					<div class="contact-message contact-success">
						<p><?php esc_html_e( 'Vielen Dank für Ihre Nachricht! Wir werden uns schnellstmöglich bei Ihnen melden.', 'urvena-fix' ); ?></p>
					</div>
					<?php
				}

				if ( isset( $_GET['contact_error'] ) ) {
					?>
					<div class="contact-message contact-error">
						<p><?php echo esc_html( urldecode( $_GET['contact_error'] ) ); ?></p>
					</div>
					<?php
				}
				?>

				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="contact-form">
					<?php wp_nonce_field( 'urvena_contact_form', 'urvena_contact_nonce' ); ?>
					<input type="hidden" name="action" value="urvena_contact">

					<div class="form-group">
						<label for="contact_name">
							<?php esc_html_e( 'Name', 'urvena-fix' ); ?> <span class="required">*</span>
						</label>
						<input type="text" id="contact_name" name="contact_name" required>
					</div>

					<div class="form-group">
						<label for="contact_email">
							<?php esc_html_e( 'E-Mail', 'urvena-fix' ); ?> <span class="required">*</span>
						</label>
						<input type="email" id="contact_email" name="contact_email" required>
					</div>

					<div class="form-group">
						<label for="contact_phone">
							<?php esc_html_e( 'Telefon (optional)', 'urvena-fix' ); ?>
						</label>
						<input type="tel" id="contact_phone" name="contact_phone">
					</div>

					<div class="form-group">
						<label for="contact_subject">
							<?php esc_html_e( 'Betreff (optional)', 'urvena-fix' ); ?>
						</label>
						<input type="text" id="contact_subject" name="contact_subject">
					</div>

					<div class="form-group">
						<label for="contact_message">
							<?php esc_html_e( 'Nachricht', 'urvena-fix' ); ?> <span class="required">*</span>
						</label>
						<textarea id="contact_message" name="contact_message" rows="6" required></textarea>
					</div>

					<button type="submit" class="btn btn-primary">
						<?php esc_html_e( 'Nachricht senden', 'urvena-fix' ); ?>
					</button>
				</form>
			</div>

			<!-- Contact Information -->
			<div class="contact-info-section">
				<div class="contact-info-box">
					<h3><?php esc_html_e( 'Kontaktinformationen', 'urvena-fix' ); ?></h3>

					<div class="contact-info-item">
						<div class="contact-info-icon">📍</div>
						<div class="contact-info-content">
							<h4><?php esc_html_e( 'Adresse', 'urvena-fix' ); ?></h4>
							<p>
								<?php
								$address   = get_theme_mod( 'urvena_address', 'Mainzer Str. 70, 64293 Darmstadt' );
								$maps_link = get_theme_mod( 'urvena_maps_link', 'https://www.google.com/maps/place/Mainzer+Str.+70,+64293+Darmstadt' );
								echo esc_html( $address );
								?>
							</p>
							<a href="<?php echo esc_url( $maps_link ); ?>" target="_blank" rel="noopener noreferrer" class="contact-info-link">
								<?php esc_html_e( 'Auf Karte anzeigen', 'urvena-fix' ); ?> →
							</a>
						</div>
					</div>

					<div class="contact-info-item">
						<div class="contact-info-icon">📞</div>
						<div class="contact-info-content">
							<h4><?php esc_html_e( 'Telefon', 'urvena-fix' ); ?></h4>
							<?php
							$phone = get_theme_mod( 'urvena_phone', '+49 6151 123456' );
							?>
							<p>
								<a href="tel:<?php echo esc_attr( urvena_format_phone_link( $phone ) ); ?>">
									<?php echo esc_html( $phone ); ?>
								</a>
							</p>
						</div>
					</div>

					<div class="contact-info-item">
						<div class="contact-info-icon">📧</div>
						<div class="contact-info-content">
							<h4><?php esc_html_e( 'E-Mail', 'urvena-fix' ); ?></h4>
							<?php
							$email = get_theme_mod( 'urvena_email', 'info@urvenafix.de' );
							?>
							<p>
								<a href="mailto:<?php echo esc_attr( $email ); ?>">
									<?php echo esc_html( $email ); ?>
								</a>
							</p>
						</div>
					</div>
				</div>

				<div class="contact-info-box">
					<h3><?php esc_html_e( 'Öffnungszeiten', 'urvena-fix' ); ?></h3>
					<ul class="opening-hours">
						<li>
							<span><?php echo esc_html( get_theme_mod( 'urvena_hours_weekdays', 'Mo - Fr: 08:00 - 18:00 Uhr' ) ); ?></span>
						</li>
						<li>
							<span><?php echo esc_html( get_theme_mod( 'urvena_hours_saturday', 'Sa: 09:00 - 14:00 Uhr' ) ); ?></span>
						</li>
						<li>
							<span><?php echo esc_html( get_theme_mod( 'urvena_hours_sunday', 'So: Geschlossen' ) ); ?></span>
						</li>
					</ul>
				</div>

				<div class="contact-map">
					<a href="<?php echo esc_url( $maps_link ); ?>" target="_blank" rel="noopener noreferrer" class="map-link">
						<div class="map-placeholder">
							<svg width="100%" height="300" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect width="400" height="300" fill="#e0e0e0"/>
								<circle cx="200" cy="150" r="30" fill="#ff4444"/>
								<text x="200" y="160" font-family="Arial" font-size="24" fill="white" text-anchor="middle">📍</text>
								<text x="200" y="230" font-family="Arial" font-size="14" fill="#666" text-anchor="middle">
									<?php esc_html_e( 'Klicken für Karte', 'urvena-fix' ); ?>
								</text>
							</svg>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();

