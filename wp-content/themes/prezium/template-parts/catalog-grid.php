<?php
/**
 * Katalog-Raster mit Filtern. Wird von allen Katalog-Archiven benutzt.
 */
?>
<?php
// Einstieg über Bereiche: nur auf der Katalog-Startseite, ohne Suche und Filter.
// phpcs:disable WordPress.Security.NonceVerification
$has_filter = ! empty( $_GET['s'] ) || ! empty( $_GET['bereich'] ) || ! empty( $_GET['marke'] ) || ! empty( $_GET['material'] );
// phpcs:enable
if ( is_post_type_archive( 'prezium_product' ) && ! $has_filter && ! is_paged() ) :
	$terms = get_terms( array( 'taxonomy' => 'prezium_bereich', 'hide_empty' => true ) );
	if ( $terms && ! is_wp_error( $terms ) ) :
		$prio = array( 'tueren' => 1, 'turen' => 1, 'parkett' => 2, 'fenster' => 3, 'terrassendielen' => 4, 'moebel' => 5, 'mobel' => 5, 'licht' => 6, 'sonstiges' => 9 );
		usort( $terms, function ( $a, $b ) use ( $prio ) {
			$pa = isset( $prio[ $a->slug ] ) ? $prio[ $a->slug ] : 8;
			$pb = isset( $prio[ $b->slug ] ) ? $prio[ $b->slug ] : 8;
			return $pa === $pb ? strcmp( $a->name, $b->name ) : $pa - $pb;
		} );
		?>
<section class="section bereich-tiles" aria-label="<?php esc_attr_e( 'Bereiche', 'prezium' ); ?>">
	<h2 class="page-title"><?php esc_html_e( 'Nach Bereich entdecken', 'prezium' ); ?></h2>
	<div class="tiles">
		<?php
		foreach ( $terms as $t ) :
			$img = get_posts( array(
				'post_type'      => 'prezium_product',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_thumbnail_id',
				'tax_query'      => array( array( 'taxonomy' => 'prezium_bereich', 'terms' => $t->term_id ) ),
			) );
			?>
			<a class="tile" href="<?php echo esc_url( get_term_link( $t ) ); ?>">
				<?php if ( $img ) { echo get_the_post_thumbnail( $img[0], 'medium_large', array( 'class' => 'tile-img', 'loading' => 'lazy' ) ); } else { echo '<span class="tile-img"></span>'; } ?>
				<span class="tile-label">
					<strong><?php echo esc_html( $t->name ); ?></strong>
					<small><?php echo esc_html( sprintf( /* translators: %d: number of products */ _n( '%d Produkt', '%d Produkte', (int) $t->count, 'prezium' ), (int) $t->count ) ); ?></small>
				</span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
	<?php endif; ?>
<?php endif; ?>
<section class="section">
	<h1 class="page-title">
		<?php
		if ( is_tax() ) {
			single_term_title();
		} else {
			esc_html_e( 'Katalog', 'prezium' );
		}
		?>
	</h1>

	<?php get_template_part( 'template-parts/catalog-filters' ); ?>

	<?php if ( have_posts() ) : ?>
		<div class="grid">
			<?php
			while ( have_posts() ) :
				the_post();
				$sku    = get_post_meta( get_the_ID(), '_prez_sku', true );
				$marken = get_the_terms( get_the_ID(), 'prezium_marke' );
				$marke  = ( $marken && ! is_wp_error( $marken ) ) ? $marken[0]->name : '';
				?>
				<article class="card">
					<a href="<?php the_permalink(); ?>">
						<?php prezium_badge(); ?>
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'class' => 'ph', 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<div class="ph"></div>
						<?php endif; ?>
						<?php the_title(); ?>
						<small><?php echo esc_html( trim( $marke . ( $marke && $sku ? ' · ' : '' ) . $sku ) ); ?></small>
					</a>
				</article>
			<?php endwhile; ?>
		</div>
		<div class="pagination"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
	<?php else : ?>
		<p class="note"><?php esc_html_e( 'Keine Treffer. Ändern Sie Suche oder Filter.', 'prezium' ); ?></p>
	<?php endif; ?>
</section>
