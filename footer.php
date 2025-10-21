<?php
/**
 * Footer Template
 *
 * @package URVENA_Fix
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

	</main><!-- #primary -->

	<footer id="colophon" class="site-footer">
		<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
			<div class="footer-widgets">
				<div class="footer-widgets-container">
					<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
						<div class="footer-widget-area">
							<?php dynamic_sidebar( 'footer-1' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
						<div class="footer-widget-area">
							<?php dynamic_sidebar( 'footer-2' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
						<div class="footer-widget-area">
							<?php dynamic_sidebar( 'footer-3' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="footer-bottom">
			<div class="footer-bottom-container">
				<div class="footer-info">
					<p class="footer-company">
						<strong>URVENA FIX</strong><br>
						<?php
						$address = get_theme_mod( 'urvena_address', 'Mainzer Str. 70, 64293 Darmstadt' );
						echo esc_html( $address );
						?>
					</p>
					<p class="footer-contact">
						<?php
						$phone = get_theme_mod( 'urvena_phone', '+49 6151 123456' );
						$email = get_theme_mod( 'urvena_email', 'info@urvenafix.de' );
						?>
						Tel: <a href="tel:<?php echo esc_attr( urvena_format_phone_link( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a><br>
						E-Mail: <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
					</p>
				</div>

				<div class="footer-menu">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_class'     => 'footer-nav',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
					
					<!-- Mobile Navigation Links -->
					<div class="mobile-footer-nav">
						<ul class="mobile-footer-menu">
							<li><a href="<?php echo esc_url( home_url( '/dienstleistungen/' ) ); ?>">Dienstleistungen</a></li>
							<li><a href="<?php echo esc_url( home_url( '/ueber-uns/' ) ); ?>">Über uns</a></li>
							<li><a href="<?php echo esc_url( home_url( '/datenschutz/' ) ); ?>">Datenschutz</a></li>
						</ul>
					</div>
				</div>

				<div class="footer-copyright">
					<p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Alle Rechte vorbehalten.', 'urvena-fix' ); ?></p>
				</div>
			</div>
		</div>
	</footer>

</div><!-- #page -->

<?php
// Output schema markup.
urvena_output_schema_markup();
?>

<?php wp_footer(); ?>

</body>
</html>

