<?php
/**
 * Startseite: filmische Inszenierung (Hero, Bereiche, Projekte, Präzision).
 *
 * @package prezium
 */

if ( ! function_exists( 'prezium_home_img' ) ) {
	/**
	 * Gibt ein responsives WebP-Bild aus assets/home/ aus.
	 *
	 * @param string $slug  Dateiname ohne Endung, z. B. 01-hero.
	 * @param string $alt   Alternativtext.
	 * @param string $class CSS-Klasse.
	 * @param bool   $eager Sofort laden (Hero) statt lazy.
	 */
	function prezium_home_img( $slug, $alt, $class = '', $eager = false ) {
		$base = get_template_directory_uri() . '/assets/home/' . $slug;
		printf(
			'<img class="%1$s" src="%2$s.webp" srcset="%2$s-sm.webp 960w, %2$s.webp 1920w" sizes="(max-width: 800px) 100vw, 60vw" width="1920" height="1072" alt="%3$s" %4$s>',
			esc_attr( $class ),
			esc_url( $base ),
			esc_attr( $alt ),
			$eager ? 'fetchpriority="high"' : 'loading="lazy" decoding="async"'
		);
	}
}

get_header();

// Bereiche: Reihenfolge, Bild, Kurztext. Schlüssel = Name der Kategorie in Kleinbuchstaben.
$panels = array(
	'türen'           => array( '02-tueren', true, __( 'Pivot-, Schiebe- und Zimmertüren: präzise Linien, ruhige Flächen.', 'prezium' ) ),
	'parkett'         => array( '03-parkett', false, __( 'Fischgrät, Landhausdiele und Massivholz in warmen, tiefen Tönen.', 'prezium' ) ),
	'fenster'         => array( '04-fenster', false, __( 'Schlanke Rahmen, große Glasflächen, Licht ohne Grenzen.', 'prezium' ) ),
	'möbel'           => array( '05-moebel', true, __( 'Skulpturale Einzelstücke aus Nussbaum, Eiche und Stein.', 'prezium' ) ),
	'terrassendielen' => array( '06-terrassendielen', false, __( 'Thermoholz und Hartholz für Terrassen, die Jahre überdauern.', 'prezium' ) ),
	'licht'           => array( '07-licht', false, __( 'Leuchten, die Räume formen, nicht nur ausleuchten.', 'prezium' ) ),
);

$count_products = (int) ( wp_count_posts( 'prezium_product' )->publish ?? 0 );
$count_brands   = (int) wp_count_terms( array( 'taxonomy' => 'prezium_marke', 'hide_empty' => false ) );
$count_areas    = (int) wp_count_terms( array( 'taxonomy' => 'prezium_bereich', 'hide_empty' => false ) );
$catalog_url    = get_post_type_archive_link( 'prezium_product' );
?>

<section class="hx">
	<?php prezium_home_img( '01-hero', __( 'Moderne Villa in der Dämmerung mit warm beleuchteten Glasfronten', 'prezium' ), 'hx-img', true ); ?>
	<div class="hx-shade"></div>
	<div class="hx-body">
		<p class="eyebrow"><?php esc_html_e( 'Prezium GmbH', 'prezium' ); ?></p>
		<h1><?php echo esc_html( get_bloginfo( 'description' ) ?: 'Der Sieg liebt Präzision' ); ?></h1>
		<p class="hx-lead"><?php esc_html_e( 'Hochwertige Türen, Parkett, Fenster und Licht für Räume mit Haltung.', 'prezium' ); ?></p>
		<p class="hx-actions">
			<a class="btn gold" href="<?php echo esc_url( $catalog_url ); ?>"><?php esc_html_e( 'Katalog ansehen', 'prezium' ); ?></a>
			<a class="btn outline" href="#projekte"><?php esc_html_e( 'Projekte ansehen', 'prezium' ); ?></a>
		</p>
	</div>
	<span class="hx-scroll"><?php esc_html_e( 'Scroll', 'prezium' ); ?></span>
</section>

<section class="intro">
	<p class="eyebrow rv"><?php esc_html_e( 'Unser Anspruch', 'prezium' ); ?></p>
	<h2 class="rv"><?php esc_html_e( 'Materialien, die Architektur tragen.', 'prezium' ); ?></h2>
	<p class="intro-text rv"><?php esc_html_e( 'Wir wählen Hersteller und Oberflächen mit Sorgfalt aus. Jedes Stück ist präzise gefertigt, jede Anfrage wird persönlich beraten, ohne Warenkorb und ohne Umwege.', 'prezium' ); ?></p>
	<dl class="stats rv">
		<div><dt><?php echo esc_html( $count_products ); ?></dt><dd><?php esc_html_e( 'Produkte im Katalog', 'prezium' ); ?></dd></div>
		<div><dt><?php echo esc_html( $count_brands ); ?></dt><dd><?php esc_html_e( 'Marken', 'prezium' ); ?></dd></div>
		<div><dt><?php echo esc_html( $count_areas ); ?></dt><dd><?php esc_html_e( 'Bereiche', 'prezium' ); ?></dd></div>
	</dl>
</section>

<section class="panels-wrap" id="bereiche">
	<div class="panels">
		<?php
		$i = 0;
		foreach ( $panels as $key => $panel ) :
			$term = null;
			foreach ( (array) get_terms( array( 'taxonomy' => 'prezium_bereich', 'hide_empty' => false ) ) as $t ) {
				if ( ! is_wp_error( $t ) && mb_strtolower( $t->name ) === $key ) {
					$term = $t;
					break;
				}
			}
			if ( ! $term ) {
				continue;
			}
			++$i;
			?>
			<a class="panel rv<?php echo $panel[1] ? ' wide' : ''; ?>" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
				<?php prezium_home_img( $panel[0], $term->name, 'panel-img' ); ?>
				<span class="panel-shade"></span>
				<span class="panel-body">
					<small><?php echo esc_html( sprintf( '%02d', $i ) ); ?></small>
					<h3><?php echo esc_html( $term->name ); ?></h3>
					<span class="panel-text"><?php echo esc_html( $panel[2] ); ?></span>
					<span class="more"><?php esc_html_e( 'Entdecken', 'prezium' ); ?> &rarr;</span>
				</span>
			</a>
		<?php endforeach; ?>
	</div>
</section>

<section class="projects" id="projekte">
	<div class="head rv">
		<p class="eyebrow"><?php esc_html_e( 'Ausgewählte Referenzen', 'prezium' ); ?></p>
		<h2><?php esc_html_e( 'Projekte', 'prezium' ); ?></h2>
		<p><?php esc_html_e( 'Räume, in denen Material, Licht und Proportion zusammenfinden.', 'prezium' ); ?></p>
	</div>
	<div class="project-grid">
		<?php
		$projects = array(
			array( '08-projekt1', __( 'Villa mit Doppelraum', 'prezium' ), __( 'Parkett · Fenster · Licht', 'prezium' ), 'villa-mit-doppelraum' ),
			array( '09-projekt2', __( 'Penthouse mit Stadtblick', 'prezium' ), __( 'Parkett · Möbel', 'prezium' ), 'penthouse-mit-stadtblick' ),
			array( '10-projekt3', __( 'Haus im Morgennebel', 'prezium' ), __( 'Terrassendielen · Fenster', 'prezium' ), 'haus-im-morgennebel' ),
			array( '11-projekt4', __( 'Boutique-Hotel Lobby', 'prezium' ), __( 'Türen · Licht · Möbel', 'prezium' ), 'boutique-hotel-lobby' ),
		);
		foreach ( $projects as $p ) :
			$pp   = get_posts( array( 'post_type' => 'prezium_project', 'name' => $p[3], 'posts_per_page' => 1, 'fields' => 'ids' ) );
			$link = $pp ? get_permalink( $pp[0] ) : '';
			?>
			<figure class="project rv">
				<?php if ( $link ) : ?><a href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( $p[1] ); ?>"><?php endif; ?>
				<?php prezium_home_img( $p[0], $p[1], 'project-img' ); ?>
				<?php if ( $link ) : ?></a><?php endif; ?>
				<span class="badge-demo"><?php esc_html_e( 'Demo', 'prezium' ); ?></span>
				<figcaption>
					<strong><?php echo esc_html( $p[1] ); ?></strong>
					<span><?php echo esc_html( $p[2] ); ?></span>
				</figcaption>
			</figure>
		<?php endforeach; ?>
	</div>
</section>

<section class="section featured">
	<div class="head rv">
		<p class="eyebrow"><?php esc_html_e( 'Aus dem Katalog', 'prezium' ); ?></p>
		<h2><?php esc_html_e( 'Ausgewählte Produkte', 'prezium' ); ?></h2>
	</div>
	<div class="grid">
		<?php
		$featured = new WP_Query( array( 'post_type' => 'prezium_product', 'posts_per_page' => 4, 'orderby' => 'rand' ) );
		while ( $featured->have_posts() ) :
			$featured->the_post();
			$sku    = get_post_meta( get_the_ID(), '_prez_sku', true );
			$marken = get_the_terms( get_the_ID(), 'prezium_marke' );
			$marke  = ( $marken && ! is_wp_error( $marken ) ) ? $marken[0]->name : '';
			?>
			<article class="card rv">
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
</section>

<section class="precision">
	<?php prezium_home_img( '12-praezision', __( 'Werkbank mit präziser Holzverbindung und Stechbeitel', 'prezium' ), 'precision-img' ); ?>
	<div class="precision-shade"></div>
	<div class="precision-body">
		<p class="eyebrow rv"><?php esc_html_e( 'So arbeiten wir', 'prezium' ); ?></p>
		<h2 class="rv"><?php esc_html_e( 'Präzision beginnt im Detail.', 'prezium' ); ?></h2>
		<ol class="steps">
			<li class="rv"><span>01</span><h3><?php esc_html_e( 'Entdecken', 'prezium' ); ?></h3><p><?php esc_html_e( 'Im Katalog Produkte, Materialien und Oberflächen vergleichen.', 'prezium' ); ?></p></li>
			<li class="rv"><span>02</span><h3><?php esc_html_e( 'Preis anfragen', 'prezium' ); ?></h3><p><?php esc_html_e( 'Mit einem Klick anfragen, das Produkt wird automatisch übernommen.', 'prezium' ); ?></p></li>
			<li class="rv"><span>03</span><h3><?php esc_html_e( 'Persönlich beraten', 'prezium' ); ?></h3><p><?php esc_html_e( 'Wir melden uns mit Angebot, Mustern und technischen Details.', 'prezium' ); ?></p></li>
		</ol>
	</div>
</section>

<section class="section brands">
	<p class="eyebrow rv"><?php esc_html_e( 'Unsere Marken', 'prezium' ); ?></p>
	<p class="brand-strip rv">
		<?php
		$marken = get_terms( array( 'taxonomy' => 'prezium_marke', 'hide_empty' => false ) );
		if ( ! is_wp_error( $marken ) ) :
			foreach ( $marken as $marke ) :
				?>
				<a href="<?php echo esc_url( get_term_link( $marke ) ); ?>"><?php echo esc_html( $marke->name ); ?></a>
			<?php endforeach; endif; ?>
	</p>
</section>

<section class="cta-arch">
	<?php prezium_home_img( '13-architekten', __( 'Architektenschreibtisch mit Plänen und Modell einer Villa', 'prezium' ), 'cta-img' ); ?>
	<div class="cta-shade"></div>
	<div class="cta-body rv">
		<p class="eyebrow"><?php esc_html_e( 'Für Fachleute', 'prezium' ); ?></p>
		<h2><?php esc_html_e( 'Architekt oder Designer?', 'prezium' ); ?></h2>
		<p><?php esc_html_e( 'Registrieren Sie sich für Preislisten, technische Dokumentation und CAD/BIM-Daten.', 'prezium' ); ?></p>
		<a class="btn gold" href="<?php echo esc_url( home_url( '/architekten-designer/' ) ); ?>"><?php esc_html_e( 'Mehr erfahren', 'prezium' ); ?></a>
	</div>
</section>

<?php get_footer(); ?>
