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
		<div id="monk-notice"></div>
		<?php
			update_option( 'monk_settings_notice', false );
			settings_errors();
		?>
		<form action="options.php" method="POST" id="monk-form-settings">
			<?php
				wp_nonce_field( '_monk_nonce' );
				do_settings_sections( 'monk_settings' );
				submit_button( '', 'primary', 'submit', 'true', array( 'id' => 'monk-submit-settings' ) );
			?>
			<span class="spinner monk-spinner" id="monk-spinner"></span>
		</form>
	</div>
<?php
