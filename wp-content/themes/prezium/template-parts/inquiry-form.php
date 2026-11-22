<?php
/**
 * Anfrageformular (Pop-up und Kontaktseite). Wird per JS (site.js) per AJAX gesendet.
 */
$is_dialog = ! empty( $args['dialog'] );
?>
<form class="f inquiry-form" method="post" novalidate>
	<input type="hidden" name="action" value="prezium_inquiry">
	<input type="hidden" name="product" value="">
	<div class="hp" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

	<input name="name" placeholder="<?php esc_attr_e( 'Name', 'prezium' ); ?>" aria-label="<?php esc_attr_e( 'Name', 'prezium' ); ?>" required autocomplete="name">
	<input name="email" type="email" placeholder="<?php esc_attr_e( 'E-Mail', 'prezium' ); ?>" aria-label="<?php esc_attr_e( 'E-Mail', 'prezium' ); ?>" required autocomplete="email">
	<input name="phone" type="tel" placeholder="<?php esc_attr_e( 'Telefon', 'prezium' ); ?>" aria-label="<?php esc_attr_e( 'Telefon', 'prezium' ); ?>" autocomplete="tel">
	<textarea name="message" rows="4" placeholder="<?php esc_attr_e( 'Nachricht', 'prezium' ); ?>" aria-label="<?php esc_attr_e( 'Nachricht', 'prezium' ); ?>"></textarea>

	<label class="consent">
		<input type="checkbox" name="consent" value="1" required>
		<span>
			<?php
			printf(
				/* translators: %s: link to privacy policy */
				esc_html__( 'Ich stimme der Verarbeitung meiner Daten zur Bearbeitung der Anfrage zu (%s).', 'prezium' ),
				'<a href="' . esc_url( get_privacy_policy_url() ?: home_url( '/datenschutz/' ) ) . '">' . esc_html__( 'Datenschutzerklärung', 'prezium' ) . '</a>'
			);
			?>
		</span>
	</label>

	<p class="form-status" role="status" aria-live="polite"></p>
	<div class="form-actions">
		<button class="btn" type="submit"><?php echo $is_dialog ? esc_html__( 'Anfrage senden', 'prezium' ) : esc_html__( 'Nachricht senden', 'prezium' ); ?></button>
		<?php if ( $is_dialog ) : ?>
			<button class="btn ghost" type="button" data-inquiry-close><?php esc_html_e( 'Schließen', 'prezium' ); ?></button>
		<?php endif; ?>
	</div>
</form>
