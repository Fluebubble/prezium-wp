<?php
/**
 * Demo-Daten: Admin-Seite "Produkte → Demo-Daten" legt Testprodukte (Marke TEST),
 * Marken, Seiten und Menüs per Knopfdruck an. Nur für Administratoren, mit Nonce.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', function () {
	add_submenu_page(
		'edit.php?post_type=prezium_product',
		__( 'Demo-Daten', 'prezium' ),
		__( 'Demo-Daten', 'prezium' ),
		'manage_options',
		'prezium-demo',
		'prezium_render_demo_page'
	);
} );

function prezium_render_demo_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$done = get_option( 'prezium_demo_seeded' );
	echo '<div class="wrap"><h1>' . esc_html__( 'Demo-Daten', 'prezium' ) . '</h1>';
	if ( isset( $_GET['prezium_done'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Demo-Daten wurden angelegt.', 'prezium' ) . '</p></div>';
	}
	echo '<p>' . esc_html__( 'Legt 24 Testprodukte mit Bildern und Texten, 5 Marken, die Seiten und die Menüs an. Mehrfaches Ausführen ergänzt und aktualisiert die Demo-Daten, ohne Dubletten zu erzeugen. Testprodukte sind mit TEST markiert.', 'prezium' ) . '</p>';
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
	echo '<input type="hidden" name="action" value="prezium_demo_import">';
	wp_nonce_field( 'prezium_demo_import' );
	submit_button( $done ? __( 'Demo-Daten aktualisieren', 'prezium' ) : __( 'Demo-Daten anlegen', 'prezium' ) );
	echo '</form>';
	echo '</div>';
}

add_action( 'admin_post_prezium_demo_import', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Keine Berechtigung.', 'prezium' ), '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'prezium_demo_import' );
	prezium_run_demo_import();
	wp_safe_redirect( admin_url( 'edit.php?post_type=prezium_product&page=prezium-demo&prezium_done=1' ) );
	exit;
} );

/** Kopiert eine Demo-Datei aus der Theme in die Mediathek; gibt die Attachment-ID zurück. */
function prezium_demo_attach( $file, $parent = 0 ) {
	$src = get_template_directory() . '/assets/demo/' . $file;
	if ( ! file_exists( $src ) ) {
		return 0;
	}
	$upload = wp_upload_dir();
	$dest   = wp_unique_filename( $upload['path'], $file );
	copy( $src, $upload['path'] . '/' . $dest );
	$type = wp_check_filetype( $dest );
	$id   = wp_insert_attachment( array(
		'post_mime_type' => $type['type'],
		'post_title'     => preg_replace( '/\.[^.]+$/', '', $file ),
		'post_status'    => 'inherit',
	), $upload['path'] . '/' . $dest, $parent );
	if ( $id && ! is_wp_error( $id ) ) {
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['path'] . '/' . $dest ) );
		return $id;
	}
	return 0;
}

/** Seite anhand des Titels finden. */
function prezium_demo_find_page( $title ) {
	$found = get_posts( array( 'post_type' => 'page', 'post_status' => 'any', 'title' => $title, 'posts_per_page' => 1 ) );
	return $found ? $found[0] : null;
}

function prezium_run_demo_import() {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once get_template_directory() . '/inc/demo-data.php';

	foreach ( array( 'Türen', 'Parkett', 'Fenster', 'Terrassendielen', 'Möbel', 'Licht', 'Sonstiges' ) as $b ) {
		if ( ! term_exists( $b, 'prezium_bereich' ) ) {
			wp_insert_term( $b, 'prezium_bereich' );
		}
	}

	foreach ( prezium_demo_brands() as $name => $desc ) {
		$term = term_exists( $name, 'prezium_marke' );
		if ( $term ) {
			wp_update_term( (int) ( is_array( $term ) ? $term['term_id'] : $term ), 'prezium_marke', array( 'description' => $desc ) );
		} else {
			wp_insert_term( $name, 'prezium_marke', array( 'description' => $desc ) );
		}
	}

	$pdf_id  = (int) get_option( 'prezium_demo_pdf' );
	$pdf_url = $pdf_id ? wp_get_attachment_url( $pdf_id ) : '';
	if ( ! $pdf_url ) {
		$pdf_id = prezium_demo_attach( 'test-dokument.pdf' );
		if ( $pdf_id ) {
			update_option( 'prezium_demo_pdf', $pdf_id );
			$pdf_url = wp_get_attachment_url( $pdf_id );
		}
	}

	$bereich_text = prezium_demo_bereich_text();
	foreach ( prezium_demo_products() as $p ) {
		list( $title, $bereich, $marke, $sku, $material, $dims, $finish, $image, $lead, $excerpt ) = $p;

		$content = $lead . "\n\n" . ( $bereich_text[ $bereich ] ?? '' ) . "\n\nDies ist ein Demo-Text.";
		$existing = get_posts( array(
			'post_type'      => 'prezium_product',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_prez_sku', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $sku, // phpcs:ignore WordPress.DB.SlowDBQuery
		) );
		$args = array(
			'post_type'    => 'prezium_product',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $content,
			'post_excerpt' => $excerpt,
		);
		if ( $existing ) {
			$args['ID'] = $existing[0];
			$id         = wp_update_post( $args );
		} else {
			$id = wp_insert_post( $args );
		}
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		wp_set_object_terms( $id, $bereich, 'prezium_bereich' );
		wp_set_object_terms( $id, $marke, 'prezium_marke' );
		update_post_meta( $id, '_prez_sku', $sku );
		update_post_meta( $id, '_prez_material', $material );
		update_post_meta( $id, '_prez_dimensions', $dims );
		update_post_meta( $id, '_prez_finish', $finish );
		update_post_meta( $id, '_prez_is_test', 1 );
		if ( $pdf_url ) {
			foreach ( array_keys( prezium_product_documents() ) as $doc_key ) {
				update_post_meta( $id, $doc_key, $pdf_url );
			}
		}
		if ( ! has_post_thumbnail( $id ) ) {
			$img = prezium_demo_attach( $image . '.jpg', $id );
			if ( $img ) {
				set_post_thumbnail( $id, $img );
			}
		}
	}

	$page_ids = array();
	foreach ( prezium_demo_pages() as $title => $content ) {
		$page = prezium_demo_find_page( $title );
		$args = array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $content,
		);
		if ( $page ) {
			$args['ID'] = $page->ID;
			$page_ids[ $title ] = wp_update_post( $args );
		} else {
			$page_ids[ $title ] = wp_insert_post( $args );
		}
	}
	$marken_page = prezium_demo_find_page( 'Marken' );
	if ( ! $marken_page ) {
		$page_ids['Marken'] = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Marken', 'post_content' => '' ) );
	} else {
		$page_ids['Marken'] = $marken_page->ID;
	}

	$main_obj = wp_get_nav_menu_object( 'Hauptmenü' );
	$foot_obj = wp_get_nav_menu_object( 'Footer' );
	$main     = $main_obj ? $main_obj->term_id : wp_create_nav_menu( 'Hauptmenü' );
	$foot     = $foot_obj ? $foot_obj->term_id : wp_create_nav_menu( 'Footer' );
	if ( ! is_wp_error( $main ) && ! is_wp_error( $foot ) ) {
		if ( ! $main_obj ) {
			wp_update_nav_menu_item( $main, 0, array( 'menu-item-title' => 'Start', 'menu-item-url' => home_url( '/' ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
			wp_update_nav_menu_item( $main, 0, array( 'menu-item-title' => 'Katalog', 'menu-item-url' => get_post_type_archive_link( 'prezium_product' ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
		}
		$add = function ( $menu, $titles ) use ( $page_ids ) {
			$have = array();
			foreach ( (array) wp_get_nav_menu_items( $menu ) as $item ) {
				$have[] = (int) $item->object_id;
			}
			foreach ( $titles as $t ) {
				if ( ! empty( $page_ids[ $t ] ) && ! in_array( (int) $page_ids[ $t ], $have, true ) ) {
					wp_update_nav_menu_item( $menu, 0, array( 'menu-item-object-id' => $page_ids[ $t ], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
				}
			}
		};
		$add( $main, array( 'Marken', 'Architekten & Designer', 'Über uns', 'Kontakt' ) );
		$add( $foot, array( 'Impressum', 'Datenschutz' ) );
		set_theme_mod( 'nav_menu_locations', array( 'primary' => $main, 'footer' => $foot ) );
	}

	flush_rewrite_rules();
	update_option( 'prezium_demo_seeded', 1 );
}
