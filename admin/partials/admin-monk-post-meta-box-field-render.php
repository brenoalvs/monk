<?php

/**
 * Provide the view for the monk_post_meta_box_field_render function
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

	if ( $current_screen->action == 'add' || $post_default_language == '' ) : 
	?>
	<div>
		<h4><?php _e( 'Default post language', 'monk' ); ?></h4>
		<p>
			<select name="monk_post_language">
			<?php
				foreach ( $active_languages as $lang_code ) :
					if ( array_key_exists( $lang_code, $available_languages ) ) :
						$lang_name = $available_languages[$lang_code];
			?>
				<option value="<?php echo esc_attr( $lang_code ); ?>" <?php selected( $site_default_language, $lang_name ); ?>>
					<?php echo esc_html( $lang_name ); ?>
				</option>
			<?php endif; endforeach; ?>
			</select>
		</p>
		<div id="log">
		<?php
			if ( isset( $_GET['lang'] ) && isset( $_GET['monk_id'] ) ) {
				$monk_lang = $_GET['lang'];
				$monk_id = $_GET['monk_id'];
				print_r($monk_vars);
			}
		?>
			<input type="hidden" name="monk_request_post_lang" value="<?php echo $monk_vars['lang']; ?>">
			<input type="hidden" name="monk_request_post_id" value="<?php echo $monk_vars['monk_id']; ?>">
		</div>
	</div>
	<?php else : ?>
	<div class="monk-post-meta-text">
		<label for="monk-post-add-translation"><?php _e( 'Translations', 'monk' ); ?></label>
		<a class="edit-post-status hide-if-no-js">
			<span aria-hidden="true" class="monk-add-translation">
				Add<strong>+</strong>
			</span>
			<span class="screen-reader-text">Add new translation</span>
		</a>
	</div>
	<div class="monk-post-meta-add-translation">
		<select name="monk_post_translation_id">
			<?php
				foreach ( $active_languages as $lang_code ) :
					$encoded_url = esc_url(
						add_query_arg( array(
							'lang'    => $lang_code,
							'monk_id' => '123'
							), $monk_translation_url
						)
					);
					$lang_id = sanitize_title( $lang_code );
					if ( array_key_exists( $lang_code, $available_languages ) && ! in_array( $lang_code, $post_translation ) ) :
						$lang_name = $available_languages[$lang_code];
			?>
					<option  value="<?php echo $encoded_url; ?>"/>
						<?php echo $lang_name; ?>
					</option>
			<?php endif; endforeach; ?>
		</select>
		<button class="monk-submit-translation button">OK</button>
		<a class="monk-cancel-submit-translation hide-if-no-js button-cancel">Cancel</a>
	</div>
	<ul class="monk-translated-to">
		<li>
			<?php echo esc_html( $available_languages[$post_default_language] ); ?>
		</li>
		<?php
			foreach ( $available_languages as $lang_code => $lang_name ) :
				$encoded_url = esc_url(
					add_query_arg( array(
						'lang'    => $lang_code,
						'monk_id' => '123'
						), $monk_translation_url
					)
				);
				if ( in_array( $lang_code, $post_translation ) ) : ?>
					<li>
						<a href="<?php echo $encoded_url; ?>"><?php echo $lang_name; ?></a>
					</li>
		<?php	endif; endforeach; ?>
	</ul>
<?php
endif;
