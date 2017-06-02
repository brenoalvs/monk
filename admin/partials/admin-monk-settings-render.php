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
		if ( 'monk_general' === $action || '' === $action ) {
			$form_id = 'monk-general-form';
			$is_tools = false;
		} elseif ( 'monk_tools' === $action ) {
			$form_id  = 'monk-tools-form';
			$is_tools = true;
		}
		?>
		<form action="options.php" method="POST" class="monk-form-settings" id="<?php echo esc_attr( $form_id ); ?>">
			<?php
			if ( ! $is_tools ) {
				settings_fields( 'monk_settings' );
			} else {
				?><input type="hidden" name="action" value="monk_set_language_to_elements" /><?php
				wp_nonce_field( '_monk_nonce' );
			}
			do_settings_sections( 'monk_settings' );
			submit_button();
			?>
			<span class="spinner monk-spinner" id="monk-spinner"></span>
			<?php if ( $is_tools ) : ?>
				<p class="monk-message monk-hide" id="monk-checkbox-not-selected-message"><?php esc_html_e( 'Select the checkbox.', 'monk' ); ?></p>
				<p class="monk-message monk-hide" id="monk-bulk-action"><?php esc_html_e( 'Defining language for posts and terms...', 'monk' ); ?></p>
				<p class="monk-message monk-hide" id="monk-bulk-action-done"><?php esc_html_e( 'Done!', 'monk' ); ?></p>
				<p class="monk-message monk-hide" id="monk-bulk-action-error"><?php esc_html_e( 'Error. Try again.', 'monk' ); ?></p>
			<?php endif; ?>
		</form>
	</div>
<?php
