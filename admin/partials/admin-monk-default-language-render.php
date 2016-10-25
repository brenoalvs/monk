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

?>	
<select name="monk_default_language">
	<?php foreach ( $available_languages as $lang_code => $lang_name ) : ?>
		<option value="<?php echo esc_attr( $lang_code ); ?>"<?php selected( $default_language, $lang_code ); ?>>
			<?php echo esc_html( $lang_name ); ?>
		</option>
	<?php endforeach; ?>
</select>
<p>
	<?php echo $default_language; ?>
</p>
