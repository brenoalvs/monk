<?php
/**
 * Field to change Term Language.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<table class="form-table">
	<tbody>
		<tr class="form-field term-language">
			<th scope="row">
				<label for="monk-language"><?php esc_html_e( 'Language', 'monk' ); ?></label>
			</th>
			<td>
				<select class="postform" id="monk-language" name="monk-language">
					<?php foreach ( $languages as $language ) : ?>
						<option value="<?php echo esc_attr( $language ); ?>" <?php selected( $monk_language, $language ); ?>><?php echo esc_html( $monk_languages[ $language ]['name'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>
