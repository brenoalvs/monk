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

global $monk_languages;
?>	
<select name="monk_default_language">
	<?php
	foreach ( $monk_languages as $lang_code => $lang_names ) : ?>
		<option value="<?php echo esc_attr( $lang_code ); ?>"<?php selected( $default_language, $lang_code ); ?>>
			<?php echo esc_html( $lang_names['name'] ); ?>
		</option>
	<?php endforeach; ?>
</select>
