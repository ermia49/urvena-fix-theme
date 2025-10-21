<?php
/**
 * Single Service Template
 *
 * @package URVENA_Fix
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$icon        = get_post_meta( get_the_ID(), '_urvena_icon', true );
	$price_range = get_post_meta( get_the_ID(), '_urvena_price_range', true );
	?>

	<div class="service-single">
		<div class="service-single-header">
			<div class="container">
				<div class="breadcrumbs">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Startseite', 'urvena-fix' ); ?></a>
					<span class="separator">/</span>
					<a href="<?php echo esc_url( home_url( '/dienstleistungen/' ) ); ?>"><?php esc_html_e( 'Dienstleistungen', 'urvena-fix' ); ?></a>
					<span class="separator">/</span>
					<span><?php the_title(); ?></span>
				</div>
				<div class="service-single-header-content">
					<?php if ( $icon ) : ?>
						<div class="service-single-icon"><?php echo esc_html( $icon ); ?></div>
					<?php endif; ?>
					<h1 class="service-single-title"><?php the_title(); ?></h1>
					<?php if ( $price_range ) : ?>
						<div class="service-single-price"><?php echo esc_html( $price_range ); ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="service-single-content">
			<div class="container">
				<div class="service-single-grid">
					<div class="service-single-main">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="service-single-thumbnail">
								<?php the_post_thumbnail( 'large' ); ?>
							</div>
						<?php endif; ?>

						<div class="service-single-text">
							<?php the_content(); ?>
						</div>
					</div>

					<aside class="service-single-sidebar">
						<div class="service-sidebar-box">
							<h3><?php esc_html_e( 'Interesse an diesem Service?', 'urvena-fix' ); ?></h3>
							<p><?php esc_html_e( 'Kontaktieren Sie uns für weitere Informationen oder vereinbaren Sie direkt einen Termin.', 'urvena-fix' ); ?></p>
							<?php
							$phone = get_theme_mod( 'urvena_phone', '+49 6151 123456' );
							?>
							<a href="tel:<?php echo esc_attr( urvena_format_phone_link( $phone ) ); ?>" class="btn btn-primary btn-block">
								<?php esc_html_e( 'Jetzt anrufen', 'urvena-fix' ); ?>
							</a>
							<a href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>" class="btn btn-outline btn-block">
								<?php esc_html_e( 'Anfrage senden', 'urvena-fix' ); ?>
							</a>
						</div>

						<div class="service-sidebar-box">
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
					</aside>
				</div>
			</div>
		</div>

		<div class="service-single-related">
			<div class="container">
				<h2><?php esc_html_e( 'Weitere Dienstleistungen', 'urvena-fix' ); ?></h2>
				<div class="services-grid">
					<?php
					$related_services = new WP_Query(
						array(
							'post_type'      => 'service',
							'posts_per_page' => 3,
							'post__not_in'   => array( get_the_ID() ),
							'orderby'        => 'rand',
						)
					);

					if ( $related_services->have_posts() ) :
						while ( $related_services->have_posts() ) :
							$related_services->the_post();
							$related_icon        = get_post_meta( get_the_ID(), '_urvena_icon', true );
							$related_price_range = get_post_meta( get_the_ID(), '_urvena_price_range', true );
							?>
							<div class="service-card">
								<?php if ( $related_icon ) : ?>
									<div class="service-icon"><?php echo esc_html( $related_icon ); ?></div>
								<?php endif; ?>
								<h3 class="service-title"><?php the_title(); ?></h3>
								<div class="service-excerpt">
									<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 15 ) ); ?>
								</div>
								<?php if ( $related_price_range ) : ?>
									<div class="service-price"><?php echo esc_html( $related_price_range ); ?></div>
								<?php endif; ?>
								<a href="<?php the_permalink(); ?>" class="service-link">
									<?php esc_html_e( 'Mehr erfahren', 'urvena-fix' ); ?> →
								</a>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
					endif;
					?>
				</div>
			</div>
		</div>
	</div>

	<?php
endwhile;

get_footer();

