<?php

/**
 * Provide the view for the monk_default_language_render function
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

	$default_language = get_option( 'monk_default_language' );
	$available_languages = array(
		'pt_BR' => __( 'Portuguese (Brazil)', 'monk' ),
		'en_US' => __( 'English', 'monk' ),
		'es_ES' => __( 'Spanish', 'monk' ),
		'fr_FR' => __( 'French', 'monk' ),
		'it_IT' => __('Italian', 'monk')
	);
?>	
<select name="monk_default_language">
	<?php	
	foreach ($available_languages as $lang_code => $lang_name) :
	?>
		<option value="<?php echo esc_attr( $lang_code ); ?>"<?php selected( $default_language, $lang_code ); ?>>
			<?php echo esc_html( $lang_name ); ?>
		</option>
	<?php endforeach; ?>
</select>
