<?php
/**
 * Katalog: Suche (Name, Marke, Artikelnummer) und Filter (Marke, Material).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Alle vorhandenen Materialien für das Filter-Dropdown. */
function prezium_get_materials() {
	global $wpdb;
	$vals = $wpdb->get_col( $wpdb->prepare( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		"SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
		 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		 WHERE pm.meta_key = %s AND pm.meta_value <> '' AND p.post_type = %s AND p.post_status = 'publish'
		 ORDER BY pm.meta_value ASC",
		'_prez_material',
		'prezium_product'
	) );
	return $vals ? $vals : array();
}

function prezium_is_catalog_query( $q ) {
	return ! is_admin() && $q->is_main_query() && (
		$q->is_post_type_archive( 'prezium_product' ) ||
		$q->is_tax( array( 'prezium_bereich', 'prezium_marke' ) )
	);
}

add_action( 'pre_get_posts', function ( $q ) {
	if ( ! prezium_is_catalog_query( $q ) ) {
		return;
	}
	$q->set( 'post_type', 'prezium_product' );
	$q->set( 'posts_per_page', 12 );

	// phpcs:disable WordPress.Security.NonceVerification
	$tax_query  = (array) $q->get( 'tax_query' );
	$meta_query = (array) $q->get( 'meta_query' );

	if ( ! empty( $_GET['marke'] ) && ! $q->is_tax( 'prezium_marke' ) ) {
		$tax_query[] = array(
			'taxonomy' => 'prezium_marke',
			'field'    => 'slug',
			'terms'    => sanitize_title( wp_unslash( $_GET['marke'] ) ),
		);
	}
	if ( ! empty( $_GET['bereich'] ) && ! $q->is_tax( 'prezium_bereich' ) ) {
		$tax_query[] = array(
			'taxonomy' => 'prezium_bereich',
			'field'    => 'slug',
			'terms'    => sanitize_title( wp_unslash( $_GET['bereich'] ) ),
		);
	}
	if ( ! empty( $_GET['material'] ) ) {
		$meta_query[] = array(
			'key'   => '_prez_material',
			'value' => sanitize_text_field( wp_unslash( $_GET['material'] ) ),
		);
	}
	// phpcs:enable

	if ( $tax_query ) {
		$q->set( 'tax_query', $tax_query );
	}
	if ( $meta_query ) {
		$q->set( 'meta_query', $meta_query );
	}
} );

/** Suche erweitert um Artikelnummer und Markenname. */
add_filter( 'posts_search', function ( $search, $q ) {
	global $wpdb;
	if ( is_admin() || ! $q->is_main_query() || ! $q->is_search() || 'prezium_product' !== $q->get( 'post_type' ) ) {
		return $search;
	}
	$term = trim( (string) $q->get( 's' ) );
	if ( '' === $term ) {
		return $search;
	}
	$like = '%' . $wpdb->esc_like( $term ) . '%';
	return $wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQL
		" AND (
			{$wpdb->posts}.post_title LIKE %s
			OR EXISTS (
				SELECT 1 FROM {$wpdb->postmeta} pm
				WHERE pm.post_id = {$wpdb->posts}.ID AND pm.meta_key = '_prez_sku' AND pm.meta_value LIKE %s
			)
			OR EXISTS (
				SELECT 1 FROM {$wpdb->term_relationships} tr
				INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
				INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
				WHERE tr.object_id = {$wpdb->posts}.ID AND tt.taxonomy = 'prezium_marke' AND t.name LIKE %s
			)
		) ",
		$like,
		$like,
		$like
	);
}, 10, 2 );
