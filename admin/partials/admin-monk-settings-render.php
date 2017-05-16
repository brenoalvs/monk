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
		<input type="hidden" id="monk-error-message" value="<?php esc_attr_e( 'Error sending form', 'monk' ); ?>">
		<h2>Monk</h2>
		<div class="notice notice-success monk-hide" id="monk-settings-notice-success">
			<p>
				<strong><?php esc_html_e( 'This packages have been downloaded:', 'monk' ); ?></strong>
			</p>
			<div></div>
		</div>
		<div class="notice notice-error monk-hide" id="monk-settings-notice-error">
			<p class="monk-hide">
				<strong><?php esc_html_e( 'This packages have not been downloaded:', 'monk' ); ?></strong><br />
			</p>
			<div></div>
		</div>
		<?php
			update_option( 'monk_settings_notice', false );
			settings_errors();
		?>
		<form action="options.php" method="POST" id="monk-form-settings">
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
		</form>
	</div>
<?php
