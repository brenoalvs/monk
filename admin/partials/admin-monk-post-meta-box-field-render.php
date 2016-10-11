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
			<select name="monk_post_default_language">
			<?php
				foreach ( $available_languages as $lang_code => $lang_name ) :
					if ( in_array( $lang_code, $active_languages ) ) :
			?>
				<option value="<?php echo esc_attr( $lang_name ); ?>" <?php selected( $post_default_language, $lang_name ); ?>>
					<?php echo esc_html( $lang_name ); ?>
				</option>
			<?php endif; endforeach; ?>
			</select>
		</p>
	</div>
	<?php else : ?>
	<div class="monk-post-meta-text">
		<label for="monk-post-add-translation"><?php _e( 'Translations', 'monk' ); ?></label>
		<a class="edit-post-status hide-if-no-js"><span aria-hidden="true" class="monk-add-translation">Add<strong>+</strong></span><span class="screen-reader-text">Add new translation</span></a>
	</div>
	<div class="monk-post-meta-add-translation">
		<select name="monk_post_add_translation[]">
			<?php
				foreach ( $available_languages as $lang_code => $lang_name ) :
				$lang_id = sanitize_title( $lang_code );
					if ( in_array( $lang_code, $active_languages ) && $post_default_language != $lang_name && ! in_array( $lang_code, $post_translations ) ) : ?>
					<option  value="<?php echo $lang_code ?>"/>
						<?php echo $lang_name; ?>
					</option>
			<?php endif; endforeach; ?>
		</select>
		<input type="submit" class="monk-submit-translation button" value="ok" />
	</div>
	<ul class="monk-translated-to">
		<li><?php echo esc_html( $post_default_language ); ?></li>
		<?php
			foreach ( $available_languages as $lang_code => $lang_name ) :
				if ( in_array( $lang_code, $post_translations ) ) : ?>
					<li><a href="<?php echo get_edit_post_link( $post->ID, '' ); ?> "><?php echo $lang_name; ?></a></li>
		<?php	endif; endforeach; ?>
	</ul>
<?php
endif;
