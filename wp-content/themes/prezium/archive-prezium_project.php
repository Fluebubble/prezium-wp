<?php
/**
 * Projekte: Übersicht aller Referenzprojekte.
 */
get_header();

$areas = array();
$items = array();
while ( have_posts() ) {
	the_post();
	$facts  = get_post_meta( get_the_ID(), '_prez_facts', true );
	$facts  = is_array( $facts ) ? $facts : array();
	$slugs  = array();
	$bereich = isset( $facts['Bereiche'] ) ? $facts['Bereiche'] : '';
	foreach ( array_filter( array_map( 'trim', explode( ',', $bereich ) ) ) as $a ) {
		$areas[ sanitize_title( $a ) ] = $a;
		$slugs[]                       = sanitize_title( $a );
	}
	$lead = '';
	if ( preg_match( '/<p[^>]*>(.*?)<\/p>/s', get_the_content(), $m ) ) {
		$lead = wp_trim_words( wp_strip_all_tags( $m[1] ), 26 );
	}
	$items[] = array(
		'id'    => get_the_ID(),
		'url'   => get_permalink(),
		'title' => get_the_title(),
		'areas' => get_the_excerpt(),
		'slugs' => $slugs,
		'facts' => $facts,
		'lead'  => $lead,
	);
}
$count = count( $items );
?>
<div class="pa">
	<header class="pa-head">
		<p class="pj-eyebrow"><?php esc_html_e( 'Referenzen', 'prezium' ); ?></p>
		<div class="pa-title">
			<h1><?php esc_html_e( 'Projekte', 'prezium' ); ?></h1>
			<p><?php esc_html_e( 'Räume, in denen unsere Materialien zu Hause sind. Jedes Projekt zeigt, welche Produkte verbaut wurden, und führt direkt in den Katalog.', 'prezium' ); ?></p>
		</div>
		<div class="pa-bar">
			<?php if ( $areas ) : ?>
			<div class="pa-filter" role="group" aria-label="<?php esc_attr_e( 'Nach Bereich filtern', 'prezium' ); ?>">
				<button type="button" class="pa-chip is-active" data-filter="*"><?php esc_html_e( 'Alle', 'prezium' ); ?></button>
				<?php foreach ( $areas as $slug => $name ) : ?>
					<button type="button" class="pa-chip" data-filter="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></button>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<span class="pa-count" data-count><?php echo esc_html( sprintf( /* translators: %d: number of projects */ _n( '%d Projekt', '%d Projekte', $count, 'prezium' ), $count ) ); ?></span>
		</div>
	</header>

	<div class="pa-list">
		<?php foreach ( $items as $i => $it ) : ?>
			<article class="pa-item" data-areas="<?php echo esc_attr( implode( ' ', $it['slugs'] ) ); ?>">
				<a class="pa-img" href="<?php echo esc_url( $it['url'] ); ?>" tabindex="-1" aria-hidden="true">
					<?php echo get_the_post_thumbnail( $it['id'], 'large', array( 'loading' => $i < 1 ? 'eager' : 'lazy', 'sizes' => '(min-width: 900px) 56vw, 100vw' ) ); ?>
					<span class="pj-demo">DEMO</span>
				</a>
				<div class="pa-body">
					<p class="pa-meta"><span class="pa-no"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><span class="pa-line"></span><span><?php echo esc_html( $it['areas'] ); ?></span></p>
					<h2><a href="<?php echo esc_url( $it['url'] ); ?>"><?php echo esc_html( $it['title'] ); ?></a></h2>
					<?php if ( $it['lead'] ) : ?><p class="pa-lead"><?php echo esc_html( $it['lead'] ); ?></p><?php endif; ?>
					<dl class="pa-facts">
						<?php foreach ( array( 'Typ', 'Ort', 'Fläche' ) as $k ) : if ( ! empty( $it['facts'][ $k ] ) ) : ?>
							<div><dt><?php echo esc_html( $k ); ?></dt><dd><?php echo esc_html( $it['facts'][ $k ] ); ?></dd></div>
						<?php endif; endforeach; ?>
					</dl>
					<a class="pj-link" href="<?php echo esc_url( $it['url'] ); ?>"><?php esc_html_e( 'Projekt lesen', 'prezium' ); ?> &rarr;</a>
				</div>
			</article>
		<?php endforeach; ?>
		<?php if ( ! $items ) : ?>
			<p class="note"><?php esc_html_e( 'Noch keine Projekte vorhanden.', 'prezium' ); ?></p>
		<?php endif; ?>
	</div>

	<section class="pj-cta pa-cta">
		<h2 class="serif"><?php esc_html_e( 'Ihr Projekt mit Prezium', 'prezium' ); ?></h2>
		<p><?php esc_html_e( 'Architekten, Designer und Bauherren beraten wir persönlich. Schildern Sie uns Ihr Vorhaben, wir melden uns mit Vorschlägen und Preisen.', 'prezium' ); ?></p>
		<button class="pj-btn lg" type="button" data-inquiry="<?php echo esc_attr__( 'Projektanfrage', 'prezium' ); ?>"><?php esc_html_e( 'Projektanfrage senden', 'prezium' ); ?></button>
	</section>
</div>
<?php
get_footer();
