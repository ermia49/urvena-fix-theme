<?php
/**
 * Header Template
 *
 * @package URVENA_Fix
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	
	<!-- German SEO Meta Tags -->
	<meta name="description" content="URVENA FIX - Professioneller Reifenservice in Darmstadt. Reifenwechsel, Reparaturen, Einlagerung und Autoservice. Schnelle Terminbuchung online. Faire Preise, erfahrene Mechaniker.">
	<meta name="keywords" content="Reifenservice Darmstadt, Reifenwechsel, Reifenreparatur, Autoservice, Reifen einlagern, Achsvermessung, Wuchtung, KFZ Werkstatt Darmstadt, Sommerreifen, Winterreifen">
	<meta name="author" content="URVENA FIX">
	<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
	<meta name="language" content="de">
	<meta name="geo.region" content="DE-HE">
	<meta name="geo.placename" content="Darmstadt">
	<meta name="geo.position" content="49.8728;8.6512">
	<meta name="ICBM" content="49.8728, 8.6512">
	
	<!-- Open Graph German -->
	<meta property="og:locale" content="de_DE">
	<meta property="og:type" content="website">
	<meta property="og:title" content="URVENA FIX - Reifenservice Darmstadt | Online Termin buchen">
	<meta property="og:description" content="Professioneller Reifenservice in Darmstadt. Reifenwechsel, Reparaturen & Autoservice. Jetzt online Termin buchen! ✓ Faire Preise ✓ Erfahrene Mechaniker">
	<meta property="og:url" content="<?php echo esc_url( home_url() ); ?>">
	<meta property="og:site_name" content="URVENA FIX">
	<meta property="og:image" content="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1.png' ) ); ?>">
	<meta property="og:image:width" content="600">
	<meta property="og:image:height" content="400">
	<meta property="og:image:alt" content="URVENA FIX Reifenservice Werkstatt in Darmstadt">
	
	<!-- Twitter Card German -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="URVENA FIX - Reifenservice Darmstadt">
	<meta name="twitter:description" content="Professioneller Reifenservice in Darmstadt. Online Termin buchen für Reifenwechsel, Reparaturen & mehr.">
	<meta name="twitter:image" content="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1.png' ) ); ?>">
	
	<!-- Schema.org German Business Data -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "AutomotiveBusiness",
		"name": "URVENA-FIX",
		"alternateName": "URVENA-FIX Reifenservice",
		"description": "Professioneller Reifenservice und Autoservice in Darmstadt. Spezialist für Reifenwechsel, Reparaturen, Einlagerung und KFZ-Service.",
		"url": "<?php echo esc_url( home_url() ); ?>",
		"telephone": "+49 6151 123456",
		"email": "info@urvena-fix.de",
		"address": {
			"@type": "PostalAddress",
			"streetAddress": "Musterstraße 123",
			"addressLocality": "Darmstadt",
			"postalCode": "64283",
			"addressRegion": "Hessen",
			"addressCountry": "DE"
		},
		"geo": {
			"@type": "GeoCoordinates",
			"latitude": 49.8728,
			"longitude": 8.6512
		},
		"openingHours": [
			"Mo-Fr 08:00-17:00",
			"Sa 08:00-14:00"
		],
		"priceRange": "€€",
		"paymentAccepted": ["Cash", "Credit Card", "Debit Card"],
		"currenciesAccepted": "EUR",
		"areaServed": {
			"@type": "City",
			"name": "Darmstadt"
		},
		"serviceType": [
			"Reifenwechsel",
			"Reifenreparatur", 
			"Reifeneinlagerung",
			"Achsvermessung",
			"Radwuchtung",
			"Autoservice"
		],
		"image": "<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/Urvena1.png' ) ); ?>",
		"logo": {
			"@type": "ImageObject",
			"url": "<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-fix-logo.svg' ) ); ?>",
			"width": 280,
			"height": 70,
			"caption": "URVENA-FIX - Reifenservice Darmstadt SVG Logo"
		}
	}
	</script>
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Zum Inhalt springen', 'urvena-fix' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="header-container">
			<div class="site-branding">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo-link" itemscope itemtype="https://schema.org/Organization">
					<svg class="site-logo" width="260" height="70" viewBox="0 0 260 70" xmlns="http://www.w3.org/2000/svg" 
						role="img" 
						aria-label="URVENA FIX Logo - Professioneller Reifenservice Darmstadt"
						itemprop="logo"
						itemscope 
						itemtype="https://schema.org/ImageObject">
						<title>URVENA FIX - Ihr Reifenexperte in Darmstadt</title>
						<desc>Professionelles Logo von URVENA FIX Reifenservice in Darmstadt</desc>
						
						<!-- URVENA FIX Text with proper spacing and underline -->
						<g transform="translate(15, 0)">
							<!-- Main Brand Text with proper spacing -->
							<text x="0" y="35" font-family="Arial Black, sans-serif" font-size="32" font-weight="900" fill="#000">
								URVENA
							</text>
							<text x="135" y="35" font-family="Arial Black, sans-serif" font-size="32" font-weight="900" fill="#dc2626">
								FIX
							</text>
							
							<!-- Underline spanning both words -->
							<rect x="0" y="42" width="185" height="3" fill="#dc2626"/>
							<rect x="0" y="46" width="185" height="1" fill="#b91c1c"/>
						</g>
					</svg>
					<meta itemprop="name" content="URVENA-FIX Logo">
					<meta itemprop="description" content="Offizielles Logo von URVENA-FIX - Reifenservice und Autoservice in Darmstadt">
					<meta itemprop="contentUrl" content="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/urvena-reifen-logo.svg' ) ); ?>">
					<meta itemprop="width" content="280">
					<meta itemprop="height" content="70">
				</a>
			</div>

			<nav id="site-navigation" class="main-navigation">
				<!-- Desktop Menu Only -->
				<div class="desktop-menu">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'nav-menu',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
					?>
				</div>
			</nav>

			<div class="header-cta">
				<a href="/terminbuchung" class="header-appointment-btn">📅 <span>Termin buchen</span></a>
				
				<?php
				$phone = get_theme_mod( 'urvena_phone', '+49 6151 123456' );
				if ( $phone ) {
					?>
					<a href="tel:<?php echo esc_attr( urvena_format_phone_link( $phone ) ); ?>" class="header-phone">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M18.3 14.5C17.1 14.5 15.9 14.3 14.8 13.9C14.4 13.8 13.9 13.9 13.6 14.2L11.4 16.4C8.6 15 5 11.4 3.6 8.6L5.8 6.4C6.1 6.1 6.2 5.6 6.1 5.2C5.7 4.1 5.5 2.9 5.5 1.7C5.5 1 4.9 0.5 4.2 0.5H1.7C1 0.5 0.5 1 0.5 1.7C0.5 11.5 8.5 19.5 18.3 19.5C19 19.5 19.5 19 19.5 18.3V15.8C19.5 15.1 19 14.5 18.3 14.5Z" fill="currentColor"/>
						</svg>
						<span><?php echo esc_html( $phone ); ?></span>
					</a>
					<?php
				}
				?>
			</div>
		</div>
	</header>

	<main id="primary" class="site-main">

