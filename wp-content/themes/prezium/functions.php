<?php
/**
 * Prezium theme setup, catalog post type and taxonomies.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'prezium', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array(
		'primary' => __( 'Hauptmenü', 'prezium' ),
		'footer'  => __( 'Footer', 'prezium' ),
	) );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'prezium-fonts',
		'https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'prezium-style', get_stylesheet_uri(), array( 'prezium-fonts' ), wp_get_theme()->get( 'Version' ) );
} );

/**
 * Katalog: Produkte, Bereiche (Türen, Parkett, ...) und Marken.
 */
add_action( 'init', function () {
	register_post_type( 'prezium_product', array(
		'labels' => array(
			'name'          => __( 'Produkte', 'prezium' ),
			'singular_name' => __( 'Produkt', 'prezium' ),
			'add_new_item'  => __( 'Produkt hinzufügen', 'prezium' ),
		),
		'public'       => true,
		'has_archive'  => 'katalog',
		'rewrite'      => array( 'slug' => 'katalog' ),
		'menu_icon'    => 'dashicons-archive',
		'show_in_rest' => true,
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
	) );

	register_taxonomy( 'prezium_bereich', 'prezium_product', array(
		'labels'       => array(
			'name'          => __( 'Bereiche', 'prezium' ),
			'singular_name' => __( 'Bereich', 'prezium' ),
		),
		'hierarchical' => true,
		'public'       => true,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'bereich' ),
	) );

	register_taxonomy( 'prezium_marke', 'prezium_product', array(
		'labels'       => array(
			'name'          => __( 'Marken', 'prezium' ),
			'singular_name' => __( 'Marke', 'prezium' ),
		),
		'hierarchical' => false,
		'public'       => true,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'marke' ),
	) );
} );

/**
 * Beim Aktivieren: Standard-Bereiche anlegen und Permalinks neu schreiben.
 */
add_action( 'after_switch_theme', function () {
	$bereiche = array( 'Türen', 'Parkett', 'Fenster', 'Terrassendielen', 'Möbel', 'Licht', 'Sonstiges' );
	foreach ( $bereiche as $name ) {
		if ( ! term_exists( $name, 'prezium_bereich' ) ) {
			wp_insert_term( $name, 'prezium_bereich' );
		}
	}
	flush_rewrite_rules();
} );
