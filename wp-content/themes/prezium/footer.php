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
<?php wp_footer(); ?>
</body>
</html>
