<?php
/**
 * Katalog-Raster mit Filtern. Wird von allen Katalog-Archiven benutzt.
 */
?>
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
						<?php if ( prezium_is_test() ) : ?><span class="badge-test">TEST</span><?php endif; ?>
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
