<?php
/**
 * Provide the view for the monk_options function
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
	<div class="wrap">
		<h2>Monk</h2>
		<?php
			update_option( 'monk_settings_notice', false );
			settings_errors();
		?>
		<form action="options.php" method="POST" id="monk-form-settings">
			<input type="hidden" name="action" value="monk_save_language_packages">
			<?php
				$btn_args = array(
					'id' => 'monk-submit-settings',
				);
				wp_nonce_field( '_monk_nonce', '_monk_nonce' );
				settings_fields( 'monk_settings' );
				do_settings_sections( 'monk_settings' );
				submit_button( '', 'primary', 'submit', 'true', $btn_args );
			?>
			<span class="spinner monk-spinner" id="monk-spinner"></span>
			<p class="monk-message monk-hide" id="monk-downloading"><?php esc_html_e( 'Downloading packages...', 'monk' ); ?></p>
			<p class="monk-message monk-hide" id="monk-download-done"><?php esc_html_e( 'Done!', 'monk' ); ?></p>
			<p class="monk-message monk-hide" id="monk-download-error"><?php esc_html_e( 'Error. Try again.', 'monk' ); ?></p>
		</form>
	</div>
<?php
