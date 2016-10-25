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
	if ( isset( $_GET['lang'] ) && isset( $_GET['monk_id'] ) ) {
		$lang    = $_GET['lang'];
		$monk_id = $_GET['monk_id'];
	} else {
		$lang    = $site_default_language;
		$monk_id = $monk_meta_id;
		if ( empty( $monk_id ) ) {
			$monk_id = $post->ID;
		}
	}
?>
<input type="hidden" name="monk_id" value="<?php echo $monk_id; ?>" />
	<?php if ( $current_screen->action == 'add' || $post_default_language == '' ) : ?>
	<div>
		<h4><?php _e( 'Default post language', 'monk' ); ?></h4>
		<p>
			<select name="monk_post_language">
			<?php
				foreach ( $active_languages as $lang_code ) :
					if ( array_key_exists( $lang_code, $available_languages ) ) :
						$lang_name = $available_languages[$lang_code];
			?>
				<option value="<?php echo esc_attr( $lang_code ); ?>" <?php selected( $lang, $lang_code ); ?>>
					<?php echo esc_html( $lang_name ); ?>
				</option>
			<?php endif; endforeach; ?>
			</select>
		</p>
	</div>
	<?php else : ?>
	<input type="hidden" name="monk_post_language" value="<?php echo esc_attr( $post_default_language ); ?>">
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
							'monk_id' => $monk_id
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
			$option_current_name = 'monk_post_translations_' . $monk_id;
			//if ( get_option( $option_current_name ) !== false ) :
				$post_translations = get_option( $option_current_name );
				foreach ( $post_translations as $monk_post_id => $lang_code ) :
					if ( $monk_post_id != $post->ID ) :
						$encoded_url = esc_url( get_edit_post_link( $monk_post_id ) ); ?>
						<li>
							<a href="<?php echo $encoded_url; ?>"><?php echo $available_languages[$lang_code]; ?></a>
						</li>
		<?php 	endif;
				endforeach;
				if ( count( $post_translations ) == 1 ) : ?>
					<span class="monk-add-translation">
					<?php _e( 'Not translated, add one', 'monk' ); ?>
					</span>
		<?php   endif; ?>
	</ul>
<?php
endif;
