<?php get_header(); ?>

<section class="hero">
	<div>
		<h1><?php echo esc_html( get_bloginfo( 'description' ) ?: 'Der Sieg liebt Präzision' ); ?></h1>
		<p><?php esc_html_e( 'Hochwertige Türen, Parkett, Fenster und Licht für Räume mit Haltung.', 'prezium' ); ?></p>
		<a class="btn" href="<?php echo esc_url( get_post_type_archive_link( 'prezium_product' ) ); ?>"><?php esc_html_e( 'Katalog ansehen', 'prezium' ); ?></a>
	</div>
</section>

<section class="section">
	<h2><?php esc_html_e( 'Unsere Bereiche', 'prezium' ); ?></h2>
	<div class="grid">
		<?php
		$bereiche = get_terms( array( 'taxonomy' => 'prezium_bereich', 'hide_empty' => false ) );
		if ( ! is_wp_error( $bereiche ) ) :
			foreach ( $bereiche as $bereich ) :
				?>
				<div class="card">
					<a href="<?php echo esc_url( get_term_link( $bereich ) ); ?>">
						<div class="ph"></div>
						<?php echo esc_html( $bereich->name ); ?>
					</a>
				</div>
				<?php
			endforeach;
		endif;
		?>
	</div>
</section>

<?php get_footer(); ?>
