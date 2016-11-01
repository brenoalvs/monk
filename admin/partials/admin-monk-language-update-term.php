<?php
	$monk_language = get_term_meta( $term->term_id, 'monk-language', true );
	global $monk_languages;
	$languages = get_option( 'monk_active_languages' );
	?>
	<table class="form-table">
		<tbody>
			<tr class="form-field term-language">
				<th scope="row">
					<label for="monk-language"><?php _e( 'Monk language', 'monk' ); ?></label>
				</th>
				<td>
					<select class="postform" id="monk-language" name="monk-language">
						<option value="-1"><?php _e( 'none', 'monk' ); ?></option>
						<?php foreach ( $languages as $language ) : ?>
							<option value="<?php echo $language; ?>" <?php selected( $monk_language, $language ); ?>><?php echo $monk_languages[$language]['name']; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<?php