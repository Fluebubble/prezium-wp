<?php
/**
 * Suche und Filter über dem Katalog.
 */
$action = get_post_type_archive_link( 'prezium_product' );
// phpcs:disable WordPress.Security.NonceVerification
$cur_s        = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
$cur_marke    = isset( $_GET['marke'] ) ? sanitize_title( wp_unslash( $_GET['marke'] ) ) : '';
$cur_bereich  = isset( $_GET['bereich'] ) ? sanitize_title( wp_unslash( $_GET['bereich'] ) ) : '';
$cur_material = isset( $_GET['material'] ) ? sanitize_text_field( wp_unslash( $_GET['material'] ) ) : '';
// phpcs:enable

if ( is_tax() ) {
	$action = get_term_link( get_queried_object() );
}
$marken    = get_terms( array( 'taxonomy' => 'prezium_marke', 'hide_empty' => true ) );
$bereiche  = get_terms( array( 'taxonomy' => 'prezium_bereich', 'hide_empty' => true ) );
$materials = prezium_get_materials();
?>
<form class="filters" method="get" action="<?php echo esc_url( $action ); ?>">
	<label><?php esc_html_e( 'Suche', 'prezium' ); ?>
		<input type="search" name="s" value="<?php echo esc_attr( $cur_s ); ?>" placeholder="<?php esc_attr_e( 'Name, Marke oder Artikelnummer', 'prezium' ); ?>">
	</label>

	<?php if ( ! is_tax( 'prezium_bereich' ) && ! is_wp_error( $bereiche ) && $bereiche ) : ?>
	<label><?php esc_html_e( 'Bereich', 'prezium' ); ?>
		<select name="bereich">
			<option value=""><?php esc_html_e( 'Alle Bereiche', 'prezium' ); ?></option>
			<?php foreach ( $bereiche as $t ) : ?>
				<option value="<?php echo esc_attr( $t->slug ); ?>" <?php selected( $cur_bereich, $t->slug ); ?>><?php echo esc_html( $t->name ); ?></option>
			<?php endforeach; ?>
		</select>
	</label>
	<?php endif; ?>

	<?php if ( ! is_tax( 'prezium_marke' ) && ! is_wp_error( $marken ) && $marken ) : ?>
	<label><?php esc_html_e( 'Marke', 'prezium' ); ?>
		<select name="marke">
			<option value=""><?php esc_html_e( 'Alle Marken', 'prezium' ); ?></option>
			<?php foreach ( $marken as $t ) : ?>
				<option value="<?php echo esc_attr( $t->slug ); ?>" <?php selected( $cur_marke, $t->slug ); ?>><?php echo esc_html( $t->name ); ?></option>
			<?php endforeach; ?>
		</select>
	</label>
	<?php endif; ?>

	<?php if ( $materials ) : ?>
	<label><?php esc_html_e( 'Material', 'prezium' ); ?>
		<select name="material">
			<option value=""><?php esc_html_e( 'Alle Materialien', 'prezium' ); ?></option>
			<?php foreach ( $materials as $m ) : ?>
				<option value="<?php echo esc_attr( $m ); ?>" <?php selected( $cur_material, $m ); ?>><?php echo esc_html( $m ); ?></option>
			<?php endforeach; ?>
		</select>
	</label>
	<?php endif; ?>

	<button class="btn" type="submit"><?php esc_html_e( 'Filtern', 'prezium' ); ?></button>
	<a class="btn ghost" href="<?php echo esc_url( $action ); ?>"><?php esc_html_e( 'Zurücksetzen', 'prezium' ); ?></a>
</form>
