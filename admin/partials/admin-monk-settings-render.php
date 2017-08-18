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
		<form action="options.php" method="POST" class="monk-form-settings" id="<?php echo esc_attr( $form_id ); ?>">
			<?php $btn_args = array(); ?>
			<?php
			switch ( $the_tab ) :
				case 'tools':
			?>
				<input type="hidden" name="action" value="monk_set_language_to_elements" />
				<?php wp_nonce_field( '_monk_tools' ); ?>
				<?php break; ?>
			<?php case 'options': ?>
				<input type="hidden" name="action" value="monk_save_options" />
				<?php wp_nonce_field( '_monk_site_options' ); ?>
				<?php break; ?>
			<?php case 'general': ?>
				<?php default: ?>
					<?php
						$btn_args = array(
							'id' => 'monk-submit-settings',
						);
						wp_nonce_field( '_monk_save_general_settings', '_monk_save_general_settings' );
						settings_fields( 'monk_settings' );
					?>
				<?php break; ?>
			<?php endswitch; ?>
			<?php
				do_settings_sections( 'monk_settings' );
				submit_button( '', 'primary', 'submit', 'true', $btn_args );
			?>
			<span class="spinner monk-spinner" id="monk-spinner"></span>
			<?php
			switch ( $the_tab ) :
				case 'tools':
			?>
					<p class="monk-message monk-hide" id="monk-checkbox-not-selected-message"><?php esc_html_e( 'Select the checkbox.', 'monk' ); ?></p>
					<p class="monk-message monk-hide" id="monk-bulk-action"><?php esc_html_e( 'Defining language for posts and terms...', 'monk' ); ?></p>
				<?php break; ?>
			<?php case 'options': ?>
				<p class="monk-message monk-hide" id="monk-save-options">
				<?php esc_html_e( 'Saving Options...', 'monk' ); ?>
				</p>
				<?php break; ?>
			<?php case 'general': ?>
				<?php default: ?>
					<p class="monk-message monk-hide" id="monk-downloading">
					<?php esc_html_e( 'Downloading packages...', 'monk' ); ?>
					</p>
				<?php break; ?>
			<?php endswitch; ?>
			<p class="monk-message monk-hide" id="monk-done"><?php esc_html_e( 'Done!', 'monk' ); ?></p>
			<p class="monk-message monk-hide" id="monk-error"><?php esc_html_e( 'Error. Try again.', 'monk' ); ?></p>
		</form>
	</div>
<?php
