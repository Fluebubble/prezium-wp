<?php
get_header();
while ( have_posts() ) :
	the_post();
	$pid    = get_the_ID();
	$facts  = get_post_meta( $pid, '_prez_facts', true );
	$prods  = prezium_project_products( $pid );
	$mins   = prezium_reading_time( $pid );
	$others = get_posts( array( 'post_type' => 'prezium_project', 'posts_per_page' => 1, 'post__not_in' => array( $pid ), 'orderby' => 'rand' ) );
	$ld     = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'Article',
		'headline'      => get_the_title(),
		'description'   => wp_strip_all_tags( get_the_excerpt() ),
		'datePublished' => get_the_date( 'c' ),
		'dateModified'  => get_the_modified_date( 'c' ),
		'image'         => get_the_post_thumbnail_url( $pid, 'full' ),
		'publisher'     => array( '@type' => 'Organization', 'name' => 'Prezium GmbH' ),
	);
	?>
<script type="application/ld+json"><?php echo wp_json_encode( $ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>
<article class="section proj">
	<p class="note"><a href="<?php echo esc_url( home_url( '/#projekte' ) ); ?>">&larr; <?php esc_html_e( 'Alle Projekte', 'prezium' ); ?></a></p>
	<header class="proj-head">
		<p class="eyebrow"><?php echo esc_html( get_the_excerpt() ); ?> · <?php echo esc_html( sprintf( /* translators: %d: minutes */ __( '%d Min. Lesezeit', 'prezium' ), $mins ) ); ?></p>
		<h1 class="page-title"><?php the_title(); ?> <span class="badge-test badge-demoproject badge-inline">DEMO</span></h1>
	</header>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="proj-hero"><?php the_post_thumbnail( 'full', array( 'class' => 'ph' ) ); ?></div>
	<?php endif; ?>

	<div class="proj-body">
		<div class="proj-text prod-text"><?php the_content(); ?></div>
		<aside class="proj-side">
			<?php if ( is_array( $facts ) && $facts ) : ?>
				<dl class="specs">
					<?php foreach ( $facts as $k => $v ) : ?>
						<dt><?php echo esc_html( $k ); ?></dt><dd><?php echo esc_html( $v ); ?></dd>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>
			<button class="btn" type="button" data-inquiry="<?php echo esc_attr( __( 'Projektanfrage', 'prezium' ) . ': ' . get_the_title() ); ?>"><?php esc_html_e( 'Ähnliches Projekt anfragen', 'prezium' ); ?></button>
		</aside>
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

	<?php if ( $others ) : ?>
		<p class="proj-next"><?php esc_html_e( 'Nächstes Projekt', 'prezium' ); ?>: <a href="<?php echo esc_url( get_permalink( $others[0] ) ); ?>"><?php echo esc_html( get_the_title( $others[0] ) ); ?> &rarr;</a></p>
	<?php endif; ?>
</article>
	<?php
endwhile;
get_footer();
