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

	$options = get_option( 'monk_default_language' );
?>
	<select name="monk_default_language">
		<option value="pt_BR"<?php selected( $options, 'pt_BR' ); ?>>
			<?php esc_html_e( 'Portuguese', 'monk' ); ?>
		</option>
		<option value="en_US"<?php selected( $options, 'en_US' ); ?>>
			<?php esc_html_e( 'English', 'monk' ); ?>
		</option>
		<option value="es_ES"<?php selected( $options, 'es_ES' ); ?>>
			<?php esc_html_e( 'Spanish', 'monk' ); ?>
		</option>
		<option value="fr_FR"<?php selected( $options, 'fr_FR' ); ?>>
			<?php esc_html_e( 'French', 'monk' ); ?>
		</option>
	</select>
