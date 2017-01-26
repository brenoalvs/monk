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
		<form action="options.php" method="POST">
			<?php
				settings_fields( 'monk_settings' );
				do_settings_sections( 'monk_settings' );
				submit_button();
			?>
		</form>
	</div>
<?php
