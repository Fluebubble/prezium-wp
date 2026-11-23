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
	if ( $done ) {
		echo '<p>' . esc_html__( 'Die Demo-Daten sind bereits angelegt. Testprodukte sind mit TEST markiert und können unter Produkte gelöscht werden.', 'prezium' ) . '</p>';
	} else {
		echo '<p>' . esc_html__( 'Legt 9 Testprodukte mit Bildern, 5 Marken, 4 Seiten und die Menüs an.', 'prezium' ) . '</p>';
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
		echo '<input type="hidden" name="action" value="prezium_demo_import">';
		wp_nonce_field( 'prezium_demo_import' );
		submit_button( __( 'Demo-Daten anlegen', 'prezium' ) );
		echo '</form>';
	}
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

function prezium_run_demo_import() {
	if ( get_option( 'prezium_demo_seeded' ) ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';

	foreach ( array( 'Türen', 'Parkett', 'Fenster', 'Terrassendielen', 'Möbel', 'Licht', 'Sonstiges' ) as $b ) {
		if ( ! term_exists( $b, 'prezium_bereich' ) ) {
			wp_insert_term( $b, 'prezium_bereich' );
		}
	}

	$brands = array(
		'Cocif Porte'        => 'Italienische Manufaktur für Türen mit klarer Linie.',
		'Ali Parquet'        => 'Parkett und Terrassendielen aus ausgewähltem Massivholz.',
		'Ravaioli Falegnami' => 'Fenster und Schreinerarbeiten nach Maß.',
		'Aeterno Parquet'    => 'Parkettkollektionen und Außenmöbel in Edelholz.',
		'Novoferm'           => 'Brandschutz- und Funktionstüren in Serie und Sonderanfertigung.',
	);
	foreach ( $brands as $name => $desc ) {
		if ( ! term_exists( $name, 'prezium_marke' ) ) {
			wp_insert_term( $name, 'prezium_marke', array( 'description' => $desc ) );
		}
	}

	$products = array(
		array( 'Linea Zimmertür', 'Türen', 'Cocif Porte', 'CP-1040', 'Eiche lackiert', '2100 × 900 mm', 'Drehtür', 'linea-zimmertuer' ),
		array( 'Cortina Schiebetür', 'Türen', 'Cocif Porte', 'CP-2210', 'Glas', '2400 × 1000 mm', 'Schiebetür', 'cortina-schiebetuer' ),
		array( 'Stahl-Brandschutztür T30', 'Türen', 'Novoferm', 'NF-3005', 'Stahl', '2000 × 1000 mm', 'Drehtür', 'stahl-brandschutztuer' ),
		array( 'Fischgrät Eiche Natur', 'Parkett', 'Ali Parquet', 'AP-5120', 'Eiche', '14 mm Stärke', 'Geölt', 'fischgraet-eiche' ),
		array( 'Landhausdiele Nussbaum', 'Parkett', 'Aeterno Parquet', 'AE-6044', 'Nussbaum', '20 mm Stärke', 'Lackiert', 'landhausdiele-nussbaum' ),
		array( 'Holz-Alu-Fenster Serie 90', 'Fenster', 'Ravaioli Falegnami', 'RF-7001', 'Holz-Alu', 'nach Maß', 'Dreh-Kipp', 'holz-alu-fenster' ),
		array( 'Thermoesche Terrasse', 'Terrassendielen', 'Ali Parquet', 'AP-8030', 'Esche', '26 mm Stärke', 'Natur', 'thermoesche-terrasse' ),
		array( 'Outdoor Lounge Teak', 'Möbel', 'Aeterno Parquet', 'AE-9010', 'Teak', '220 × 90 cm', 'Natur', 'outdoor-lounge-teak' ),
		array( 'Schienenleuchte Prisma', 'Licht', 'Ravaioli Falegnami', 'RF-1120', 'Aluminium', 'Ø 60 mm', 'Schwarz matt', 'schienenleuchte-prisma' ),
	);

	$pdf_id  = prezium_demo_attach( 'test-dokument.pdf' );
	$pdf_url = $pdf_id ? wp_get_attachment_url( $pdf_id ) : '';

	foreach ( $products as $p ) {
		$id = wp_insert_post( array(
			'post_type'    => 'prezium_product',
			'post_status'  => 'publish',
			'post_title'   => $p[0],
			'post_content' => 'Präzise gefertigt, in mehreren Oberflächen erhältlich. Dies ist ein Demo-Text.',
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			wp_set_object_terms( $id, $p[1], 'prezium_bereich' );
			wp_set_object_terms( $id, $p[2], 'prezium_marke' );
			update_post_meta( $id, '_prez_sku', $p[3] );
			update_post_meta( $id, '_prez_material', $p[4] );
			update_post_meta( $id, '_prez_dimensions', $p[5] );
			update_post_meta( $id, '_prez_finish', $p[6] );
			update_post_meta( $id, '_prez_is_test', 1 );
			if ( $pdf_url ) {
				foreach ( array_keys( prezium_product_documents() ) as $doc_key ) {
					update_post_meta( $id, $doc_key, $pdf_url );
				}
			}
			$img = prezium_demo_attach( $p[7] . '.jpg', $id );
			if ( $img ) {
				set_post_thumbnail( $id, $img );
			}
		}
	}

	$pages    = array(
		'Kontakt'     => 'Prezium GmbH, Musterstraße 1, 51373 Leverkusen. (Demo-Adresse)',
		'Über uns'    => 'Prezium GmbH bringt europäische Manufakturen und anspruchsvolle Projekte zusammen.',
		'Impressum'   => 'Platzhalter. Rechtstexte werden vor dem Livegang von einer Fachkanzlei erstellt.',
		'Datenschutz' => 'Platzhalter. Rechtstexte werden vor dem Livegang von einer Fachkanzlei erstellt.',
	);
	$page_ids = array();
	foreach ( $pages as $title => $content ) {
		$page_ids[ $title ] = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $content,
		) );
	}

	$main = wp_create_nav_menu( 'Hauptmenü' );
	if ( ! is_wp_error( $main ) ) {
		wp_update_nav_menu_item( $main, 0, array( 'menu-item-title' => 'Start', 'menu-item-url' => home_url( '/' ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
		wp_update_nav_menu_item( $main, 0, array( 'menu-item-title' => 'Katalog', 'menu-item-url' => get_post_type_archive_link( 'prezium_product' ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
		foreach ( array( 'Über uns', 'Kontakt' ) as $t ) {
			wp_update_nav_menu_item( $main, 0, array( 'menu-item-object-id' => $page_ids[ $t ], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
	}
	$foot = wp_create_nav_menu( 'Footer' );
	if ( ! is_wp_error( $foot ) ) {
		foreach ( array( 'Impressum', 'Datenschutz' ) as $t ) {
			wp_update_nav_menu_item( $foot, 0, array( 'menu-item-object-id' => $page_ids[ $t ], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
	}
	if ( ! is_wp_error( $main ) && ! is_wp_error( $foot ) ) {
		set_theme_mod( 'nav_menu_locations', array( 'primary' => $main, 'footer' => $foot ) );
	}

	flush_rewrite_rules();
	update_option( 'prezium_demo_seeded', 1 );
}
