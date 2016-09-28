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

	$active_languages = get_option( 'monk_active_languages' );
	$suported_languages = array(
		'pt_BR' => __( 'Portuguese (Brazil)', 'monk' ),
		'en_US' => __( 'English', 'monk' ),
		'es_ES' => __( 'Spanish', 'monk' ),
		'fr_FR' => __( 'French', 'monk' ),
		'it_IT' => __('Italian', 'monk')
	);
	$default_language = get_option( 'monk_default_language' );
?>
	<fieldset>
	<?php
		foreach ($suported_languages as $lang_code => $lang_name) {

			/**
			 * Walks through each value in the active languages array creating the checkboxes
			 * based on the values, making possible and more reliable updating that list 
			*/

			$id = strtolower( str_replace( '_', '', $lang_code) );
	?>
			<label for="monk-<?php echo $id; ?>">
				<?php echo $lang_name; ?>
			</label>
			<input type="checkbox" name="monk_active_languages[]" id="monk-<?php echo $id; ?>" value="<?php echo $lang_code; ?>" <?php checked( in_array( $lang_code, $active_languages ) ); disabled( $default_language, $lang_code); ?> />
	<?php
		}
	?>
	</fieldset>
	<?php	
