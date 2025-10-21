<?php
/**
 * Front Page Template
 *
 * @package URVENA_Fix
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- Hero Section -->
<section class="hero-section">
	<div class="hero-container">
		<div class="hero-content">
			<h1 class="hero-title" itemprop="headline">
				<?php
				echo esc_html(
					get_theme_mod(
						'urvena_hero_title',
						'Professioneller Reifenservice in Darmstadt'
					)
				);
				?>
			</h1>
			<p class="hero-subtitle" itemprop="description">
				<?php
				echo esc_html(
					get_theme_mod(
						'urvena_hero_subtitle',
						'Qualität, Zuverlässigkeit und faire Preise – Ihr vertrauensvoller Partner für Reifen und Autoservice seit Jahren'
					)
				);
				?>
			</p>

		</div>
		<div class="hero-image">
			<div class="hero-image-container">
				<img 
					src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1.png' ) ); ?>" 
					alt="URVENA FIX Reifenservice Darmstadt - Professioneller Reifenwechsel und Autoservice"
					title="URVENA FIX - Ihr Experte für Reifen und Autoservice in Darmstadt"
					class="hero-main-image"
					width="600" 
					height="400"
					loading="eager"
					fetchpriority="high"
					itemscope 
					itemtype="https://schema.org/ImageObject"
					itemprop="image"
				/>
				<meta itemprop="name" content="URVENA FIX Reifenservice Werkstatt">
				<meta itemprop="description" content="Moderne Reifenservice-Werkstatt in Darmstadt mit professioneller Ausrüstung für Reifenwechsel, Reparaturen und Autoservice">
				<meta itemprop="contentUrl" content="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1.png' ) ); ?>">
				<meta itemprop="width" content="600">
				<meta itemprop="height" content="400">
			</div>
		</div>
	</div>
</section>

<!-- Services Section -->
<section class="services-section">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title"><?php esc_html_e( 'Unsere Dienstleistungen', 'urvena-fix' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Professioneller Service für Reifen und Fahrzeugpflege', 'urvena-fix' ); ?></p>
		</div>

		<!-- Main Service Categories Preview -->
		<div class="services-preview">
			<div class="service-preview-card">
				<div class="service-preview-icon">🔧</div>
				<h3 class="service-preview-title">Reifenwechsel</h3>
				<ul class="service-preview-list">
					<li>Saisonaler Reifenwechsel</li>
					<li>Präzises Auswuchten</li>
					<li>Umfassender Sicherheitscheck</li>
					<li>Professionelle Reifeneinlagerung</li>
				</ul>
				<a href="<?php echo esc_url( home_url( '/dienstleistungen/#reifenwechsel' ) ); ?>" class="service-preview-link">
					Alle Details →
				</a>
			</div>
			<div class="service-preview-card">
				<div class="service-preview-icon">🛢️</div>
				<h3 class="service-preview-title">Ölwechsel</h3>
				<ul class="service-preview-list">
					<li>Motorölwechsel mit Filterwechsel</li>
					<li>Getriebe- und Achsantriebsöl</li>
					<li>Servolenkungsöl-Service</li>
					<li>Umweltgerechte Entsorgung</li>
				</ul>
				<a href="<?php echo esc_url( home_url( '/dienstleistungen/#oelwechsel' ) ); ?>" class="service-preview-link">
					Alle Details →
				</a>
			</div>
			<div class="service-preview-card shop-preview-card">
				<div class="service-preview-icon">🛒</div>
				<h3 class="service-preview-title">Reifen & Zubehör</h3>
				<ul class="service-preview-list">
					<li>Premium Markenreifen</li>
					<li>Sommer-, Winter- & Ganzjahresreifen</li>
					<li>Felgen und Zubehör</li>
					<li>Online-Shop verfügbar</li>
				</ul>
				<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="service-preview-link shop-preview-link">
					Zum Shop →
				</a>
			</div>
		</div>

		<div class="services-grid">
			<?php
			$services = new WP_Query(
				array(
					'post_type'      => 'service',
					'posts_per_page' => 6,
					'orderby'        => 'meta_value_num',
					'meta_key'       => '_urvena_order',
					'order'          => 'ASC',
				)
			);

			if ( $services->have_posts() ) :
				while ( $services->have_posts() ) :
					$services->the_post();
					$icon        = get_post_meta( get_the_ID(), '_urvena_icon', true );
					$price_range = get_post_meta( get_the_ID(), '_urvena_price_range', true );
					?>
					<div class="service-card">
						<?php if ( $icon ) : ?>
							<div class="service-icon"><?php echo esc_html( $icon ); ?></div>
						<?php endif; ?>
						<h3 class="service-title"><?php the_title(); ?></h3>
						<div class="service-excerpt">
							<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
						</div>
						<?php if ( $price_range ) : ?>
							<div class="service-price"><?php echo esc_html( $price_range ); ?></div>
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
</section>

<!-- Appointment Booking Section -->
<section class="appointment-section" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 4rem 0; text-align: center;">
	<div class="container">
		<div class="appointment-content" style="max-width: 800px; margin: 0 auto;">
			<h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: white;">📅 Termin online buchen</h2>
			<p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9;">
				Sparen Sie Zeit und buchen Sie Ihren Termin bei URVENA FIX ganz einfach online. 
				Wählen Sie Ihren gewünschten Service und einen passenden Zeitpunkt – schnell und unkompliziert!
			</p>
			
			<div class="appointment-features" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
				<div class="feature-item" style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 8px; backdrop-filter: blur(10px);">
					<div style="font-size: 2rem; margin-bottom: 0.5rem;">⚡</div>
					<h3 style="margin-bottom: 0.5rem; color: white;">Schnell & Einfach</h3>
					<p style="margin: 0; opacity: 0.8; font-size: 0.9rem;">In nur wenigen Klicks zum Wunschtermin</p>
				</div>
				<div class="feature-item" style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 8px; backdrop-filter: blur(10px);">
					<div style="font-size: 2rem; margin-bottom: 0.5rem;">📧</div>
					<h3 style="margin-bottom: 0.5rem; color: white;">Sofortige Bestätigung</h3>
					<p style="margin: 0; opacity: 0.8; font-size: 0.9rem;">Automatische E-Mail-Bestätigung</p>
				</div>
				<div class="feature-item" style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 8px; backdrop-filter: blur(10px);">
					<div style="font-size: 2rem; margin-bottom: 0.5rem;">🕒</div>
					<h3 style="margin-bottom: 0.5rem; color: white;">24/7 Verfügbar</h3>
					<p style="margin: 0; opacity: 0.8; font-size: 0.9rem;">Buchen Sie rund um die Uhr</p>
				</div>
			</div>
			
			<div class="appointment-buttons" style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
				<a href="<?php echo esc_url( home_url( '/terminbuchung/' ) ); ?>" 
				   class="btn-appointment-main" 
				   style="background: white; color: #dc3545; padding: 1rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;">
					📅 Jetzt Termin buchen
				</a>
				
				<?php 
				// Show dashboard button only to logged-in users with proper Ultimate Member roles
				if ( is_user_logged_in() ) : 
					// Additional Ultimate Member check if plugin is active
					$show_dashboard = true;
					$user = wp_get_current_user();
					$user_id = get_current_user_id();
					
					if ( function_exists( 'um_user' ) && function_exists( 'um_is_user_approved' ) ) {
						// Check if user is approved in Ultimate Member
						$show_dashboard = um_is_user_approved( $user_id );
					}
					
					// Check for proper Ultimate Member roles
					$allowed_roles = array( 'administrator', 'um_admin', 'um_seller', 'um_customer', 'editor', 'subscriber' );
					$has_valid_role = array_intersect( $allowed_roles, $user->roles );
					
					if ( $show_dashboard && $has_valid_role ) :
						// Determine button text based on role
						$button_text = '📊 Termine verwalten';
						if ( in_array( 'administrator', $user->roles ) || in_array( 'um_admin', $user->roles ) ) {
							$button_text = '🔧 Admin Dashboard';
						} elseif ( in_array( 'um_seller', $user->roles ) ) {
							$button_text = '💼 Verkäufer Dashboard';
						}
				?>
				<a href="<?php echo esc_url( home_url( '/termine/' ) ); ?>" 
				   class="btn-appointment-secondary" 
				   style="background: transparent; color: white; padding: 1rem 2rem; border: 2px solid white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;">
					<?php echo esc_html( $button_text ); ?>
				</a>
				<?php 
					endif;
				else : 
					// Show login button for non-logged users
				?>
				<a href="<?php echo function_exists( 'um_get_core_page' ) ? esc_url( um_get_core_page( 'login' ) ) : esc_url( wp_login_url( home_url( '/termine/' ) ) ); ?>" 
				   class="btn-appointment-login" 
				   style="background: transparent; color: white; padding: 1rem 2rem; border: 2px solid white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;">
					🔐 Anmelden für Dashboard
				</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<style>
.btn-appointment-main:hover {
	transform: translateY(-3px) !important;
	box-shadow: 0 6px 20px rgba(0,0,0,0.3) !important;
	color: #dc3545 !important;
	text-decoration: none !important;
}

.btn-appointment-secondary:hover,
.btn-appointment-login:hover {
	background: white !important;
	color: #dc3545 !important;
	text-decoration: none !important;
	transform: translateY(-2px) !important;
}

@media (max-width: 768px) {
	.appointment-features {
		grid-template-columns: 1fr !important;
		gap: 1rem !important;
	}
	
	.appointment-buttons {
		flex-direction: column !important;
		align-items: center !important;
	}
	
	.btn-appointment-main,
	.btn-appointment-secondary {
		width: 100% !important;
		max-width: 300px !important;
		justify-content: center !important;
	}
}
</style>

<!-- About Section with Auto-Sliding Image Carousel -->
<section class="about-section" itemscope itemtype="https://schema.org/LocalBusiness">
	<div class="container">
		<div class="about-grid">
			<div class="about-image">
				<div class="about-carousel-container">
					<div class="about-carousel">
						<!-- Image 1 - Original Workshop -->
						<div class="carousel-slide active" data-slide="1">
							<img 
								src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1-1200x675.png' ) ); ?>" 
								srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1-300x200.png' ) ); ?> 300w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1-768x512.png' ) ); ?> 768w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1-1024x683.png' ) ); ?> 1024w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1-1200x675.png' ) ); ?> 1200w"
								sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 600px"
								alt="URVENA FIX Werkstatt - Moderne Reifenservice Ausstattung in Darmstadt"
								title="Professionelle Reifenservice Werkstatt URVENA FIX - Hochwertige Ausrüstung für erstklassigen Service"
								class="carousel-image"
								width="600" 
								height="400"
								loading="eager"
								itemscope 
								itemtype="https://schema.org/ImageObject"
								itemprop="image"
							/>
							<meta itemprop="name" content="URVENA FIX Werkstatt Innenansicht - Professionelle Ausrüstung">
							<meta itemprop="description" content="Moderne Werkstatt mit professioneller Ausrüstung für Reifenservice und Autoservice in Darmstadt. Spezialisiert auf Reifenwechsel, Reparaturen und Einlagerung.">
							<meta itemprop="contentUrl" content="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1-1200x675.png' ) ); ?>">
							<meta itemprop="width" content="600">
							<meta itemprop="height" content="400">
						</div>
						
						<!-- Image 2 - Professional Service -->
						<div class="carousel-slide" data-slide="2">
							<img 
								src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena--1200x675.png' ) ); ?>" 
								srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena--300x200.png' ) ); ?> 300w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena--768x512.png' ) ); ?> 768w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena--1024x683.png' ) ); ?> 1024w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena--1200x675.png' ) ); ?> 1200w"
								sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 600px"
								alt="URVENA Reifenservice - Professionelle Reifenmontage und Service in Darmstadt"
								title="URVENA Experte bei der Reifenmontage - Qualität und Präzision in jedem Handgriff"
								class="carousel-image"
								width="600" 
								height="400"
								loading="lazy"
								itemscope 
								itemtype="https://schema.org/ImageObject"
								itemprop="image"
							/>
							<meta itemprop="name" content="URVENA Reifenmontage Service - Fachkundige Beratung">
							<meta itemprop="description" content="Professionelle Reifenmontage und Service von erfahrenen Mechanikern in Darmstadt. Kompetente Beratung für Sommer- und Winterreifen.">
							<meta itemprop="contentUrl" content="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena--1200x675.png' ) ); ?>">
							<meta itemprop="width" content="600">
							<meta itemprop="height" content="400">
						</div>
						
						<!-- Image 3 - Workshop Equipment -->
						<div class="carousel-slide" data-slide="3">
							<img 
								src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3-1200x675.png' ) ); ?>" 
								srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3-300x200.png' ) ); ?> 300w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3-768x512.png' ) ); ?> 768w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3-1024x683.png' ) ); ?> 1024w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3-1200x675.png' ) ); ?> 1200w"
								sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 600px"
								alt="URVENA Werkstatt Ausrüstung - Moderne Technologie für professionellen Reifenservice"
								title="Hochmoderne Werkstattausrüstung für professionellen Reifenservice - Präzision und Effizienz"
								class="carousel-image"
								width="600" 
								height="400"
								loading="lazy"
								itemscope 
								itemtype="https://schema.org/ImageObject"
								itemprop="image"
							/>
							<meta itemprop="name" content="URVENA Werkstatt Technologie - Moderne Ausrüstung">
							<meta itemprop="description" content="Modernste Werkstattausrüstung und Technologie für professionellen Reifenservice. Achsvermessung, Wuchtung und Reparaturen auf höchstem Niveau.">
							<meta itemprop="contentUrl" content="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3-1200x675.png' ) ); ?>">
							<meta itemprop="width" content="600">
							<meta itemprop="height" content="400">
						</div>
						
						<!-- Image 4 - Service Excellence -->
						<div class="carousel-slide" data-slide="4">
							<img 
								src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena-2-1024x675.png' ) ); ?>" 
								srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena-2-200x300.png' ) ); ?> 200w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena-2-683x1024.png' ) ); ?> 683w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena-2-768x1152.png' ) ); ?> 768w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena-2-1024x675.png' ) ); ?> 1024w"
								sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 600px"
								alt="URVENA Reifenexperte - Fachkundige Beratung und erstklassiger Service"
								title="Kompetente Beratung und erstklassiger Service bei URVENA - Ihr Vertrauen ist unser Anspruch"
								class="carousel-image"
								width="600" 
								height="400"
								loading="lazy"
								itemscope 
								itemtype="https://schema.org/ImageObject"
								itemprop="image"
							/>
							<meta itemprop="name" content="URVENA Fachberatung - Kompetenter Kundenservice">
							<meta itemprop="description" content="Kompetente Fachberatung und erstklassiger Kundenservice für alle Reifenfragen. Persönliche Beratung zu Reifenwahl, Pflege und Sicherheit.">
							<meta itemprop="contentUrl" content="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena-2-1024x675.png' ) ); ?>">
							<meta itemprop="width" content="600">
							<meta itemprop="height" content="400">
						</div>
						
						<!-- Image 5 - Complete Service -->
						<div class="carousel-slide" data-slide="5">
							<img 
								src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3.png' ) ); ?>" 
								srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3-300x200.png' ) ); ?> 300w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3-768x512.png' ) ); ?> 768w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3-1024x683.png' ) ); ?> 1024w,
										<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3-1200x675.png' ) ); ?> 1200w"
								sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 600px"
								alt="URVENA Komplettservice - Von Beratung bis Montage alles aus einer Hand in Darmstadt"
								title="Kompletter Reifenservice von der Beratung bis zur professionellen Montage - URVENA FIX"
								class="carousel-image"
								width="600" 
								height="400"
								loading="lazy"
								itemscope 
								itemtype="https://schema.org/ImageObject"
								itemprop="image"
							/>
							<meta itemprop="name" content="URVENA Komplettservice - Rundum-Betreuung">
							<meta itemprop="description" content="Umfassender Reifenservice von der ersten Beratung bis zur finalen Montage. Komplette Betreuung und Service aus einer Hand in Darmstadt.">
							<meta itemprop="contentUrl" content="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3.png' ) ); ?>">
							<meta itemprop="width" content="600">
							<meta itemprop="height" content="400">
						</div>
					</div>
					
					<!-- SEO-optimized Image Gallery Schema -->
					<script type="application/ld+json">
					{
						"@context": "https://schema.org",
						"@type": "ImageGallery",
						"name": "URVENA FIX Werkstatt Bildergalerie",
						"description": "Professionelle Bildergalerie der URVENA FIX Werkstatt in Darmstadt. Moderne Ausrüstung, fachkundige Beratung und erstklassiger Reifenservice.",
						"about": {
							"@type": "LocalBusiness",
							"name": "URVENA FIX",
							"description": "Professioneller Reifenservice in Darmstadt"
						},
						"image": [
							{
								"@type": "ImageObject",
								"name": "URVENA FIX Werkstatt Innenansicht",
								"description": "Moderne Werkstatt mit professioneller Ausrüstung für Reifenservice und Autoservice",
								"contentUrl": "<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1-1200x675.png' ) ); ?>",
								"width": "600",
								"height": "400",
								"encodingFormat": "image/png"
							},
							{
								"@type": "ImageObject", 
								"name": "URVENA Reifenmontage Service",
								"description": "Professionelle Reifenmontage und Service von erfahrenen Mechanikern",
								"contentUrl": "<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena--1200x675.png' ) ); ?>",
								"width": "600", 
								"height": "400",
								"encodingFormat": "image/png"
							},
							{
								"@type": "ImageObject",
								"name": "URVENA Werkstatt Technologie", 
								"description": "Modernste Werkstattausrüstung und Technologie für professionellen Reifenservice",
								"contentUrl": "<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3-1200x675.png' ) ); ?>",
								"width": "600",
								"height": "400", 
								"encodingFormat": "image/png"
							},
							{
								"@type": "ImageObject",
								"name": "URVENA Fachberatung",
								"description": "Kompetente Fachberatung und erstklassiger Kundenservice für alle Reifenfragen", 
								"contentUrl": "<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena-2-1024x675.png' ) ); ?>",
								"width": "600",
								"height": "400",
								"encodingFormat": "image/png"
							},
							{
								"@type": "ImageObject",
								"name": "URVENA Komplettservice",
								"description": "Umfassender Reifenservice von der ersten Beratung bis zur finalen Montage",
								"contentUrl": "<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-3.png' ) ); ?>", 
								"width": "600",
								"height": "400",
								"encodingFormat": "image/png"
							}
						]
					}
					</script>
					
					<!-- Carousel Indicators -->
					<div class="carousel-indicators">
						<button class="indicator active" data-slide="1" aria-label="Bild 1"></button>
						<button class="indicator" data-slide="2" aria-label="Bild 2"></button>
						<button class="indicator" data-slide="3" aria-label="Bild 3"></button>
						<button class="indicator" data-slide="4" aria-label="Bild 4"></button>
						<button class="indicator" data-slide="5" aria-label="Bild 5"></button>
					</div>
					
					<!-- Carousel Navigation -->
					<button class="carousel-nav prev" aria-label="Vorheriges Bild">‹</button>
					<button class="carousel-nav next" aria-label="Nächstes Bild">›</button>
				</div>
			</div>
			<div class="about-content" itemprop="description">
				<h2 class="about-title" itemprop="name"><?php esc_html_e( 'Ihr zuverlässiger Partner in Darmstadt', 'urvena-fix' ); ?></h2>
				<p class="about-text">
					<?php
					esc_html_e(
						'Bei URVENA FIX steht Qualität an erster Stelle. Mit jahrelanger Erfahrung im Bereich Reifenservice und Autoservice bieten wir Ihnen professionelle Lösungen für Ihr Fahrzeug. Unsere moderne Werkstatt in Darmstadt ist mit neuester Technologie ausgestattet.',
						'urvena-fix'
					);
					?>
				</p>
				<ul class="about-features" itemscope itemtype="https://schema.org/ItemList">
					<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
						<span class="feature-icon">✓</span>
						<span itemprop="name"><?php esc_html_e( 'Erfahrene und zertifizierte Fachkräfte', 'urvena-fix' ); ?></span>
						<meta itemprop="position" content="1">
					</li>
					<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
						<span class="feature-icon">✓</span>
						<span itemprop="name"><?php esc_html_e( 'Modernste Ausrüstung und Technologie', 'urvena-fix' ); ?></span>
						<meta itemprop="position" content="2">
					</li>
					</li>
					<li>
						<span class="feature-icon">✓</span>
						<span><?php esc_html_e( 'Faire und transparente Preise', 'urvena-fix' ); ?></span>
					</li>
					<li>
						<span class="feature-icon">✓</span>
						<span><?php esc_html_e( 'Schnelle und zuverlässige Terminvergabe', 'urvena-fix' ); ?></span>
					</li>
				</ul>
				<a href="<?php echo esc_url( home_url( '/ueber-uns/' ) ); ?>" class="btn btn-primary">
					<?php esc_html_e( 'Mehr über uns', 'urvena-fix' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>

<!-- CTA Section -->
<section class="cta-section">
	<div class="container">
		<div class="cta-content">
			<h2 class="cta-title"><?php esc_html_e( 'Bereit für einen Termin?', 'urvena-fix' ); ?></h2>
			<p class="cta-text">
				<?php
				esc_html_e(
					'Kontaktieren Sie uns noch heute und vereinbaren Sie einen Termin. Wir freuen uns auf Sie!',
					'urvena-fix'
				);
				?>
			</p>
			<div class="cta-buttons">
				<?php
				$phone = get_theme_mod( 'urvena_phone', '+49 6151 123456' );
				?>
				<a href="tel:<?php echo esc_attr( urvena_format_phone_link( $phone ) ); ?>" class="btn btn-white">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M18.3 14.5C17.1 14.5 15.9 14.3 14.8 13.9C14.4 13.8 13.9 13.9 13.6 14.2L11.4 16.4C8.6 15 5 11.4 3.6 8.6L5.8 6.4C6.1 6.1 6.2 5.6 6.1 5.2C5.7 4.1 5.5 2.9 5.5 1.7C5.5 1 4.9 0.5 4.2 0.5H1.7C1 0.5 0.5 1 0.5 1.7C0.5 11.5 8.5 19.5 18.3 19.5C19 19.5 19.5 19 19.5 18.3V15.8C19.5 15.1 19 14.5 18.3 14.5Z" fill="currentColor"/>
					</svg>
					<?php echo esc_html( $phone ); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>" class="btn btn-outline-white">
					<?php esc_html_e( 'Kontaktformular', 'urvena-fix' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();

