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
		<option value="pt-br"<?php selected( $options['language_select'], 'pt-br' ); ?>>
			<?php esc_html_e( 'Portuguese', 'monk' ); ?>
		</option>
		<option value="en"<?php selected( $options['language_select'], 'en' ); ?>>
			<?php esc_html_e( 'English', 'monk' ); ?>
		</option>
		<option value="es"<?php selected( $options['language_select'], 'es' ); ?>>
			<?php esc_html_e( 'Spanish', 'monk' ); ?>
		</option>
		<option value="fr"<?php selected( $options['language_select'], 'fr' ); ?>>
			<?php esc_html_e( 'French', 'monk' ); ?>
		</option>
	</select>
