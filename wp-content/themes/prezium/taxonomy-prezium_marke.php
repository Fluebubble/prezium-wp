<?php
get_header();
?>
<?php if ( term_description() ) : ?>
	<section class="section brand-intro"><?php echo wp_kses_post( term_description() ); ?></section>
<?php endif; ?>
<?php
get_template_part( 'template-parts/catalog-grid' );
get_footer();
