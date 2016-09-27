<?php

/**
 * Provide the view for the monk_translation_list_render function
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

	$options = get_option( 'monk_active_languages' );
?>
	<fieldset>
		<label for="monk-ptbr">
			<?php esc_html_e( 'Portuguese( Brazil )', 'monk' ); ?>
		</label>
		<input type="checkbox" name="monk_active_languages[]" id="monk-ptbr" value="pt_BR" <?php checked( in_array( 'pt_BR', $options ) ); ?>/>

		<label for="monk-enus">
			<?php esc_html_e( 'English', 'monk' ); ?>
		</label>
		<input type="checkbox" name="monk_active_languages[]" id="monk-enus" value="en_US" <?php checked( in_array( 'en_US', $options ) ); ?>/>

		<label for="monk-eses">
			<?php esc_html_e( 'Spanish', 'monk' ); ?>
		</label>
		<input type="checkbox" name="monk_active_languages[]" id="monk-eses" value="es_ES" <?php checked( in_array( 'es_ES', $options ) ); ?>/>

		<label for="monk-frfr">
			<?php esc_html_e( 'French', 'monk' ); ?>
		</label>
		<input type="checkbox" name="monk_active_languages[]" id="monk-frfr" value="fr_FR" <?php checked( in_array( 'fr_FR', $options ) ); ?>/>

		<label for="monk-itit">
			<?php esc_html_e( 'Italian', 'monk' ); ?>
		</label>
		<input type="checkbox" name="monk_active_languages[]" id="monk-itit" value="it_IT" <?php checked( in_array( 'it_IT', $options ) ); ?>/>
	</fieldset>
