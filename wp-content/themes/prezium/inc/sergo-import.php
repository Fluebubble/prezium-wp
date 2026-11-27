<?php
/**
 * Import einer Produktauswahl aus dem Schwesterkatalog (Admin: Produkte → Sergo-Import).
 * Läuft in Paketen, lädt Bilder serverseitig, legt Produkte als TEST an (Upsert nach Artikelnummer).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const PREZIUM_SERGO_BASE  = 'https://sergo.com.ua';
const PREZIUM_SERGO_BATCH = 6;

add_action( 'admin_menu', function () {
	add_submenu_page( 'edit.php?post_type=prezium_product', __( 'Sergo-Import', 'prezium' ), __( 'Sergo-Import', 'prezium' ), 'manage_options', 'prezium-sergo', 'prezium_render_sergo_page' );
} );

function prezium_render_sergo_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	require_once get_template_directory() . '/inc/sergo-data.php';
	$total  = count( prezium_sergo_list() );
	$offset = isset( $_GET['offset'] ) ? absint( $_GET['offset'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
	$running = isset( $_GET['run'] ); // phpcs:ignore WordPress.Security.NonceVerification
	echo '<div class="wrap"><h1>' . esc_html__( 'Sergo-Import', 'prezium' ) . '</h1>';
	echo '<p>' . esc_html( sprintf( __( 'Importiert %d Produkte (Türen, Parkett, Terrassendielen, Möbel, Licht) mit Bildern. Mehrfaches Ausführen erzeugt keine Dubletten.', 'prezium' ), $total ) ) . '</p>';
	if ( $running && $offset < $total ) {
		echo '<p><strong>' . esc_html( sprintf( __( 'Fortschritt: %1$d von %2$d …', 'prezium' ), $offset, $total ) ) . '</strong></p>';
	} elseif ( $running ) {
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Import abgeschlossen.', 'prezium' ) . '</p></div>';
	}
	if ( ! $running || $offset < $total ) {
		echo '<form id="prez-sergo" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
		echo '<input type="hidden" name="action" value="prezium_sergo_import"><input type="hidden" name="offset" value="' . esc_attr( $running ? $offset : 0 ) . '">';
		wp_nonce_field( 'prezium_sergo_import' );
		submit_button( $running ? __( 'Weiter', 'prezium' ) : __( 'Import starten', 'prezium' ) );
		echo '</form>';
		if ( $running ) {
			echo '<script>document.getElementById("prez-sergo").submit();</script>';
		}
	}
	echo '</div>';
}

add_action( 'admin_post_prezium_sergo_import', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Keine Berechtigung.', 'prezium' ), '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'prezium_sergo_import' );
	require_once get_template_directory() . '/inc/sergo-data.php';
	@set_time_limit( 280 ); // phpcs:ignore
	$offset = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;
	$slice  = array_slice( prezium_sergo_list(), $offset, PREZIUM_SERGO_BATCH, true );
	foreach ( $slice as $slug => $bereich ) {
		prezium_sergo_import_one( $slug, $bereich );
	}
	wp_safe_redirect( admin_url( 'edit.php?post_type=prezium_product&page=prezium-sergo&run=1&offset=' . ( $offset + PREZIUM_SERGO_BATCH ) ) );
	exit;
} );

function prezium_sergo_get( $url ) {
	$r = wp_remote_get( $url, array( 'timeout' => 25 ) );
	return ( ! is_wp_error( $r ) && 200 === wp_remote_retrieve_response_code( $r ) ) ? wp_remote_retrieve_body( $r ) : '';
}

function prezium_sergo_import_one( $slug, $bereich ) {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$json = prezium_sergo_get( PREZIUM_SERGO_BASE . '/wp-json/wp/v2/product?slug=' . rawurlencode( $slug ) . '&_fields=title,link,featured_media' );
	$item = $json ? json_decode( $json, true ) : null;
	if ( empty( $item[0] ) ) {
		return 0;
	}
	$item  = $item[0];
	$title = html_entity_decode( wp_strip_all_tags( $item['title']['rendered'] ), ENT_QUOTES, 'UTF-8' );

	$types = prezium_sergo_types();
	$tkey  = '';
	foreach ( array_keys( $types ) as $k ) {
		if ( 0 === strpos( $title, $k ) ) {
			$tkey = $k;
			break;
		}
	}
	$name = trim( $tkey ? substr( $title, strlen( $tkey ) ) : $title );
	$name = preg_replace( '/^(?:[\p{Cyrillic}]+\s+)+/u', '', $name );

	// Seite: Marke und Eigenschaften.
	$brand = '';
	$attrs = array();
	$html  = prezium_sergo_get( $item['link'] );
	if ( $html ) {
		if ( preg_match( '/Brands:\s*(.+?)\s+Brand\b/us', wp_strip_all_tags( $html ), $m ) ) {
			$brand = trim( preg_replace( '/\s*Опис.*/u', '', $m[1] ) );
		}
		if ( preg_match_all( '/attributes-item__label">\s*([^<]+?)\s*<\/th>.*?attributes-item__value">\s*(?:<p>)?\s*(.*?)\s*(?:<\/p>)?\s*<\/td>/us', $html, $mm, PREG_SET_ORDER ) ) {
			foreach ( $mm as $x ) {
				$attrs[ trim( $x[1] ) ] = trim( wp_strip_all_tags( $x[2] ) );
			}
		}
	}
	if ( '' === $brand ) {
		$parts = explode( ' ', $name );
		$brand = $parts[0];
	}
	$countries = prezium_sergo_countries();
	$country   = $countries[ $attrs['Страна изготовления'] ?? '' ] ?? '';
	$coll      = $attrs['Коллекция'] ?? '';
	$material  = $attrs['Материал'] ?? '';
	$finish    = $attrs['Цвет'] ?? '';

	$tde   = $tkey ? $types[ $tkey ][0] : 'Produkt';
	$lead  = $tkey ? $types[ $tkey ][1] : 'Ein ausgewähltes Designobjekt.';
	$text  = $lead;
	if ( $country ) {
		$text .= ' ' . sprintf( 'Hergestellt in %s von %s.', $country, $brand );
	}
	$text .= "\n\nTechnische Daten, Varianten und Preise senden wir Ihnen gern auf Anfrage.\n\nDies ist ein Demo-Text.";
	$excerpt = trim( $tde . ' von ' . $brand . ( $country ? ' · ' . $country : '' ) );

	$sku      = 'SG-' . strtoupper( substr( md5( $slug ), 0, 6 ) );
	$existing = get_posts( array( 'post_type' => 'prezium_product', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => '_prez_sku', 'meta_value' => $sku ) ); // phpcs:ignore
	$args     = array( 'post_type' => 'prezium_product', 'post_status' => 'publish', 'post_title' => $name, 'post_content' => $text, 'post_excerpt' => $excerpt );
	if ( $existing ) {
		$args['ID'] = $existing[0];
		$id         = wp_update_post( $args );
	} else {
		$id = wp_insert_post( $args );
	}
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	if ( ! term_exists( $bereich, 'prezium_bereich' ) ) {
		wp_insert_term( $bereich, 'prezium_bereich' );
	}
	wp_set_object_terms( $id, $bereich, 'prezium_bereich' );
	wp_set_object_terms( $id, $brand, 'prezium_marke' );
	update_post_meta( $id, '_prez_sku', $sku );
	update_post_meta( $id, '_prez_material', $material );
	update_post_meta( $id, '_prez_finish', $finish ? $finish : $coll );
	update_post_meta( $id, '_prez_is_test', 1 );

	if ( ! has_post_thumbnail( $id ) && ! empty( $item['featured_media'] ) ) {
		$media = json_decode( prezium_sergo_get( PREZIUM_SERGO_BASE . '/wp-json/wp/v2/media/' . (int) $item['featured_media'] . '?_fields=source_url' ), true );
		if ( ! empty( $media['source_url'] ) ) {
			$att = media_sideload_image( esc_url_raw( $media['source_url'] ), $id, $name, 'id' );
			if ( ! is_wp_error( $att ) ) {
				set_post_thumbnail( $id, $att );
			}
		}
	}
	return $id;
}
