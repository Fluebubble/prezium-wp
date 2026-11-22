<?php
/**
 * Anfragen ("Preis anfragen" und Kontaktformular): speichern, im Admin anzeigen, per E-Mail melden.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'prezium_inquiry', array(
		'labels'       => array(
			'name'          => __( 'Anfragen', 'prezium' ),
			'singular_name' => __( 'Anfrage', 'prezium' ),
			'menu_name'     => __( 'Anfragen', 'prezium' ),
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-email-alt',
		'menu_position' => 25,
		'supports'     => array( 'title' ),
		'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap' => true,
	) );
} );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'prezium_inquiry_data', __( 'Anfrage', 'prezium' ), function ( $post ) {
		$rows = array(
			__( 'Name', 'prezium' )     => get_post_meta( $post->ID, '_name', true ),
			__( 'E-Mail', 'prezium' )   => get_post_meta( $post->ID, '_email', true ),
			__( 'Telefon', 'prezium' )  => get_post_meta( $post->ID, '_phone', true ),
			__( 'Produkt', 'prezium' )  => get_post_meta( $post->ID, '_product', true ),
			__( 'Nachricht', 'prezium' ) => get_post_meta( $post->ID, '_message', true ),
		);
		echo '<table class="form-table" role="presentation">';
		foreach ( $rows as $label => $val ) {
			echo '<tr><th>' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( $val ) ) . '</td></tr>';
		}
		echo '</table>';
	}, 'prezium_inquiry', 'normal', 'high' );
} );

add_filter( 'manage_prezium_inquiry_posts_columns', function () {
	return array(
		'cb'        => '<input type="checkbox">',
		'title'     => __( 'Betreff', 'prezium' ),
		'prez_mail' => __( 'E-Mail', 'prezium' ),
		'prez_prod' => __( 'Produkt', 'prezium' ),
		'date'      => __( 'Datum', 'prezium' ),
	);
} );

add_action( 'manage_prezium_inquiry_posts_custom_column', function ( $col, $post_id ) {
	if ( 'prez_mail' === $col ) {
		echo esc_html( get_post_meta( $post_id, '_email', true ) );
	} elseif ( 'prez_prod' === $col ) {
		echo esc_html( get_post_meta( $post_id, '_product', true ) );
	}
}, 10, 2 );

/** AJAX-Handler (für Besucher und Angemeldete). */
add_action( 'wp_ajax_nopriv_prezium_inquiry', 'prezium_handle_inquiry' );
add_action( 'wp_ajax_prezium_inquiry', 'prezium_handle_inquiry' );

function prezium_handle_inquiry() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'prezium_inquiry' ) ) {
		wp_send_json_error( array( 'message' => __( 'Sitzung abgelaufen. Bitte laden Sie die Seite neu.', 'prezium' ) ), 403 );
	}
	// Honeypot gegen Spam: dieses Feld bleibt für Menschen leer.
	if ( ! empty( $_POST['website'] ) ) {
		wp_send_json_success();
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
	$product = sanitize_text_field( wp_unslash( $_POST['product'] ?? '' ) );
	$consent = ! empty( $_POST['consent'] );

	if ( '' === $name || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Bitte geben Sie Name und eine gültige E-Mail-Adresse an.', 'prezium' ) ), 422 );
	}
	if ( ! $consent ) {
		wp_send_json_error( array( 'message' => __( 'Bitte stimmen Sie der Datenverarbeitung zu.', 'prezium' ) ), 422 );
	}

	$subject = $product
		? sprintf( /* translators: %s: product */ __( 'Preisanfrage: %s', 'prezium' ), $product )
		: __( 'Neue Kontaktanfrage', 'prezium' );

	$id = wp_insert_post( array(
		'post_type'   => 'prezium_inquiry',
		'post_status' => 'publish',
		'post_title'  => wp_strip_all_tags( $subject . ' – ' . $name ),
		'meta_input'  => array(
			'_name'    => $name,
			'_email'   => $email,
			'_phone'   => $phone,
			'_message' => $message,
			'_product' => $product,
		),
	) );

	if ( is_wp_error( $id ) || ! $id ) {
		wp_send_json_error( array( 'message' => __( 'Die Anfrage konnte nicht gespeichert werden. Bitte versuchen Sie es später erneut.', 'prezium' ) ), 500 );
	}

	$body = sprintf(
		"Name: %s\nE-Mail: %s\nTelefon: %s\nProdukt: %s\n\n%s\n\n%s",
		$name,
		$email,
		$phone,
		$product,
		$message,
		admin_url( 'post.php?post=' . $id . '&action=edit' )
	);
	wp_mail(
		get_option( 'admin_email' ),
		$subject,
		$body,
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);

	wp_send_json_success( array( 'message' => __( 'Vielen Dank. Wir melden uns innerhalb eines Werktags.', 'prezium' ) ) );
}
