<?php
/**
 * Kontaktseite (Seite mit dem Titelform "kontakt").
 */
get_header();
?>
<section class="section">
	<h1 class="page-title"><?php the_title(); ?></h1>
	<div class="cols">
		<div class="prod-text"><?php while ( have_posts() ) { the_post(); the_content(); } ?></div>
		<?php get_template_part( 'template-parts/inquiry-form' ); ?>
	</div>
</section>
<?php
get_footer();
