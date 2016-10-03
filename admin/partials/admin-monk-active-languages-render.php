<?php

/**
 * Provide the view for the monk_active_languages_render function
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

	$active_languages    = get_option( 'monk_active_languages' );
	$default_language    = get_option( 'monk_default_language' );
	$available_languages = array(
		'pt_BR' => __( 'Portuguese (Brazil)', 'monk' ),
		'en_US' => __( 'English', 'monk' ),
		'es_ES' => __( 'Spanish', 'monk' ),
		'fr_FR' => __( 'French', 'monk' ),
		'it_IT' => __( 'Italian', 'monk' ),
	);
?>
<fieldset>
<?php

	/**
	 * Walks through each value in the active languages array creating the checkboxes
	 * based on the values, making possible and more reliable updating that list 
	 */
	foreach ( $available_languages as $lang_code => $lang_name ) :
		$id          = sanitize_title( $lang_code );
		$is_checked  = $default_language === $lang_code || in_array( $lang_code, $active_languages ) ? true : false;
		$is_disabled = $default_language === $lang_code ? true : false;
?>
	<label for="<?php echo esc_attr( 'monk-' . $id ); ?>">
		<input type="checkbox" <?php if ( $is_checked ) : ?>class="monk-saved-language"<?php endif; ?> name="monk_active_languages[]" id="<?php echo esc_attr( 'monk-' . $id ); ?>" value="<?php echo esc_attr( $lang_code ); ?>" <?php checked( $is_checked ); disabled( $is_disabled ); ?> />
		<?php echo esc_html( $lang_name ); ?>
	</label>
	<br />
	<?php endforeach; ?>
	<input type="hidden" name="monk_active_languages[]" value="<?php echo esc_attr( $default_language ); ?>">
</fieldset>
