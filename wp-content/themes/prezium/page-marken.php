<?php
/**
 * Seite "Marken": Logo-Wand mit hervorgehobenen Marken, Filter nach Bereich und Link zu den Produkten.
 */
get_header();

$brands   = prezium_brands_data();
$featured = array_values( array_filter( $brands, function ( $b ) {
	return $b['meta']['featured'];
} ) );
$all_areas = array();
foreach ( $brands as $b ) {
	foreach ( $b['areas'] as $slug => $name ) {
		$all_areas[ $slug ] = $name;
	}
}
$order = array( 'tueren', 'turen', 'parkett', 'terrassendielen', 'fenster', 'licht', 'moebel', 'mobel', 'sonstiges' );
uksort( $all_areas, function ( $a, $b ) use ( $order ) {
	$pa = array_search( $a, $order, true );
	$pb = array_search( $b, $order, true );
	return ( false === $pa ? 99 : $pa ) - ( false === $pb ? 99 : $pb );
} );
$total = count( $brands );
$inq   = __( 'Markenanfrage', 'prezium' );
?>
<div class="bm">
	<header class="bm-head">
		<p class="bm-eyebrow"><?php esc_html_e( 'Partner & Hersteller', 'prezium' ); ?></p>
		<div class="bm-title">
			<h1><?php esc_html_e( 'Marken', 'prezium' ); ?></h1>
			<p><?php esc_html_e( 'Hersteller, mit denen wir arbeiten. Jede Marke führt direkt zu ihren Produkten im Katalog.', 'prezium' ); ?></p>
		</div>
	</header>

	<?php if ( $featured ) : ?>
	<section class="bm-featured" aria-label="<?php esc_attr_e( 'Ausgewählte Marken', 'prezium' ); ?>">
		<?php foreach ( array_slice( $featured, 0, 3 ) as $b ) : $t = $b['term']; ?>
			<a class="bm-feat" href="<?php echo esc_url( get_term_link( $t ) ); ?>">
				<div class="bm-feat-img">
					<?php if ( $b['thumb'] ) { echo wp_get_attachment_image( $b['thumb'], 'large', false, array( 'loading' => 'lazy' ) ); } ?>
					<div class="bm-feat-mark"><?php echo prezium_brand_mark( $t, 'medium' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				</div>
				<div class="bm-feat-body">
					<?php if ( $t->description ) : ?><p><?php echo esc_html( $t->description ); ?></p><?php endif; ?>
					<div class="bm-feat-foot">
						<span><?php echo esc_html( sprintf( /* translators: %d: number of products */ _n( '%d Produkt', '%d Produkte', $b['count'], 'prezium' ), $b['count'] ) ); ?></span>
						<span class="bm-more"><?php esc_html_e( 'Ansehen', 'prezium' ); ?> &rarr;</span>
					</div>
				</div>
			</a>
		<?php endforeach; ?>
	</section>
	<?php endif; ?>

	<section class="bm-wall">
		<div class="bm-bar">
			<?php if ( $all_areas ) : ?>
			<div class="bm-filter" role="group" aria-label="<?php esc_attr_e( 'Nach Bereich filtern', 'prezium' ); ?>">
				<button type="button" class="bm-chip is-active" data-filter="*"><?php esc_html_e( 'Alle', 'prezium' ); ?></button>
				<?php foreach ( $all_areas as $slug => $name ) : ?>
					<button type="button" class="bm-chip" data-filter="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></button>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<span class="bm-count" data-bm-count><?php echo esc_html( sprintf( /* translators: %d: number of brands */ _n( '%d Marke', '%d Marken', $total, 'prezium' ), $total ) ); ?></span>
		</div>

		<div class="bm-grid">
			<?php foreach ( $brands as $b ) : $t = $b['term']; ?>
				<a class="bm-cell" href="<?php echo esc_url( get_term_link( $t ) ); ?>" data-areas="<?php echo esc_attr( implode( ' ', array_keys( $b['areas'] ) ) ); ?>">
					<span class="bm-cell-mark"><?php echo prezium_brand_mark( $t, 'medium' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span class="bm-cell-meta"><?php echo esc_html( sprintf( /* translators: %d: number of products */ _n( '%d Produkt', '%d Produkte', $b['count'], 'prezium' ), $b['count'] ) ); ?></span>
					<span class="bm-arrow" aria-hidden="true">&rarr;</span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="bm-cta">
		<h2><?php esc_html_e( 'Ihre Marke ist nicht dabei?', 'prezium' ); ?></h2>
		<p><?php esc_html_e( 'Wir beschaffen Produkte auf Anfrage, auch von Herstellern, die hier nicht gelistet sind. Nennen Sie uns Ihren Wunsch.', 'prezium' ); ?></p>
		<button class="bm-btn" type="button" data-inquiry="<?php echo esc_attr( $inq ); ?>"><?php esc_html_e( 'Anfrage senden', 'prezium' ); ?></button>
	</section>
</div>
<?php get_footer(); ?>
