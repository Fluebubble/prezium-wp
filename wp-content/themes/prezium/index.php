<?php get_header(); ?>

<section class="section">
	<?php if ( is_post_type_archive( 'prezium_product' ) ) : ?>
		<h2><?php esc_html_e( 'Katalog', 'prezium' ); ?></h2>
	<?php elseif ( is_tax() ) : ?>
		<h2><?php single_term_title(); ?></h2>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<div class="grid">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article class="card">
					<a href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'class' => 'ph', 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<div class="ph"></div>
						<?php endif; ?>
						<?php the_title(); ?>
					</a>
				</article>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<p><?php esc_html_e( 'Keine Einträge gefunden.', 'prezium' ); ?></p>
	<?php endif; ?>
</section>

<?php get_footer(); ?>
