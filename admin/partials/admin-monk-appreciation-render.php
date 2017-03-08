<?php
/**
 * Provide the view for the monk_active_languages_render function
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
<fieldset>
<label for="appreciate-monk">
	<input type="checkbox" name="appreciate_monk" id="appreciate-monk" value="true" <?php checked( $appreciation ); ?> >
	<?php
		esc_html_e( 'Show the world how much you like us by displaying a beatifull message under your language switcher', 'monk' );
	?>
</label>
</fieldset>
