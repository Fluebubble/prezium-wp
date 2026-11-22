<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
	<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">PREZIUM</a>
	<nav class="site-nav" aria-label="<?php esc_attr_e( 'Hauptmenü', 'prezium' ); ?>">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'fallback_cb'    => false,
		) );
		?>
	</nav>
</header>
<main>
