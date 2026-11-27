<?php
/**
 * Prezium theme setup, catalog post type and taxonomies.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/product-fields.php';
require_once get_template_directory() . '/inc/catalog-query.php';
require_once get_template_directory() . '/inc/inquiries.php';
require_once get_template_directory() . '/inc/demo-import.php';
require_once get_template_directory() . '/inc/sergo-import.php';
require_once get_template_directory() . '/inc/projects.php';
require_once get_template_directory() . '/inc/brands.php';

/**
 * Wer darf Dokumente herunterladen? Vorerst jeder angemeldete Nutzer.
 * Mit der B2B-Freischaltung (nächster Schritt) wird das auf "freigeschaltet" eingeschränkt.
 */
function prezium_user_can_download() {
	return is_user_logged_in();
}

/** Kennzeichnung eines Produkts: '', 'TEST', 'SERGO' oder 'DEMOPROJECT'. */
function prezium_label( $post_id = null ) {
	$id    = $post_id ? $post_id : get_the_ID();
	$label = get_post_meta( $id, '_prez_label', true );
	if ( ! $label && get_post_meta( $id, '_prez_is_test', true ) ) {
		$label = 'TEST';
	}
	return $label;
}

function prezium_is_test( $post_id = null ) {
	return (bool) prezium_label( $post_id );
}

/** Gibt das Etikett (Badge) aus; $inline für den Titel auf der Produktseite. */
function prezium_badge( $inline = false ) {
	// Vor dem Livegang: in wp-config.php  define( 'PREZIUM_HIDE_BADGES', true );  blendet alle Etiketten aus.
	if ( defined( 'PREZIUM_HIDE_BADGES' ) && PREZIUM_HIDE_BADGES ) {
		return;
	}
	$label = prezium_label();
	if ( $label ) {
		printf( '<span class="badge-test badge-%1$s%2$s">%3$s</span>', esc_attr( strtolower( $label ) ), $inline ? ' badge-inline' : '', esc_html( $label ) );
	}
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
	// Versionsnummer = Änderungszeit der Datei, damit Browser und Cache nach jedem Update neu laden.
	$ver = function ( $rel ) {
		$file = get_template_directory() . '/' . $rel;
		return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme()->get( 'Version' );
	};
	wp_enqueue_style(
		'prezium-fonts',
		'https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&family=Cormorant+Garamond:wght@300;400;500&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'prezium-style', get_stylesheet_uri(), array( 'prezium-fonts' ), $ver( 'style.css' ) );

	if ( is_singular( 'prezium_project' ) || is_post_type_archive( 'prezium_project' ) ) {
		wp_enqueue_style( 'prezium-project', get_template_directory_uri() . '/assets/css/project.css', array( 'prezium-style' ), $ver( 'assets/css/project.css' ) );
	}

	if ( is_page( 'marken' ) ) {
		wp_enqueue_style( 'prezium-marken', get_template_directory_uri() . '/assets/css/marken.css', array( 'prezium-style' ), $ver( 'assets/css/marken.css' ) );
	}

	if ( is_front_page() ) {
		wp_enqueue_style( 'prezium-home', get_template_directory_uri() . '/assets/css/home.css', array( 'prezium-style' ), $ver( 'assets/css/home.css' ) );
		wp_enqueue_script( 'prezium-home', get_template_directory_uri() . '/assets/js/home.js', array(), $ver( 'assets/js/home.js' ), true );
	}

	wp_enqueue_script( 'prezium-site', get_template_directory_uri() . '/assets/js/site.js', array(), $ver( 'assets/js/site.js' ), true );
	wp_localize_script( 'prezium-site', 'prezium', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'prezium_inquiry' ),
		'error'   => __( 'Senden fehlgeschlagen. Bitte versuchen Sie es erneut.', 'prezium' ),
	) );
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


/**
 * LiteSpeed-Seitencache nach einem Deployment leeren: Signatur aus den Änderungszeiten der Theme-Dateien.
 */
add_action( 'init', function () {
	if ( is_admin() || ! has_action( 'litespeed_purge_all' ) && ! defined( 'LSCWP_V' ) ) {
		return;
	}
	$dir  = get_template_directory();
	$sig  = '';
	$list = array_merge( glob( $dir . '/*.php' ) ?: array(), glob( $dir . '/inc/*.php' ) ?: array(), glob( $dir . '/template-parts/*.php' ) ?: array(), glob( $dir . '/assets/css/*.css' ) ?: array(), glob( $dir . '/assets/js/*.js' ) ?: array(), array( $dir . '/style.css' ) );
	foreach ( $list as $file ) {
		$sig .= filemtime( $file ) . ',';
	}
	$sig = md5( $sig );
	if ( get_option( 'prezium_deploy_sig' ) !== $sig ) {
		update_option( 'prezium_deploy_sig', $sig, false );
		do_action( 'litespeed_purge_all' );
	}
}, 5 );
