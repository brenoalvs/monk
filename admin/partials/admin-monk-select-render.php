<?php

/**
 * Provide the view for the monk_select_render function
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

	$options = get_option( 'monk_language' );
?>
	<select name="monk_language[language_select]">
		<option value="pt_BR"<?php selected( $options['language_select'], 'pt_BR' ); ?>>
			<?php esc_html_e( 'Portuguese', 'monk' ); ?>
		</option>
		<option value="en_US"<?php selected( $options['language_select'], 'en_US' ); ?>>
			<?php esc_html_e( 'English', 'monk' ); ?>
		</option>
		<option value="es_ES"<?php selected( $options['language_select'], 'es_ES' ); ?>>
			<?php esc_html_e( 'Spanish', 'monk' ); ?>
		</option>
		<option value="fr_FR"<?php selected( $options['language_select'], 'fr_FR' ); ?>>
			<?php esc_html_e( 'French', 'monk' ); ?>
		</option>
	</select>
