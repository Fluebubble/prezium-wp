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

<section class="section">
	<h2><?php esc_html_e( 'Ausgewählte Produkte', 'prezium' ); ?></h2>
	<div class="grid">
		<?php
		$featured = new WP_Query( array( 'post_type' => 'prezium_product', 'posts_per_page' => 4, 'orderby' => 'rand' ) );
		while ( $featured->have_posts() ) :
			$featured->the_post();
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
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</section>

<section class="section values">
	<h2><?php esc_html_e( 'Warum Prezium', 'prezium' ); ?></h2>
	<div class="values-grid">
		<div><h3><?php esc_html_e( 'Kuratierte Auswahl', 'prezium' ); ?></h3><p><?php esc_html_e( 'Nur Hersteller, die wir kennen und deren Qualität wir selbst geprüft haben.', 'prezium' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Beratung auf Augenhöhe', 'prezium' ); ?></h3><p><?php esc_html_e( 'Preis anfragen, Muster bestellen, Details klären: persönlich und ohne Warenkorb.', 'prezium' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Für Fachleute', 'prezium' ); ?></h3><p><?php esc_html_e( 'Preislisten, technische Daten und CAD/BIM-Modelle im geschützten Bereich.', 'prezium' ); ?></p></div>
	</div>
</section>

<section class="section">
	<h2><?php esc_html_e( 'Unsere Marken', 'prezium' ); ?></h2>
	<p class="brand-strip">
		<?php
		$marken = get_terms( array( 'taxonomy' => 'prezium_marke', 'hide_empty' => false ) );
		if ( ! is_wp_error( $marken ) ) :
			foreach ( $marken as $marke ) :
				?>
				<a href="<?php echo esc_url( get_term_link( $marke ) ); ?>"><?php echo esc_html( $marke->name ); ?></a>
			<?php endforeach; endif; ?>
	</p>
</section>

<section class="section cta">
	<h2><?php esc_html_e( 'Architekt oder Designer?', 'prezium' ); ?></h2>
	<p><?php esc_html_e( 'Registrieren Sie sich für Preislisten, technische Dokumentation und CAD/BIM-Daten.', 'prezium' ); ?></p>
	<a class="btn" href="<?php echo esc_url( home_url( '/architekten-designer/' ) ); ?>"><?php esc_html_e( 'Mehr erfahren', 'prezium' ); ?></a>
</section>

<?php get_footer(); ?>
