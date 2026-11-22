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
	array( 'Linea Zimmertür', 'Türen', 'Cocif Porte', 'CP-1040', 'Eiche lackiert', '2100 × 900 mm', 'Drehtür' ),
	array( 'Cortina Schiebetür', 'Türen', 'Cocif Porte', 'CP-2210', 'Glas', '2400 × 1000 mm', 'Schiebetür' ),
	array( 'Stahl-Brandschutztür T30', 'Türen', 'Novoferm', 'NF-3005', 'Stahl', '2000 × 1000 mm', 'Drehtür' ),
	array( 'Fischgrät Eiche Natur', 'Parkett', 'Ali Parquet', 'AP-5120', 'Eiche', '14 mm Stärke', 'Geölt' ),
	array( 'Landhausdiele Nussbaum', 'Parkett', 'Aeterno Parquet', 'AE-6044', 'Nussbaum', '20 mm Stärke', 'Lackiert' ),
	array( 'Holz-Alu-Fenster Serie 90', 'Fenster', 'Ravaioli Falegnami', 'RF-7001', 'Holz-Alu', 'nach Maß', 'Dreh-Kipp' ),
	array( 'Thermoesche Terrasse', 'Terrassendielen', 'Ali Parquet', 'AP-8030', 'Esche', '26 mm Stärke', 'Natur' ),
	array( 'Outdoor Lounge Teak', 'Möbel', 'Aeterno Parquet', 'AE-9010', 'Teak', '220 × 90 cm', 'Natur' ),
	array( 'Schienenleuchte Prisma', 'Licht', 'Ravaioli Falegnami', 'RF-1120', 'Aluminium', 'Ø 60 mm', 'Schwarz matt' ),
);
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

flush_rewrite_rules();
update_option( 'prezium_demo_seeded', 1 );
