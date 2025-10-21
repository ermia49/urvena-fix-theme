<?php
/**
 * Custom Post Types Registration
 *
 * @package URVENA_Fix
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Services Custom Post Type
 */
function urvena_register_services_cpt() {
	$labels = array(
		'name'                  => _x( 'Dienstleistungen', 'Post Type General Name', 'urvena-fix' ),
		'singular_name'         => _x( 'Dienstleistung', 'Post Type Singular Name', 'urvena-fix' ),
		'menu_name'             => __( 'Dienstleistungen', 'urvena-fix' ),
		'name_admin_bar'        => __( 'Dienstleistung', 'urvena-fix' ),
		'archives'              => __( 'Dienstleistungs-Archive', 'urvena-fix' ),
		'attributes'            => __( 'Dienstleistungs-Attribute', 'urvena-fix' ),
		'parent_item_colon'     => __( 'Übergeordnete Dienstleistung:', 'urvena-fix' ),
		'all_items'             => __( 'Alle Dienstleistungen', 'urvena-fix' ),
		'add_new_item'          => __( 'Neue Dienstleistung hinzufügen', 'urvena-fix' ),
		'add_new'               => __( 'Hinzufügen', 'urvena-fix' ),
		'new_item'              => __( 'Neue Dienstleistung', 'urvena-fix' ),
		'edit_item'             => __( 'Dienstleistung bearbeiten', 'urvena-fix' ),
		'update_item'           => __( 'Dienstleistung aktualisieren', 'urvena-fix' ),
		'view_item'             => __( 'Dienstleistung ansehen', 'urvena-fix' ),
		'view_items'            => __( 'Dienstleistungen ansehen', 'urvena-fix' ),
		'search_items'          => __( 'Dienstleistung suchen', 'urvena-fix' ),
		'not_found'             => __( 'Nicht gefunden', 'urvena-fix' ),
		'not_found_in_trash'    => __( 'Nicht im Papierkorb gefunden', 'urvena-fix' ),
		'featured_image'        => __( 'Vorschaubild', 'urvena-fix' ),
		'set_featured_image'    => __( 'Vorschaubild festlegen', 'urvena-fix' ),
		'remove_featured_image' => __( 'Vorschaubild entfernen', 'urvena-fix' ),
		'use_featured_image'    => __( 'Als Vorschaubild verwenden', 'urvena-fix' ),
		'insert_into_item'      => __( 'In Dienstleistung einfügen', 'urvena-fix' ),
		'uploaded_to_this_item' => __( 'Zu dieser Dienstleistung hochgeladen', 'urvena-fix' ),
		'items_list'            => __( 'Dienstleistungsliste', 'urvena-fix' ),
		'items_list_navigation' => __( 'Dienstleistungslisten-Navigation', 'urvena-fix' ),
		'filter_items_list'     => __( 'Dienstleistungsliste filtern', 'urvena-fix' ),
	);

	$args = array(
		'label'               => __( 'Dienstleistung', 'urvena-fix' ),
		'description'         => __( 'URVENA FIX Dienstleistungen', 'urvena-fix' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-admin-tools',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
		'show_in_rest'        => true,
		'rewrite'             => array( 'slug' => 'service' ),
	);

	register_post_type( 'service', $args );
}
add_action( 'init', 'urvena_register_services_cpt', 0 );

/**
 * Add custom meta boxes for services
 */
function urvena_add_service_meta_boxes() {
	add_meta_box(
		'urvena_service_details',
		__( 'Service-Details', 'urvena-fix' ),
		'urvena_service_details_callback',
		'service',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'urvena_add_service_meta_boxes' );

/**
 * Service details meta box callback
 *
 * @param WP_Post $post Current post object.
 */
function urvena_service_details_callback( $post ) {
	wp_nonce_field( 'urvena_save_service_details', 'urvena_service_details_nonce' );

	$price_range = get_post_meta( $post->ID, '_urvena_price_range', true );
	$icon        = get_post_meta( $post->ID, '_urvena_icon', true );
	$order       = get_post_meta( $post->ID, '_urvena_order', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="urvena_price_range"><?php esc_html_e( 'Preisbereich', 'urvena-fix' ); ?></label></th>
			<td>
				<input type="text" id="urvena_price_range" name="urvena_price_range" value="<?php echo esc_attr( $price_range ); ?>" class="regular-text">
				<p class="description"><?php esc_html_e( 'z.B. "ab 50€" oder "75€ - 150€"', 'urvena-fix' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="urvena_icon"><?php esc_html_e( 'Icon (Emoji)', 'urvena-fix' ); ?></label></th>
			<td>
				<input type="text" id="urvena_icon" name="urvena_icon" value="<?php echo esc_attr( $icon ); ?>" class="regular-text">
				<p class="description"><?php esc_html_e( 'z.B. 🚗 🔧 ⚙️ 🛞', 'urvena-fix' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="urvena_order"><?php esc_html_e( 'Sortierreihenfolge', 'urvena-fix' ); ?></label></th>
			<td>
				<input type="number" id="urvena_order" name="urvena_order" value="<?php echo esc_attr( $order ); ?>" class="small-text">
				<p class="description"><?php esc_html_e( 'Niedrigere Zahlen werden zuerst angezeigt.', 'urvena-fix' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save service meta box data
 *
 * @param int $post_id Post ID.
 */
function urvena_save_service_details( $post_id ) {
	// Check nonce.
	if ( ! isset( $_POST['urvena_service_details_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( $_POST['urvena_service_details_nonce'] ), 'urvena_save_service_details' ) ) {
		return;
	}

	// Check autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save price range.
	if ( isset( $_POST['urvena_price_range'] ) ) {
		update_post_meta(
			$post_id,
			'_urvena_price_range',
			sanitize_text_field( wp_unslash( $_POST['urvena_price_range'] ) )
		);
	}

	// Save icon.
	if ( isset( $_POST['urvena_icon'] ) ) {
		update_post_meta(
			$post_id,
			'_urvena_icon',
			sanitize_text_field( wp_unslash( $_POST['urvena_icon'] ) )
		);
	}

	// Save order.
	if ( isset( $_POST['urvena_order'] ) ) {
		update_post_meta(
			$post_id,
			'_urvena_order',
			absint( $_POST['urvena_order'] )
		);
	}
}
add_action( 'save_post_service', 'urvena_save_service_details' );

