<?php
/**
 * Template Name: Dienstleistungen
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
		<h1 class="page-title"><?php esc_html_e( 'Unsere Dienstleistungen', 'urvena-fix' ); ?></h1>
		<p class="page-subtitle">
			<?php
			esc_html_e(
				'Entdecken Sie unser umfassendes Angebot an professionellen Dienstleistungen rund um Ihr Fahrzeug',
				'urvena-fix'
			);
			?>
		</p>
	</div>
</div>

<div class="services-page">
	<div class="container">
		<!-- Main Service Categories -->
		<div class="services-detailed">
			<!-- Reifenwechsel Section -->
			<section class="service-category" id="reifenwechsel">
				<div class="service-category-header">
					<div class="service-category-icon">🔧</div>
					<h2 class="service-category-title">Reifenwechsel</h2>
					<p class="service-category-subtitle">Professioneller Reifen- und Radwechsel für alle PKW</p>
				</div>
				<div class="service-items-detailed">
					<div class="service-detail-card">
						<h3 class="service-detail-title">
							<span class="service-detail-icon">🚗</span>
							Saisonaler Reifenwechsel
						</h3>
						<p class="service-detail-description">Fachgerechter Wechsel zwischen Sommer- und Winterreifen für alle PKW-Modelle</p>
					</div>
					<div class="service-detail-card">
						<h3 class="service-detail-title">
							<span class="service-detail-icon">⚖️</span>
							Präzises Auswuchten
						</h3>
						<p class="service-detail-description">Für ruhigen Lauf und gleichmäßigen Reifenverschleiß</p>
					</div>
					<div class="service-detail-card">
						<h3 class="service-detail-title">
							<span class="service-detail-icon">🔍</span>
							Umfassender Sicherheitscheck
						</h3>
						<p class="service-detail-description">Profiltiefe, DOT-Nummer, sichtbare Schäden - Ihre Sicherheit hat Priorität</p>
					</div>
					<div class="service-detail-card">
						<h3 class="service-detail-title">
							<span class="service-detail-icon">📊</span>
							Reifendruckservice
						</h3>
						<p class="service-detail-description">Prüfung und Anpassung des Reifendrucks mit optionalem Protokoll</p>
					</div>
					<div class="service-detail-card">
						<h3 class="service-detail-title">
							<span class="service-detail-icon">🧽</span>
							Professionelle Montage
						</h3>
						<p class="service-detail-description">Reinigung der Anlageflächen & Anzug mit Drehmoment nach Herstellervorgabe</p>
					</div>
					<div class="service-detail-card">
						<h3 class="service-detail-title">
							<span class="service-detail-icon">📦</span>
							Reifeneinlagerung
						</h3>
						<p class="service-detail-description">Fachgerechte Lagerung Ihrer Reifen mit Kennzeichnung und Pflegedokumentation</p>
					</div>
				</div>
			</section>

			<!-- Ölwechsel Section -->
			<section class="service-category" id="oelwechsel">
				<div class="service-category-header">
					<div class="service-category-icon">🛢️</div>
					<h2 class="service-category-title">Ölwechsel</h2>
					<p class="service-category-subtitle">Kompletter Ölservice nach Herstellervorgaben</p>
				</div>
				<div class="service-items-detailed">
					<div class="service-detail-card">
						<h3 class="service-detail-title">
							<span class="service-detail-icon">🔧</span>
							Motorölwechsel
						</h3>
						<p class="service-detail-description">Kompletter Motorölservice gemäß Herstellerfreigabe inklusive Ölfilterwechsel</p>
					</div>
					<div class="service-detail-card">
						<h3 class="service-detail-title">
							<span class="service-detail-icon">⚙️</span>
							Getriebe- & Achsantriebsöl
						</h3>
						<p class="service-detail-description">Ölwechsel an Schaltgetrieben und Achsantrieben nach Herstellerangaben</p>
					</div>
					<div class="service-detail-card">
						<h3 class="service-detail-title">
							<span class="service-detail-icon">🔄</span>
							Servolenkungsöl
						</h3>
						<p class="service-detail-description">Fachgerechte Kontrolle, Nachfüllen und Austausch bei Bedarf</p>
					</div>
					<div class="service-detail-card">
						<h3 class="service-detail-title">
							<span class="service-detail-icon">🔍</span>
							Flüssigkeitscheck
						</h3>
						<p class="service-detail-description">Kontrolle von Bremsflüssigkeit, Kühlmittel und anderen Betriebsstoffen</p>
					</div>
					<div class="service-detail-card">
						<h3 class="service-detail-title">
							<span class="service-detail-icon">♻️</span>
							Umweltgerechte Entsorgung
						</h3>
						<p class="service-detail-description">Fachgerechte und umweltschonende Entsorgung von Altöl und Filtern</p>
					</div>
				</div>
			</section>

			<!-- Reifen Verkauf & Zubehör Section -->
			<section class="service-category shop-category" id="reifen-verkauf">
				<div class="service-category-header">
					<div class="service-category-icon">🛒</div>
					<h2 class="service-category-title">Reifen Verkauf & Zubehör</h2>
					<p class="service-category-subtitle">Hochwertige Reifen und Zubehör für Ihr Fahrzeug</p>
				</div>
				<div class="shop-redirect-content">
					<div class="shop-info-grid">
						<div class="shop-info-card">
							<div class="shop-info-icon">🏆</div>
							<h3>Premium Reifen</h3>
							<p>Markenreifen von führenden Herstellern für maximale Sicherheit und Leistung</p>
						</div>
						<div class="shop-info-card">
							<div class="shop-info-icon">💰</div>
							<h3>Günstige Preise</h3>
							<p>Faire Preise und attraktive Angebote für Sommer-, Winter- und Ganzjahresreifen</p>
						</div>
						<div class="shop-info-card">
							<div class="shop-info-icon">🔧</div>
							<h3>Komplettes Zubehör</h3>
							<p>Felgen, Ventile, Gewichte und weiteres Zubehör für die professionelle Montage</p>
						</div>
					</div>
					<div class="shop-cta-section">
						<h3 class="shop-cta-title">Besuchen Sie unseren Online-Shop</h3>
						<p class="shop-cta-description">
							Entdecken Sie unser umfangreiches Sortiment an hochwertigen Reifen und Zubehör. 
							Bequem online bestellen und direkt zu uns liefern lassen.
						</p>
						<div class="shop-buttons">
							<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="btn-shop-primary">
								<span class="shop-btn-icon">🛒</span>
								Zum Online-Shop
							</a>
							<a href="<?php echo esc_url( home_url( '/shop/kategorie/reifen/' ) ); ?>" class="btn-shop-secondary">
								<span class="shop-btn-icon">🚗</span>
								Reifen anzeigen
							</a>
							<a href="<?php echo esc_url( home_url( '/shop/kategorie/zubehoer/' ) ); ?>" class="btn-shop-secondary">
								<span class="shop-btn-icon">🔧</span>
								Zubehör anzeigen
							</a>
						</div>
					</div>
				</div>
			</section>

			<!-- Legal Notice Section -->
			<section class="service-legal-notice">
				<div class="legal-notice-content">
					<h3 class="legal-notice-title">
						<span class="legal-notice-icon">⚖️</span>
						Rechtlicher Hinweis
					</h3>
					<div class="legal-notice-text">
						<p>
							<strong>Wir sind kein Meisterbetrieb des Kfz- oder Vulkaniseur-Handwerks.</strong> 
							Unsere Leistungen beschränken sich auf nicht handwerkspflichtige Tätigkeiten gemäß Handwerksordnung.
						</p>
						<div class="legal-notice-excluded">
							<h4>Nicht angeboten:</h4>
							<ul>
								<li>Arbeiten an Bremsanlage</li>
								<li>Fahrwerk</li>
								<li>Elektrik</li>
								<li>Abgasanlage</li>
								<li>Achsvermessung u. Ä.</li>
							</ul>
							<p class="legal-notice-reference">
								<strong>→</strong> Hierfür verweisen wir Sie gerne an qualifizierte Meisterbetriebe.
							</p>
						</div>
					</div>
				</div>
			</section>
		</div>

		<!-- Original Services List -->
		<div class="services-list">
			<?php
			$services = new WP_Query(
				array(
					'post_type'      => 'service',
					'posts_per_page' => -1,
					'orderby'        => 'meta_value_num',
					'meta_key'       => '_urvena_order',
					'order'          => 'ASC',
				)
			);

			if ( $services->have_posts() ) :
				?>
				<h2 class="services-list-title">Weitere Informationen</h2>
				<?php
				while ( $services->have_posts() ) :
					$services->the_post();
					$icon        = get_post_meta( get_the_ID(), '_urvena_icon', true );
					$price_range = get_post_meta( get_the_ID(), '_urvena_price_range', true );
					?>
					<article class="service-item">
						<div class="service-item-header">
							<?php if ( $icon ) : ?>
								<div class="service-item-icon"><?php echo esc_html( $icon ); ?></div>
							<?php endif; ?>
							<div class="service-item-title-wrap">
								<h3 class="service-item-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
								<?php if ( $price_range ) : ?>
									<div class="service-item-price"><?php echo esc_html( $price_range ); ?></div>
								<?php endif; ?>
							</div>
						</div>
						<div class="service-item-content">
							<?php the_excerpt(); ?>
						</div>
						<div class="service-item-footer">
							<a href="<?php the_permalink(); ?>" class="btn btn-small">
								<?php esc_html_e( 'Details ansehen', 'urvena-fix' ); ?> →
							</a>
						</div>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>

		<div class="services-cta">
			<div class="services-cta-box">
				<h3><?php esc_html_e( 'Haben Sie Fragen zu unseren Dienstleistungen?', 'urvena-fix' ); ?></h3>
				<p><?php esc_html_e( 'Kontaktieren Sie uns für eine persönliche Beratung!', 'urvena-fix' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>" class="btn btn-primary">
					<?php esc_html_e( 'Kontakt aufnehmen', 'urvena-fix' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();

