<?php
get_header();
$brand = get_queried_object();
$bm    = $brand ? prezium_brand_meta( $brand->term_id ) : array();
?>
<?php if ( $brand ) : ?>
	<section class="section brand-head">
		<p class="note"><a href="<?php echo esc_url( home_url( '/marken/' ) ); ?>">&larr; <?php esc_html_e( 'Alle Marken', 'prezium' ); ?></a></p>
		<div class="brand-head-mark"><?php echo prezium_brand_mark( $brand, 'medium' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php if ( term_description() ) : ?><div class="brand-intro"><?php echo wp_kses_post( term_description() ); ?></div><?php endif; ?>
		<?php if ( ! empty( $bm['country'] ) || ! empty( $bm['url'] ) ) : ?>
			<p class="note">
				<?php echo esc_html( $bm['country'] ); ?>
				<?php if ( ! empty( $bm['country'] ) && ! empty( $bm['url'] ) ) : ?> · <?php endif; ?>
				<?php if ( ! empty( $bm['url'] ) ) : ?><a href="<?php echo esc_url( $bm['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( preg_replace( '#^https?://(www\.)?#', '', untrailingslashit( $bm['url'] ) ) ); ?></a><?php endif; ?>
			</p>
		<?php endif; ?>
	</section>
<?php endif; ?>
<?php
get_template_part( 'template-parts/catalog-grid' );
get_footer();
