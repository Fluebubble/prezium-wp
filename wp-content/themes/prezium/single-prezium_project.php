<?php
/**
 * Projektseite: Artikel-Layout (Hero, Daten, Text, Materialpalette, Anfrage, nächstes Projekt).
 */
get_header();
while ( have_posts() ) :
	the_post();
	$pid    = get_the_ID();
	$title  = get_the_title();
	$facts  = get_post_meta( $pid, '_prez_facts', true );
	$mins   = prezium_reading_time( $pid );
	$prods  = prezium_project_products( $pid );
	$cats   = array();
	$all    = get_posts( array( 'post_type' => 'prezium_project', 'posts_per_page' => -1, 'orderby' => 'ID', 'order' => 'ASC', 'fields' => 'ids' ) );
	$pos    = array_search( $pid, $all, true );
	$next   = $all ? $all[ ( (int) $pos + 1 ) % count( $all ) ] : 0;
	$ld     = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'Article',
		'headline'      => $title,
		'description'   => wp_strip_all_tags( get_the_excerpt() ),
		'datePublished' => get_the_date( 'c' ),
		'dateModified'  => get_the_modified_date( 'c' ),
		'image'         => get_the_post_thumbnail_url( $pid, 'full' ),
		'publisher'     => array( '@type' => 'Organization', 'name' => 'Prezium GmbH' ),
	);
	$inq = __( 'Projektanfrage', 'prezium' ) . ': ' . $title;
	?>
<script type="application/ld+json"><?php echo wp_json_encode( $ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>
<article class="pj">
	<header class="pj-hero">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'full', array( 'sizes' => '100vw', 'fetchpriority' => 'high' ) ); ?>
		<?php endif; ?>
		<div class="pj-hero-in">
			<a class="pj-back" href="<?php echo esc_url( get_post_type_archive_link( 'prezium_project' ) ); ?>">&larr; <?php esc_html_e( 'Alle Projekte', 'prezium' ); ?></a>
			<p class="pj-eyebrow"><?php echo esc_html( get_the_excerpt() . ' · ' . sprintf( /* translators: %d: minutes */ __( '%d Min. Lesezeit', 'prezium' ), $mins ) ); ?></p>
			<h1><?php echo esc_html( $title ); ?></h1>
			<span class="pj-demo">DEMO</span>
		</div>
	</header>

	<dl class="pj-facts">
		<?php if ( is_array( $facts ) ) : foreach ( $facts as $k => $v ) : ?>
			<div><dt><?php echo esc_html( $k ); ?></dt><dd><?php echo esc_html( $v ); ?></dd></div>
		<?php endforeach; endif; ?>
		<div class="pj-cta-cell"><button class="pj-btn" type="button" data-inquiry="<?php echo esc_attr( $inq ); ?>"><?php esc_html_e( 'Ähnliches Projekt anfragen', 'prezium' ); ?></button></div>
	</dl>

	<div class="pj-article"><div class="pj-text"><?php the_content(); ?></div></div>

	<?php if ( $prods->have_posts() ) : ?>
	<section class="pj-palette">
		<div class="pj-palette-in">
			<div class="pj-head">
				<div>
					<p class="pj-eyebrow"><?php esc_html_e( 'Materialpalette', 'prezium' ); ?></p>
					<h2><?php esc_html_e( 'Materialien & Produkte dieses Projekts', 'prezium' ); ?></h2>
					<p><?php esc_html_e( 'Alles, was in diesem Projekt verbaut wurde, mit Material, Maßen und Oberfläche. Jedes Stück finden Sie im Katalog.', 'prezium' ); ?></p>
				</div>
				<a class="pj-link" href="<?php echo esc_url( get_post_type_archive_link( 'prezium_product' ) ); ?>"><?php esc_html_e( 'Gesamten Katalog öffnen', 'prezium' ); ?> &rarr;</a>
			</div>
			<div class="pj-rows">
				<?php
				$n = 0;
				while ( $prods->have_posts() ) :
					$prods->the_post();
					++$n;
					$ppid   = get_the_ID();
					$marken = get_the_terms( $ppid, 'prezium_marke' );
					$ber    = get_the_terms( $ppid, 'prezium_bereich' );
					$marke  = ( $marken && ! is_wp_error( $marken ) ) ? $marken[0]->name : '';
					$bt     = ( $ber && ! is_wp_error( $ber ) ) ? $ber[0] : null;
					if ( $bt ) {
						$cats[ $bt->term_id ] = $bt;
					}
					$lead  = trim( strtok( wp_strip_all_tags( get_the_content() ), "\n" ) );
					$chips = array_filter( array( get_post_meta( $ppid, '_prez_material', true ), get_post_meta( $ppid, '_prez_dimensions', true ), get_post_meta( $ppid, '_prez_finish', true ) ) );
					?>
					<?php $plink = add_query_arg( 'projekt', $pid, get_permalink() ); ?>
					<div class="pj-row" id="produkt-<?php echo (int) $ppid; ?>">
						<a class="pj-row-img" href="<?php echo esc_url( $plink ); ?>">
							<?php prezium_badge(); ?>
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
							<?php else : ?>
								<img alt="" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
							<?php endif; ?>
						</a>
						<div class="pj-row-body">
							<div class="pj-row-meta"><span><?php echo esc_html( sprintf( '%02d', $n ) ); ?></span><?php if ( $bt ) : ?><a href="<?php echo esc_url( get_term_link( $bt ) ); ?>"><?php echo esc_html( sprintf( /* translators: %s: Bereich */ __( '%s im Katalog', 'prezium' ), $bt->name ) ); ?></a><?php endif; ?></div>
							<h3><a href="<?php echo esc_url( $plink ); ?>"><?php the_title(); ?></a></h3>
							<?php if ( $marke ) : ?><div class="pj-row-brand"><?php echo esc_html( $marke ); ?></div><?php endif; ?>
							<?php if ( $lead ) : ?><p><?php echo esc_html( $lead ); ?></p><?php endif; ?>
							<?php if ( $chips ) : ?><div class="pj-chips"><?php foreach ( $chips as $c ) : ?><span><?php echo esc_html( $c ); ?></span><?php endforeach; ?></div><?php endif; ?>
						</div>
						<div class="pj-row-link"><a class="pj-link" href="<?php echo esc_url( $plink ); ?>"><?php esc_html_e( 'Produkt ansehen', 'prezium' ); ?> &rarr;</a></div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<?php if ( $cats ) : ?>
				<div class="pj-cats"><span><?php esc_html_e( 'Mehr aus diesen Bereichen', 'prezium' ); ?></span>
					<?php foreach ( $cats as $c ) : ?><a href="<?php echo esc_url( get_term_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?> &rarr;</a><?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<section class="pj-cta">
		<h2><?php esc_html_e( 'Ein ähnliches Projekt planen?', 'prezium' ); ?></h2>
		<p><?php esc_html_e( 'Wir beraten Architekten, Designer und Bauherren zu Materialien, Mustern und Preisen.', 'prezium' ); ?></p>
		<button class="pj-btn lg" type="button" data-inquiry="<?php echo esc_attr( $inq ); ?>"><?php esc_html_e( 'Preis anfragen', 'prezium' ); ?></button>
	</section>

	<?php if ( $next && $next !== $pid ) : ?>
		<a class="pj-next" href="<?php echo esc_url( get_permalink( $next ) ); ?>">
			<?php echo get_the_post_thumbnail( $next, 'full', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
			<div class="pj-next-in"><small><?php esc_html_e( 'Nächstes Projekt', 'prezium' ); ?></small><strong><?php echo esc_html( get_the_title( $next ) ); ?> &rarr;</strong></div>
		</a>
	<?php endif; ?>
</article>
	<?php
endwhile;
get_footer();
