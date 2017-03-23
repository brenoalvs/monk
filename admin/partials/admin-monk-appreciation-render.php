<?php
/**
 * Provide the view for the monk_appreciation_render function
 *
 * @since      0.3.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<fieldset>
	<label for="appreciate-monk">
		<input type="checkbox" name="appreciate_monk" id="appreciate-monk" value="true" <?php checked( $appreciation ); ?> >
		<?php esc_html_e( 'Show the world how much you like us by displaying a beautiful message under your language switcher', 'monk' ); ?>
	</label>
</fieldset>
