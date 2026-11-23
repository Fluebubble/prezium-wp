<?php
/**
 * Seite "Marken": alle Marken mit Beschreibung und Link zu den Produkten.
 */
get_header();
?>
<section class="section">
	<h1 class="page-title"><?php esc_html_e( 'Marken', 'prezium' ); ?></h1>
	<div class="brand-list">
		<?php
		$marken = get_terms( array( 'taxonomy' => 'prezium_marke', 'hide_empty' => false ) );
		if ( ! is_wp_error( $marken ) ) :
			foreach ( $marken as $marke ) :
				?>
				<article class="brand-item">
					<h2><a href="<?php echo esc_url( get_term_link( $marke ) ); ?>"><?php echo esc_html( $marke->name ); ?></a></h2>
					<p><?php echo esc_html( $marke->description ); ?></p>
					<a class="btn ghost" href="<?php echo esc_url( get_term_link( $marke ) ); ?>"><?php esc_html_e( 'Produkte ansehen', 'prezium' ); ?></a>
				</article>
				<?php
			endforeach;
		endif;
		?>
	</div>
</section>
<?php get_footer(); ?>
