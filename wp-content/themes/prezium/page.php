<?php
get_header();
?>
<section class="section">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<h1 class="page-title"><?php the_title(); ?></h1>
		<div class="prod-text"><?php the_content(); ?></div>
	<?php endwhile; ?>
</section>
<?php
get_footer();
