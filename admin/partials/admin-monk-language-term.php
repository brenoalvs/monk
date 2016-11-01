<?php		
	global $monk_languages;
	$languages = get_option( 'monk_active_languages' );
	$taxonomies = get_taxonomies();
	?>
	<div class="form-field term-language-wrap">
		<label for="monk-language"><?php _e( 'Monk language', 'monk' ); ?></label>
		<select class="postform" id="monk-language" name="monk-language">
			<option value="-1"><?php _e( 'none', 'monk' ); ?></option>
			<?php foreach ( $languages as $language ) : ?>
				<option value="<?php echo $language; ?>" class=""><?php echo $monk_languages[$language]['name']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php