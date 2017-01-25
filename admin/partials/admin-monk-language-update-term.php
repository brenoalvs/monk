<?php
/**
 * Field to change Term Language.
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
var_dump( $monk_term_translations ); 
?>
<table class="form-table">
	<tbody>
		<tr class="form-field term-language">
			<th scope="row">
				<label for="monk-language"><?php esc_html_e( 'Language', 'monk' ); ?></label>
			</th>
			<td>
				<select class="postform" id="monk-language" name="monk_language">
					<?php foreach ( $languages as $language ) : ?>
						<?php if ( $language === $monk_language || ! array_key_exists( $language, $monk_term_translations ) ) : ?>
							<option value="<?php echo esc_attr( $language ); ?>" <?php selected( $monk_language, $language ); ?>><?php echo esc_html( $monk_languages[ $language ]['name'] ); ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>
