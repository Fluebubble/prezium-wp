<?php
get_header();

while ( have_posts() ) :
	the_post();
	$id      = get_the_ID();
	$sku     = get_post_meta( $id, '_prez_sku', true );
	$marken  = get_the_terms( $id, 'prezium_marke' );
	$bereich = get_the_terms( $id, 'prezium_bereich' );
	$marke   = ( $marken && ! is_wp_error( $marken ) ) ? $marken[0] : null;
	$ber     = ( $bereich && ! is_wp_error( $bereich ) ) ? $bereich[0] : null;
	$ref     = get_the_title() . ( $sku ? ' (' . $sku . ')' : '' );
	$specs   = array();
	foreach ( prezium_product_fields() as $key => $label ) {
		$val = get_post_meta( $id, $key, true );
		if ( $val ) {
			$specs[ $label ] = $val;
		}
	}
	$docs = array();
	foreach ( prezium_product_documents() as $key => $label ) {
		$url = get_post_meta( $id, $key, true );
		if ( $url ) {
			$docs[ $label ] = $url;
		}
	}
	?>
<?php
	// Rückweg zum Projekt: entweder über ?projekt=ID (Klick aus dem Projekt) oder über die Projekt-Verknüpfung.
	// phpcs:ignore WordPress.Security.NonceVerification
	$from = isset( $_GET['projekt'] ) ? absint( $_GET['projekt'] ) : 0;
	$proj = ( $from && 'prezium_project' === get_post_type( $from ) && 'publish' === get_post_status( $from ) ) ? get_post( $from ) : null;
	$via  = (bool) $proj;
	if ( ! $proj ) {
		$pterms = get_the_terms( $id, 'prezium_projekt' );
		if ( $pterms && ! is_wp_error( $pterms ) ) {
			$found = get_posts( array( 'post_type' => 'prezium_project', 'name' => $pterms[0]->slug, 'posts_per_page' => 1 ) );
			$proj  = $found ? $found[0] : null;
		}
	}
	?>
<section class="section">
	<p class="note back-links">
		<a href="<?php echo esc_url( get_post_type_archive_link( 'prezium_product' ) ); ?>">&larr; <?php esc_html_e( 'Zurück zum Katalog', 'prezium' ); ?></a>
		<?php if ( $proj ) : ?>
			<a class="back-project" href="<?php echo esc_url( get_permalink( $proj ) . '#produkt-' . $id ); ?>">&larr; <?php echo esc_html( sprintf( /* translators: %s: project title */ $via ? __( 'Zurück zum Projekt: %s', 'prezium' ) : __( 'Verwendet im Projekt: %s', 'prezium' ), get_the_title( $proj ) ) ); ?></a>
		<?php endif; ?>
	</p>

	<div class="prod">
		<div class="prod-media">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large', array( 'class' => 'ph' ) ); ?>
			<?php else : ?>
				<div class="ph"></div>
			<?php endif; ?>
		</div>

		<div class="prod-info">
			<p class="note">
				<?php if ( $marke ) : ?><a href="<?php echo esc_url( get_term_link( $marke ) ); ?>"><?php echo esc_html( $marke->name ); ?></a><?php endif; ?>
				<?php if ( $marke && $ber ) : ?> · <?php endif; ?>
				<?php if ( $ber ) : ?><a href="<?php echo esc_url( get_term_link( $ber ) ); ?>"><?php echo esc_html( $ber->name ); ?></a><?php endif; ?>
			</p>
			<h1 class="page-title"><?php the_title(); ?> <?php prezium_badge( true ); ?></h1>
			<div class="prod-text"><?php the_content(); ?></div>

			<?php if ( $specs ) : ?>
				<dl class="specs">
					<?php foreach ( $specs as $label => $val ) : ?>
						<dt><?php echo esc_html( $label ); ?></dt><dd><?php echo esc_html( $val ); ?></dd>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>

			<button class="btn" type="button" data-inquiry="<?php echo esc_attr( $ref ); ?>"><?php esc_html_e( 'Preis anfragen', 'prezium' ); ?></button>

			<?php if ( $docs ) : ?>
				<div class="downloads">
					<?php if ( prezium_user_can_download() ) : ?>
						<?php foreach ( $docs as $label => $url ) : ?>
							<a class="btn ghost" href="<?php echo esc_url( $url ); ?>" download><?php echo esc_html( $label ); ?></a>
						<?php endforeach; ?>
					<?php else : ?>
						<?php foreach ( array_keys( $docs ) as $label ) : ?>
							<span class="btn ghost is-disabled" aria-disabled="true"><?php echo esc_html( $label ); ?></span>
						<?php endforeach; ?>
						<p class="note"><?php esc_html_e( 'Downloads sind für registrierte Architekten und Designer freigeschaltet.', 'prezium' ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
	<?php
endwhile;

get_footer();
