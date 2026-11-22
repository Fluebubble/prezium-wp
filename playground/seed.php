<?php
// Demo-Inhalte für die Playground-Vorschau. Nur Testdaten.
require_once '/wordpress/wp-load.php';

if ( get_option( 'prezium_demo_seeded' ) ) {
	return;
}

// Bereiche werden beim Aktivieren der Theme angelegt; sicherheitshalber nochmals.
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
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';

/** Kopiert eine Demo-Datei aus der Theme in die Mediathek und gibt die Attachment-ID zurück. */
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

$pages = array(
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

// Menüs.
$main = wp_create_nav_menu( 'Hauptmenü' );
wp_update_nav_menu_item( $main, 0, array( 'menu-item-title' => 'Start', 'menu-item-url' => home_url( '/' ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
wp_update_nav_menu_item( $main, 0, array( 'menu-item-title' => 'Katalog', 'menu-item-url' => get_post_type_archive_link( 'prezium_product' ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
foreach ( array( 'Über uns', 'Kontakt' ) as $t ) {
	wp_update_nav_menu_item( $main, 0, array( 'menu-item-object-id' => $page_ids[ $t ], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
}
$foot = wp_create_nav_menu( 'Footer' );
foreach ( array( 'Impressum', 'Datenschutz' ) as $t ) {
	wp_update_nav_menu_item( $foot, 0, array( 'menu-item-object-id' => $page_ids[ $t ], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
}
set_theme_mod( 'nav_menu_locations', array( 'primary' => $main, 'footer' => $foot ) );

// Demo-Nutzer zum Testen der Kundensicht (nur Testdaten, in der Vorschau wertlos).
if ( ! username_exists( 'architekt' ) ) {
	wp_insert_user( array(
		'user_login'   => 'architekt',
		'user_pass'    => 'Test-Architekt-1',
		'user_email'   => 'architekt@example.com',
		'display_name' => 'Test Architekt',
		'role'         => 'subscriber',
	) );
}

flush_rewrite_rules();
update_option( 'prezium_demo_seeded', 1 );
