<?php
// Demo-Inhalte für die Playground-Vorschau. Nutzt die Import-Funktion der Theme.
require_once '/wordpress/wp-load.php';

prezium_run_demo_import();

// Demo-Nutzer nur für die Playground-Vorschau (wertlos, nicht öffentlich).
if ( ! username_exists( 'architekt' ) ) {
	wp_insert_user( array(
		'user_login'   => 'architekt',
		'user_pass'    => 'Test-Architekt-1',
		'user_email'   => 'architekt@example.com',
		'display_name' => 'Test Architekt',
		'role'         => 'subscriber',
	) );
}
