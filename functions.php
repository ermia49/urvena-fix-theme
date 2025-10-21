<?php
/**
 * URVENA FIX Theme Functions
 *
 * @package URVENA_Fix
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Theme version with timestamp for cache busting
 */
define( 'URVENA_VERSION', '1.2.0-' . time() );

/**
 * Theme setup
 */
function urvena_setup() {
	// Make theme available for translation.
	load_theme_textdomain( 'urvena-fix', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 675, true );

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary' => __( 'Hauptmenü', 'urvena-fix' ),
			'footer'  => __( 'Footer-Menü', 'urvena-fix' ),
		)
	);

	// Switch default core markup to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 250,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', 'urvena_setup' );

/**
 * Set the content width in pixels.
 */
function urvena_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'urvena_content_width', 1200 );
}
add_action( 'after_setup_theme', 'urvena_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function urvena_scripts() {
	// Main stylesheet - using style.css now includes all styles
	wp_enqueue_style(
		'urvena-style',
		get_stylesheet_uri(),
		array(),
		URVENA_VERSION
	);

	// Also enqueue the main CSS as backup
	wp_enqueue_style(
		'urvena-main-style',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		URVENA_VERSION . '-' . time()
	);

	// Main JavaScript.
	wp_enqueue_script(
		'urvena-script',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		URVENA_VERSION,
		true
	);
	
	// Appointments JavaScript.
	wp_enqueue_script(
		'urvena-appointments',
		get_template_directory_uri() . '/assets/js/appointments.js',
		array( 'jquery' ),
		URVENA_VERSION,
		true
	);
	
	// Carousel JavaScript for auto-sliding image gallery.
	wp_enqueue_script(
		'urvena-carousel',
		get_template_directory_uri() . '/assets/js/carousel.js',
		array(),
		URVENA_VERSION,
		true
	);

	// Load comment reply script if needed.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'urvena_scripts' );

// Inline styles function removed for troubleshooting

// Simplified for troubleshooting - optimization functions removed temporarily

/**
 * Debug function to confirm theme is loading
 */
function urvena_debug_theme_load() {
	$debug_info = sprintf(
		'<!-- URVENA FIX Theme v1.1.0 Enhanced - Template: %s, Theme Dir: %s -->',
		get_template_directory_uri(),
		get_template_directory()
	);
	echo $debug_info;
}
add_action( 'wp_head', 'urvena_debug_theme_load' );

/**
 * Ensure proper theme activation
 */
function urvena_after_switch_theme() {
	// Flush rewrite rules after theme activation
	flush_rewrite_rules();
	
	// Clear any cached data
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
	}
}
add_action( 'after_switch_theme', 'urvena_after_switch_theme' );

/**
 * Register widget areas.
 */
function urvena_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'urvena-fix' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Wird auf Blogseiten angezeigt.', 'urvena-fix' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 1', 'urvena-fix' ),
			'id'            => 'footer-1',
			'description'   => __( 'Erste Footer-Spalte.', 'urvena-fix' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 2', 'urvena-fix' ),
			'id'            => 'footer-2',
			'description'   => __( 'Zweite Footer-Spalte.', 'urvena-fix' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 3', 'urvena-fix' ),
			'id'            => 'footer-3',
			'description'   => __( 'Dritte Footer-Spalte.', 'urvena-fix' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'urvena_widgets_init' );

/**
 * Add custom body classes
 */
function urvena_body_classes( $classes ) {
	// Add class for custom styling
	$classes[] = 'urvena-enhanced';
	
	// Add mobile detection
	if ( wp_is_mobile() ) {
		$classes[] = 'is-mobile';
	}
	
	return $classes;
}
add_filter( 'body_class', 'urvena_body_classes' );

/**
 * Include additional files.
 */
require get_template_directory() . '/inc/custom-post-types.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/schema-markup.php';
require get_template_directory() . '/inc/contact-handler.php';
require get_template_directory() . '/inc/appointments.php';
require get_template_directory() . '/inc/quick-booking-widget.php';
require get_template_directory() . '/inc/appointment-setup.php';
require get_template_directory() . '/inc/security-redirects.php';
require get_template_directory() . '/inc/cache-manager.php';

/**
 * Sanitize and escape helper functions.
 */

/**
 * Sanitize phone number.
 *
 * @param string $phone Phone number.
 * @return string Sanitized phone number.
 */
function urvena_sanitize_phone( $phone ) {
	return preg_replace( '/[^0-9+\-\(\)\s]/', '', $phone );
}

/**
 * Format phone number for tel: link.
 *
 * @param string $phone Phone number.
 * @return string Formatted phone number.
 */
function urvena_format_phone_link( $phone ) {
	return preg_replace( '/[^0-9+]/', '', $phone );
}

// Color helper functions removed for troubleshooting

