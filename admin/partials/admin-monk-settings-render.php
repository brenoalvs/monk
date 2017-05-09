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
		<div class="notice monk-hide" id="monk-settings-notice">
			<p></p>
		</div>
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
			<input type="hidden" name="action" value="monk_set_language_to_elements" />
			<?php
			if ( ! $is_tools ) {
				settings_fields( 'monk_settings' );
			} else {
				wp_nonce_field( '_monk_nonce' );
			}
			do_settings_sections( 'monk_settings' );
			submit_button();
			?><span class="spinner monk-spinner" id="monk-spinner"></span><?php
			?>
		</form>
	</div>
<?php
