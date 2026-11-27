<?php
get_header();
while ( have_posts() ) :
	the_post();
	$facts = get_post_meta( get_the_ID(), '_prez_facts', true );
	$prods = prezium_project_products( get_the_ID() );
	?>
<section class="section">
	<p class="note"><a href="<?php echo esc_url( home_url( '/#projekte' ) ); ?>">&larr; <?php esc_html_e( 'Alle Projekte', 'prezium' ); ?></a></p>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="proj-hero"><?php the_post_thumbnail( 'full', array( 'class' => 'ph' ) ); ?></div>
	<?php endif; ?>
	<h1 class="page-title"><?php the_title(); ?> <span class="badge-test badge-demoproject badge-inline">DEMO</span></h1>
	<div class="prod">
		<div class="prod-text"><?php the_content(); ?></div>
		<?php if ( is_array( $facts ) && $facts ) : ?>
			<dl class="specs">
				<?php foreach ( $facts as $k => $v ) : ?>
					<dt><?php echo esc_html( $k ); ?></dt><dd><?php echo esc_html( $v ); ?></dd>
				<?php endforeach; ?>
			</dl>
		<?php endif; ?>
	</div>

	<?php if ( $prods->have_posts() ) : ?>
		<h2 class="page-title"><?php esc_html_e( 'Verwendete Produkte', 'prezium' ); ?></h2>
		<div class="grid">
			<?php
			while ( $prods->have_posts() ) :
				$prods->the_post();
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
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	<?php endif; ?>
</section>
	<?php
endwhile;
get_footer();
