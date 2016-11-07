<table class="form-table">
	<tbody>
		<tr class="form-field term-language">
			<th scope="row">
				<label for="monk-language"><?php _e( 'Monk language', 'monk' ); ?></label>
			</th>
			<td>
				<select class="postform" id="monk-language" name="monk-language">
					<?php foreach ( $languages as $language ) : ?>
						<option value="<?php echo esc_attr( $language ); ?>" <?php selected( $monk_language, $language ); ?>><?php echo esc_html( $monk_languages[$language]['name'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>
