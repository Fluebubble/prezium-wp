<?php
/**
 * Produktfelder im Admin: Artikelnummer, Material, Maße, Ausführung, Dokumente.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Textfelder: meta key => Label. */
function prezium_product_fields() {
	return array(
		'_prez_sku'        => __( 'Artikelnummer', 'prezium' ),
		'_prez_material'   => __( 'Material', 'prezium' ),
		'_prez_dimensions' => __( 'Maße', 'prezium' ),
		'_prez_finish'     => __( 'Ausführung / Oberfläche', 'prezium' ),
	);
}

/** Dokumente: meta key => Label. Gespeichert wird die URL aus der Mediathek. */
function prezium_product_documents() {
	return array(
		'_prez_doc_catalog'  => __( 'Katalog (PDF)', 'prezium' ),
		'_prez_doc_price'    => __( 'Preisliste', 'prezium' ),
		'_prez_doc_install'  => __( 'Montageanleitung', 'prezium' ),
		'_prez_doc_cad'      => __( 'CAD/BIM-Modell', 'prezium' ),
	);
}

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'prezium_product_data', __( 'Produktdaten', 'prezium' ), 'prezium_render_product_box', 'prezium_product', 'normal', 'high' );
	add_meta_box( 'prezium_product_docs', __( 'Dokumente für Fachkunden', 'prezium' ), 'prezium_render_docs_box', 'prezium_product', 'normal', 'default' );
} );

function prezium_render_product_box( $post ) {
	wp_nonce_field( 'prezium_save_product', 'prezium_product_nonce' );
	echo '<table class="form-table" role="presentation">';
	foreach ( prezium_product_fields() as $key => $label ) {
		printf(
			'<tr><th><label for="%1$s">%2$s</label></th><td><input type="text" class="regular-text" id="%1$s" name="%1$s" value="%3$s"></td></tr>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( get_post_meta( $post->ID, $key, true ) )
		);
	}
	$cur = prezium_label( $post->ID );
	echo '<tr><th><label for="_prez_label">' . esc_html__( 'Kennzeichnung', 'prezium' ) . '</label></th><td><select id="_prez_label" name="_prez_label">';
	foreach ( array( '' => '–', 'TEST' => 'TEST', 'SERGO' => 'SERGO', 'DEMOPROJECT' => 'DEMOPROJECT' ) as $v => $t ) {
		printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $v ), selected( $cur, $v, false ), esc_html( $t ) );
	}
	echo '</select></td></tr>';
	echo '</table>';
}

function prezium_render_docs_box( $post ) {
	echo '<p class="description">' . esc_html__( 'Datei aus der Mediathek wählen oder eine URL einfügen. Sichtbar nur für angemeldete Fachkunden.', 'prezium' ) . '</p>';
	echo '<table class="form-table" role="presentation">';
	foreach ( prezium_product_documents() as $key => $label ) {
		printf(
			'<tr><th><label for="%1$s">%2$s</label></th><td><input type="url" class="regular-text" id="%1$s" name="%1$s" value="%3$s"> <button type="button" class="button prezium-pick" data-target="%1$s">%4$s</button></td></tr>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_url( get_post_meta( $post->ID, $key, true ) ),
			esc_html__( 'Mediathek', 'prezium' )
		);
	}
	echo '</table>';
}

add_action( 'save_post_prezium_product', function ( $post_id ) {
	if ( ! isset( $_POST['prezium_product_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prezium_product_nonce'] ) ), 'prezium_save_product' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array_keys( prezium_product_fields() ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	if ( isset( $_POST['_prez_label'] ) ) {
		$lab = sanitize_text_field( wp_unslash( $_POST['_prez_label'] ) );
		update_post_meta( $post_id, '_prez_label', in_array( $lab, array( 'TEST', 'SERGO', 'DEMOPROJECT' ), true ) ? $lab : '' );
		delete_post_meta( $post_id, '_prez_is_test' );
	}
	foreach ( array_keys( prezium_product_documents() ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, esc_url_raw( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
} );

/** Mediathek-Auswahl im Produkt-Editor. */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'prezium_product' !== $screen->post_type ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'prezium-admin', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery' ), wp_get_theme()->get( 'Version' ), true );
} );

/** Spalten in der Produktliste. */
add_filter( 'manage_prezium_product_posts_columns', function ( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['prez_sku']      = __( 'Artikel', 'prezium' );
			$new['prez_bereich']  = __( 'Bereich', 'prezium' );
			$new['prez_marke']    = __( 'Marke', 'prezium' );
		}
	}
	return $new;
} );

add_action( 'manage_prezium_product_posts_custom_column', function ( $col, $post_id ) {
	if ( 'prez_sku' === $col ) {
		echo esc_html( get_post_meta( $post_id, '_prez_sku', true ) );
		$lab = prezium_label( $post_id );
		if ( $lab ) {
			echo ' <strong style="color:#b32d2e">[' . esc_html( $lab ) . ']</strong>';
		}
	} elseif ( 'prez_bereich' === $col || 'prez_marke' === $col ) {
		$tax   = 'prez_bereich' === $col ? 'prezium_bereich' : 'prezium_marke';
		$terms = get_the_terms( $post_id, $tax );
		echo $terms && ! is_wp_error( $terms ) ? esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ) : '&ndash;';
	}
}, 10, 2 );

/** Katalogfilter: Admin-Liste nach Bereich filtern. */
add_action( 'restrict_manage_posts', function ( $post_type ) {
	if ( 'prezium_product' !== $post_type ) {
		return;
	}
	wp_dropdown_categories( array(
		'taxonomy'        => 'prezium_bereich',
		'name'            => 'prezium_bereich',
		'show_option_all' => __( 'Alle Bereiche', 'prezium' ),
		'hide_empty'      => false,
		'hierarchical'    => true,
		'value_field'     => 'slug',
		'selected'        => isset( $_GET['prezium_bereich'] ) ? sanitize_text_field( wp_unslash( $_GET['prezium_bereich'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
	) );
} );
