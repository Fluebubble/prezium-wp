<?php
/**
 * Marken: Logo, Webseite, Land und „Hervorgehoben“ pro Marke (im WordPress-Editor pflegbar).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Metafelder einer Marke. */
function prezium_brand_meta( $term_id ) {
	return array(
		'logo'     => (int) get_term_meta( $term_id, '_prez_logo', true ),
		'invert'   => (bool) get_term_meta( $term_id, '_prez_logo_invert', true ),
		'url'      => (string) get_term_meta( $term_id, '_prez_url', true ),
		'country'  => (string) get_term_meta( $term_id, '_prez_country', true ),
		'featured' => (bool) get_term_meta( $term_id, '_prez_featured', true ),
	);
}

/** Markenzeichen: Logo aus der Mediathek oder, falls keins vorhanden ist, ein Schriftzug. */
function prezium_brand_mark( $term, $size = 'medium' ) {
	$m = prezium_brand_meta( $term->term_id );
	if ( $m['logo'] ) {
		$img = wp_get_attachment_image( $m['logo'], $size, false, array( 'class' => 'bm-logo' . ( $m['invert'] ? ' is-invert' : '' ), 'alt' => $term->name, 'loading' => 'lazy' ) );
		if ( $img ) {
			return $img;
		}
	}
	return '<span class="bm-word">' . esc_html( html_entity_decode( $term->name ) ) . '</span>';
}

/** Alle Marken mit Zählern, Bereichen und Vorschaubild (für die Seite „Marken“). */
function prezium_brands_data() {
	$terms = get_terms( array( 'taxonomy' => 'prezium_marke', 'hide_empty' => false ) );
	if ( is_wp_error( $terms ) || ! $terms ) {
		return array();
	}
	$pids = get_posts( array( 'post_type' => 'prezium_product', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids', 'no_found_rows' => true ) );
	$areas = array(); // Marke => array( slug => Name ).
	if ( $pids ) {
		$bt = wp_get_object_terms( $pids, 'prezium_marke', array( 'fields' => 'all_with_object_id' ) );
		$at = wp_get_object_terms( $pids, 'prezium_bereich', array( 'fields' => 'all_with_object_id' ) );
		$by_prod = array();
		foreach ( (array) $at as $a ) {
			$by_prod[ $a->object_id ][ $a->slug ] = $a->name;
		}
		foreach ( (array) $bt as $b ) {
			if ( isset( $by_prod[ $b->object_id ] ) ) {
				foreach ( $by_prod[ $b->object_id ] as $slug => $name ) {
					$areas[ $b->term_id ][ $slug ] = $name;
				}
			}
		}
	}
	$out = array();
	foreach ( $terms as $t ) {
		$meta  = prezium_brand_meta( $t->term_id );
		$thumb = 0;
		if ( $meta['featured'] ) {
			$p = get_posts( array( 'post_type' => 'prezium_product', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => '_thumbnail_id', 'tax_query' => array( array( 'taxonomy' => 'prezium_marke', 'terms' => $t->term_id ) ) ) );
			$thumb = $p ? (int) get_post_thumbnail_id( $p[0] ) : 0;
		}
		$out[] = array(
			'term'  => $t,
			'meta'  => $meta,
			'count' => (int) $t->count,
			'areas' => isset( $areas[ $t->term_id ] ) ? $areas[ $t->term_id ] : array(),
			'thumb' => $thumb,
		);
	}
	usort( $out, function ( $a, $b ) {
		return $a['count'] === $b['count'] ? strcasecmp( $a['term']->name, $b['term']->name ) : $b['count'] - $a['count'];
	} );
	return $out;
}

/* ---------- Felder im Editor ---------- */

function prezium_brand_fields_html( $term = null ) {
	$m = $term ? prezium_brand_meta( $term->term_id ) : array( 'logo' => 0, 'invert' => false, 'url' => '', 'country' => '', 'featured' => false );
	wp_nonce_field( 'prezium_brand_save', 'prezium_brand_nonce' );
	$thumb = $m['logo'] ? wp_get_attachment_image( $m['logo'], 'medium', false, array( 'style' => 'max-width:200px;height:auto;background:#555;padding:10px;display:block;' ) ) : '';
	?>
	<div class="prez-brand-logo">
		<input type="hidden" name="prez_logo" id="prez_logo" value="<?php echo (int) $m['logo']; ?>">
		<div id="prez_logo_preview"><?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<p>
			<button type="button" class="button" id="prez_logo_pick"><?php esc_html_e( 'Logo wählen', 'prezium' ); ?></button>
			<button type="button" class="button-link" id="prez_logo_remove"><?php esc_html_e( 'Entfernen', 'prezium' ); ?></button>
		</p>
	</div>
	<p>
		<label><input type="checkbox" name="prez_logo_invert" value="1" <?php checked( $m['invert'] ); ?>> <?php esc_html_e( 'Logo weiß einfärben (für dunkle Logos auf dunklem Hintergrund)', 'prezium' ); ?></label>
	</p>
	<p>
		<label for="prez_url"><?php esc_html_e( 'Webseite', 'prezium' ); ?></label><br>
		<input type="url" name="prez_url" id="prez_url" class="regular-text" value="<?php echo esc_attr( $m['url'] ); ?>" placeholder="https://">
	</p>
	<p>
		<label for="prez_country"><?php esc_html_e( 'Land', 'prezium' ); ?></label><br>
		<input type="text" name="prez_country" id="prez_country" class="regular-text" value="<?php echo esc_attr( $m['country'] ); ?>">
	</p>
	<p>
		<label><input type="checkbox" name="prez_featured" value="1" <?php checked( $m['featured'] ); ?>> <?php esc_html_e( 'Hervorgehoben (große Karte oben auf der Seite „Marken“)', 'prezium' ); ?></label>
	</p>
	<?php
}

add_action( 'prezium_marke_add_form_fields', function () {
	echo '<h2>' . esc_html__( 'Logo und Angaben', 'prezium' ) . '</h2>';
	prezium_brand_fields_html( null );
} );

add_action( 'prezium_marke_edit_form_fields', function ( $term ) {
	echo '<tr class="form-field"><th scope="row">' . esc_html__( 'Logo und Angaben', 'prezium' ) . '</th><td>';
	prezium_brand_fields_html( $term );
	echo '</td></tr>';
} );

function prezium_brand_save( $term_id ) {
	if ( ! isset( $_POST['prezium_brand_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prezium_brand_nonce'] ) ), 'prezium_brand_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_term', $term_id ) ) {
		return;
	}
	$logo = isset( $_POST['prez_logo'] ) ? absint( $_POST['prez_logo'] ) : 0;
	if ( $logo && ! wp_attachment_is_image( $logo ) && 'image/svg+xml' !== get_post_mime_type( $logo ) ) {
		$logo = 0;
	}
	update_term_meta( $term_id, '_prez_logo', $logo );
	update_term_meta( $term_id, '_prez_logo_invert', empty( $_POST['prez_logo_invert'] ) ? 0 : 1 );
	update_term_meta( $term_id, '_prez_url', isset( $_POST['prez_url'] ) ? esc_url_raw( wp_unslash( $_POST['prez_url'] ) ) : '' );
	update_term_meta( $term_id, '_prez_country', isset( $_POST['prez_country'] ) ? sanitize_text_field( wp_unslash( $_POST['prez_country'] ) ) : '' );
	update_term_meta( $term_id, '_prez_featured', empty( $_POST['prez_featured'] ) ? 0 : 1 );
}
add_action( 'created_prezium_marke', 'prezium_brand_save' );
add_action( 'edited_prezium_marke', 'prezium_brand_save' );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( ! in_array( $hook, array( 'edit-tags.php', 'term.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'prezium_marke' !== $screen->taxonomy ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'prezium-admin-brand', get_template_directory_uri() . '/assets/js/admin-brand.js', array( 'jquery' ), (string) filemtime( get_template_directory() . '/assets/js/admin-brand.js' ), true );
} );

/** Spalte „Logo“ in der Markenliste. */
add_filter( 'manage_edit-prezium_marke_columns', function ( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		if ( 'name' === $k ) {
			$new['prez_logo'] = __( 'Logo', 'prezium' );
		}
		$new[ $k ] = $v;
	}
	return $new;
} );
add_filter( 'manage_prezium_marke_custom_column', function ( $content, $col, $term_id ) {
	if ( 'prez_logo' !== $col ) {
		return $content;
	}
	$m = prezium_brand_meta( $term_id );
	if ( $m['logo'] ) {
		return wp_get_attachment_image( $m['logo'], array( 60, 60 ), false, array( 'style' => 'max-width:60px;height:auto;background:#555;padding:4px;' ) ) . ( $m['featured'] ? ' ★' : '' );
	}
	return '<span style="color:#999">—</span>' . ( $m['featured'] ? ' ★' : '' );
}, 10, 3 );

/* ---------- Seitenklasse, SVG-Logos ---------- */

add_filter( 'body_class', function ( $classes ) {
	if ( is_page( 'marken' ) ) {
		$classes[] = 'prez-marken';
	}
	return $classes;
} );

/** SVG-Logos nur für Administratoren, mit einfacher Prüfung auf aktive Inhalte. */
add_filter( 'upload_mimes', function ( $m ) {
	if ( current_user_can( 'manage_options' ) ) {
		$m['svg'] = 'image/svg+xml';
	}
	return $m;
} );
add_filter( 'wp_check_filetype_and_ext', function ( $data, $file, $filename ) {
	if ( preg_match( '/\.svg$/i', $filename ) && current_user_can( 'manage_options' ) ) {
		return array( 'ext' => 'svg', 'type' => 'image/svg+xml', 'proper_filename' => $data['proper_filename'] );
	}
	return $data;
}, 10, 3 );
add_filter( 'wp_handle_upload_prefilter', function ( $file ) {
	if ( preg_match( '/\.svg$/i', $file['name'] ) ) {
		$content = file_get_contents( $file['tmp_name'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		if ( false === $content || preg_match( '/<script|<foreignObject|<iframe|<!ENTITY|\son[a-z]+\s*=|javascript:|data:text\/html/i', $content ) ) {
			$file['error'] = __( 'Diese SVG-Datei enthält aktive Inhalte und wurde abgelehnt.', 'prezium' );
		}
	}
	return $file;
} );

/* ---------- Einmalig: Demo-Marken hervorheben ---------- */

add_action( 'init', function () {
	if ( get_option( 'prezium_brands_seed1' ) ) {
		return;
	}
	foreach ( array( 'aeterno-parquet', 'ravaioli-falegnami', 'lumen-atelier' ) as $slug ) {
		$t = get_term_by( 'slug', $slug, 'prezium_marke' );
		if ( $t ) {
			update_term_meta( $t->term_id, '_prez_featured', 1 );
		}
	}
	update_option( 'prezium_brands_seed1', 1 );
}, 20 );

/* ---------- Logos aus dem Theme übernehmen (assets/brands/<slug>.svg|png|webp|jpg) ---------- */

add_action( 'admin_menu', function () {
	add_submenu_page( 'edit.php?post_type=prezium_product', __( 'Marken-Logos', 'prezium' ), __( 'Marken-Logos', 'prezium' ), 'manage_options', 'prezium-brand-logos', 'prezium_render_brand_logos' );
} );

function prezium_brand_logo_file( $slug ) {
	foreach ( array( 'svg', 'webp', 'png', 'jpg' ) as $ext ) {
		$rel = 'assets/brands/' . $slug . '.' . $ext;
		if ( file_exists( get_template_directory() . '/' . $rel ) ) {
			return $rel;
		}
	}
	return '';
}

function prezium_render_brand_logos() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$msg = '';
	if ( isset( $_POST['prezium_brand_logos'] ) && check_admin_referer( 'prezium_brand_logos' ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$n = 0;
		foreach ( get_terms( array( 'taxonomy' => 'prezium_marke', 'hide_empty' => false ) ) as $t ) {
			if ( get_term_meta( $t->term_id, '_prez_logo', true ) ) {
				continue;
			}
			$rel = prezium_brand_logo_file( $t->slug );
			if ( ! $rel ) {
				continue;
			}
			$id = prezium_theme_attach( $rel, 0 );
			if ( $id ) {
				update_term_meta( $t->term_id, '_prez_logo', $id );
				if ( ! empty( $_POST['invert'] ) ) {
					update_term_meta( $t->term_id, '_prez_logo_invert', 1 );
				}
				$n++;
			}
		}
		/* translators: %d: number of logos */
		$msg = '<div class="notice notice-success"><p>' . esc_html( sprintf( __( '%d Logos übernommen.', 'prezium' ), $n ) ) . '</p></div>';
	}
	echo '<div class="wrap"><h1>' . esc_html__( 'Marken-Logos', 'prezium' ) . '</h1>' . $msg; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<p>' . esc_html__( 'Legt man Dateien in wp-content/themes/prezium/assets/brands/ ab (Name = Slug der Marke, z. B. tacchini.svg), übernimmt der Knopf sie in die Mediathek und verknüpft sie mit der Marke. Marken mit Logo werden übersprungen.', 'prezium' ) . '</p>';
	echo '<table class="widefat striped" style="max-width:640px"><thead><tr><th>' . esc_html__( 'Marke', 'prezium' ) . '</th><th>Slug</th><th>' . esc_html__( 'Logo', 'prezium' ) . '</th><th>' . esc_html__( 'Datei im Theme', 'prezium' ) . '</th></tr></thead><tbody>';
	foreach ( get_terms( array( 'taxonomy' => 'prezium_marke', 'hide_empty' => false ) ) as $t ) {
		$has = (bool) get_term_meta( $t->term_id, '_prez_logo', true );
		$f   = prezium_brand_logo_file( $t->slug );
		echo '<tr><td>' . esc_html( html_entity_decode( $t->name ) ) . '</td><td>' . esc_html( $t->slug ) . '</td><td>' . ( $has ? '✓' : '—' ) . '</td><td>' . esc_html( $f ? basename( $f ) : '' ) . '</td></tr>';
	}
	echo '</tbody></table>';
	echo '<form method="post" style="margin-top:1rem">';
	wp_nonce_field( 'prezium_brand_logos' );
	echo '<p><label><input type="checkbox" name="invert" value="1"> ' . esc_html__( 'Übernommene Logos weiß einfärben (für dunkle Logos)', 'prezium' ) . '</label></p>';
	echo '<p><button class="button button-primary" name="prezium_brand_logos" value="1">' . esc_html__( 'Logos aus dem Theme übernehmen', 'prezium' ) . '</button></p></form></div>';
}
