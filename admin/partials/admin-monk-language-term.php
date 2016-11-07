<?php		
	if ( ! isset( $_GET['monk_term_id'] ) ) {
		?>
		<div class="form-field term-language-wrap">
			<label for="monk-language"><?php _e( 'Monk language', 'monk' ); ?></label>
			<select class="postform" id="monk-language" name="monk-language">
				<?php foreach ( $languages as $language ) : ?>
					<option value="<?php echo esc_attr( $language ); ?>" <?php selected( $default_language, $language ); ?>><?php echo esc_html( $monk_languages[$language]['name'] ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
	} else {
		$translation_lang = $_GET['translation_lang'];
		$monk_term_id     = $_GET['monk_term_id'];
		?>
		<div class="form-field term-language-wrap">
			<label for="monk-language"><?php _e( 'Monk language', 'monk' ); ?></label>
			<input type="hidden" name="monk_term_id" value="<?php echo esc_attr( $monk_term_id ); ?>">
			<select class="postform" id="monk-language" name="monk-language">
				<?php foreach ( $languages as $language ) : ?>
					<option value="<?php echo esc_attr( $language ); ?>" <?php selected( $translation_lang, $language ); ?>><?php echo esc_html( $monk_languages[$language]['name'] ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
	}
	