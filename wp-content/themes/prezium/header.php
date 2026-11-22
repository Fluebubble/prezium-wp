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
	<a class="btn ghost login-link" href="<?php echo esc_url( is_user_logged_in() ? wp_logout_url( home_url( '/' ) ) : wp_login_url( home_url( '/' ) ) ); ?>">
		<?php echo is_user_logged_in() ? esc_html__( 'Abmelden', 'prezium' ) : esc_html__( 'Anmelden', 'prezium' ); ?>
	</a>
</header>
<main>
