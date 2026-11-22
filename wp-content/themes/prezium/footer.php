</main>
<footer class="site-footer">
	<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Prezium GmbH</span>
	<?php
	wp_nav_menu( array(
		'theme_location' => 'footer',
		'container'      => false,
		'fallback_cb'    => false,
		'depth'          => 1,
	) );
	?>
</footer>
<dialog id="inquiry-dialog" aria-labelledby="inquiry-title">
	<h2 id="inquiry-title"><?php esc_html_e( 'Preis anfragen', 'prezium' ); ?></h2>
	<p class="note" data-inquiry-ref></p>
	<?php get_template_part( 'template-parts/inquiry-form', null, array( 'dialog' => true ) ); ?>
</dialog>
<?php wp_footer(); ?>
</body>
</html>
