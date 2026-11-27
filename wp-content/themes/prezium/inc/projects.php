<?php
/**
 * Projekte (Referenzen): Post-Typ, Verknüpfung mit Produkten, Demo-Projekte per Knopfdruck.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'prezium_project', array(
		'labels'       => array(
			'name'          => __( 'Projekte', 'prezium' ),
			'singular_name' => __( 'Projekt', 'prezium' ),
			'add_new_item'  => __( 'Projekt hinzufügen', 'prezium' ),
		),
		'public'       => true,
		'has_archive'  => false,
		'rewrite'      => array( 'slug' => 'projekte' ),
		'menu_icon'    => 'dashicons-building',
		'show_in_rest' => true,
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
	) );
	// Verknüpfung: Produkte eines Projekts tragen den Begriff mit dem Slug des Projekts.
	register_taxonomy( 'prezium_projekt', 'prezium_product', array(
		'labels'            => array( 'name' => __( 'Projekte', 'prezium' ), 'singular_name' => __( 'Projekt', 'prezium' ) ),
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'hierarchical'      => false,
		'show_in_rest'      => true,
	) );
} );

/** Rewrite-Regeln nach Theme-Update einmalig neu schreiben (neuer Post-Typ „Projekte“). */
add_action( 'init', function () {
	if ( get_option( 'prezium_rewrite_v' ) !== '3' ) {
		flush_rewrite_rules( false );
		update_option( 'prezium_rewrite_v', '3' );
	}
}, 99 );

/** Einmalig: bereits importierte Sergo-Produkte (Artikelnummer SG-…) auf SERGO umstellen. */
add_action( 'admin_init', function () {
	if ( get_option( 'prezium_mig_label1' ) ) {
		return;
	}
	$ids = get_posts( array( 'post_type' => 'prezium_product', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_query' => array( array( 'key' => '_prez_sku', 'value' => 'SG-', 'compare' => 'LIKE' ) ) ) ); // phpcs:ignore
	foreach ( $ids as $id ) {
		update_post_meta( $id, '_prez_label', 'SERGO' );
		delete_post_meta( $id, '_prez_is_test' );
	}
	update_option( 'prezium_mig_label1', 1 );
} );

/** Produkte eines Projekts. */
function prezium_project_products( $project_id ) {
	return new WP_Query( array(
		'post_type'      => 'prezium_product',
		'posts_per_page' => 24,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
		'tax_query'      => array( array( 'taxonomy' => 'prezium_projekt', 'field' => 'slug', 'terms' => get_post_field( 'post_name', $project_id ) ) ), // phpcs:ignore
	) );
}

/* ---------- Demo-Seeding (Admin: Produkte → Demo-Projekte) ---------- */

add_action( 'admin_menu', function () {
	add_submenu_page( 'edit.php?post_type=prezium_product', __( 'Demo-Projekte', 'prezium' ), __( 'Demo-Projekte', 'prezium' ), 'manage_options', 'prezium-projects', function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		echo '<div class="wrap"><h1>' . esc_html__( 'Demo-Projekte', 'prezium' ) . '</h1>';
		if ( isset( $_GET['prezium_done'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Demo-Projekte und Produkte wurden angelegt.', 'prezium' ) . '</p></div>';
		}
		echo '<p>' . esc_html__( 'Legt die 4 Projekte der Startseite mit Beschreibung und die zugehörigen Produkte (DEMOPROJECT) an. Produktbilder: Dateien nach assets/demo-projects/<slug>.jpg legen und erneut ausführen.', 'prezium' ) . '</p>';
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="prezium_projects_seed">';
		wp_nonce_field( 'prezium_projects_seed' );
		submit_button( __( 'Demo-Projekte anlegen / aktualisieren', 'prezium' ) );
		echo '</form></div>';
	} );
} );

add_action( 'admin_post_prezium_projects_seed', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Keine Berechtigung.', 'prezium' ), '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'prezium_projects_seed' );
	prezium_seed_projects();
	wp_safe_redirect( admin_url( 'edit.php?post_type=prezium_product&page=prezium-projects&prezium_done=1' ) );
	exit;
} );

/** Datei aus dem Theme in die Mediathek kopieren. */
function prezium_theme_attach( $rel, $parent ) {
	$src = get_template_directory() . '/' . $rel;
	if ( ! file_exists( $src ) ) {
		return 0;
	}
	$up   = wp_upload_dir();
	$name = wp_unique_filename( $up['path'], basename( $rel ) );
	copy( $src, $up['path'] . '/' . $name );
	$type = wp_check_filetype( $name );
	$id   = wp_insert_attachment( array( 'post_mime_type' => $type['type'], 'post_title' => preg_replace( '/\.[^.]+$/', '', $name ), 'post_status' => 'inherit' ), $up['path'] . '/' . $name, $parent );
	if ( $id && ! is_wp_error( $id ) ) {
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $up['path'] . '/' . $name ) );
		return $id;
	}
	return 0;
}

function prezium_seed_projects() {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once get_template_directory() . '/inc/projects-data.php';
	require_once get_template_directory() . '/inc/projects-articles.php';
	$articles = prezium_demo_project_articles();

	foreach ( prezium_demo_project_brands() as $name => $desc ) {
		if ( ! term_exists( $name, 'prezium_marke' ) ) {
			wp_insert_term( $name, 'prezium_marke', array( 'description' => $desc ) );
		}
	}
	foreach ( prezium_demo_projects() as $slug => $pr ) {
		$found = get_posts( array( 'post_type' => 'prezium_project', 'name' => $slug, 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
		$args  = array( 'post_type' => 'prezium_project', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $pr['title'], 'post_excerpt' => $pr['excerpt'], 'post_content' => isset( $articles[ $slug ] ) ? $articles[ $slug ]['html'] : $pr['content'] );
		if ( $found ) {
			$args['ID'] = $found[0];
			$pid        = wp_update_post( $args );
		} else {
			$pid = wp_insert_post( $args );
		}
		if ( ! $pid || is_wp_error( $pid ) ) {
			continue;
		}
		update_post_meta( $pid, '_prez_facts', $pr['facts'] );
		if ( ! has_post_thumbnail( $pid ) ) {
			$att = prezium_theme_attach( 'assets/home/' . $pr['image'], $pid );
			if ( $att ) {
				set_post_thumbnail( $pid, $att );
			}
		}
		if ( isset( $articles[ $slug ] ) ) {
			update_post_meta( $pid, '_prez_quote', $articles[ $slug ]['quote'] );
			// Galerie: vorhandene Bilder assets/demo-projects/projects/<slug>-N.webp|jpg|png einbinden.
			$gallery = (array) get_post_meta( $pid, '_prez_gallery', true );
			foreach ( $articles[ $slug ]['gallery'] as $i => $caption ) {
				$n = $i + 1;
				if ( ! empty( $gallery[ $n ] ) && get_post( $gallery[ $n ] ) ) {
					continue;
				}
				foreach ( array( 'webp', 'jpg', 'png' ) as $ext ) {
					$att = prezium_theme_attach( 'assets/demo-projects/projects/' . $slug . '-' . $n . '.' . $ext, $pid );
					if ( $att ) {
						wp_update_post( array( 'ID' => $att, 'post_excerpt' => $caption ) );
						$gallery[ $n ] = $att;
						break;
					}
				}
			}
			update_post_meta( $pid, '_prez_gallery', array_filter( $gallery ) );
		}
		if ( ! term_exists( $slug, 'prezium_projekt' ) ) {
			wp_insert_term( $pr['title'], 'prezium_projekt', array( 'slug' => $slug ) );
		}
	}
	foreach ( prezium_demo_project_products() as $p ) {
		list( $slug, $projekt, $title, $bereich, $marke, $material, $dims, $finish, $lead ) = $p;
		$sku      = 'DP-' . strtoupper( substr( md5( $slug ), 0, 6 ) );
		$existing = get_posts( array( 'post_type' => 'prezium_product', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => '_prez_sku', 'meta_value' => $sku ) ); // phpcs:ignore
		$args     = array( 'post_type' => 'prezium_product', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $title, 'post_content' => $lead . "\n\nTechnische Daten, Varianten und Preise senden wir Ihnen gern auf Anfrage.\n\nDies ist ein Demo-Text.", 'post_excerpt' => $bereich . ' · ' . $marke );
		if ( $existing ) {
			$args['ID'] = $existing[0];
			$id         = wp_update_post( $args );
		} else {
			$id = wp_insert_post( $args );
		}
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		if ( ! term_exists( $bereich, 'prezium_bereich' ) ) {
			wp_insert_term( $bereich, 'prezium_bereich' );
		}
		wp_set_object_terms( $id, $bereich, 'prezium_bereich' );
		wp_set_object_terms( $id, $marke, 'prezium_marke' );
		wp_set_object_terms( $id, $projekt, 'prezium_projekt' );
		update_post_meta( $id, '_prez_sku', $sku );
		update_post_meta( $id, '_prez_material', $material );
		update_post_meta( $id, '_prez_dimensions', $dims );
		update_post_meta( $id, '_prez_finish', $finish );
		update_post_meta( $id, '_prez_label', 'DEMOPROJECT' );
		if ( ! has_post_thumbnail( $id ) ) {
			foreach ( array( 'jpg', 'png', 'webp' ) as $ext ) {
				$att = prezium_theme_attach( 'assets/demo-projects/' . $slug . '.' . $ext, $id );
				if ( $att ) {
					set_post_thumbnail( $id, $att );
					break;
				}
			}
		}
	}
}

/** Bildplatzhalter <!--prez-img:N--> im Projekttext durch Galeriebilder ersetzen. */
add_filter( 'the_content', function ( $content ) {
	if ( ! is_singular( 'prezium_project' ) || false === strpos( $content, 'prez-img' ) ) {
		return $content;
	}
	$gallery = (array) get_post_meta( get_the_ID(), '_prez_gallery', true );
	return preg_replace_callback( '/(?:<p>)?<!--prez-img:(\d+)-->(?:<\/p>)?/', function ( $m ) use ( $gallery ) {
		$id = isset( $gallery[ (int) $m[1] ] ) ? (int) $gallery[ (int) $m[1] ] : 0;
		if ( ! $id ) {
			return '';
		}
		$cap = wp_get_attachment_caption( $id );
		return '<figure class="proj-fig">' . wp_get_attachment_image( $id, 'full', false, array( 'loading' => 'lazy' ) ) . ( $cap ? '<figcaption>' . esc_html( $cap ) . '</figcaption>' : '' ) . '</figure>';
	}, $content );
}, 12 );

/** Lesedauer in Minuten. */
function prezium_reading_time( $post_id ) {
	return max( 1, (int) ceil( str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) ) / 200 ) );
}
