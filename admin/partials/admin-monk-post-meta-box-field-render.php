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

	wp_nonce_field( basename( __FILE__ ), 'monk_post_meta_box_nonce' );
	$active_languages      = get_option( 'monk_active_languages' );
	$post_default_language = get_post_meta( $post->ID, '_monk_post_default_language', true );
	$post_translations     = get_post_meta( $post->ID, '_monk_post_add_translation', true ) ? get_post_meta( $post->ID, '_monk_post_add_translation', true ) : array();
	global $current_screen;
	$available_languages   = array(
		'da_DK' => __( 'Danish', 'monk' ),
		'en_US' => __( 'English', 'monk' ),
		'fr_FR' => __( 'French', 'monk' ),
		'de_DE' => __( 'German', 'monk' ),
		'it_IT' => __( 'Italian', 'monk' ),
		'ja'    => __( 'Japanese', 'monk' ),
		'pt_BR' => __( 'Portuguese (Brazil)', 'monk' ),
		'ru_RU' => __( 'Russian', 'monk' ),
		'es_ES' => __( 'Spanish', 'monk' ),
	);
	if ( $current_screen -> action == 'add' || $post_default_language == '' ) : 
	?>
	<div>
		<h4><?php _e( 'Default post language', 'monk' ); ?></h4>
		<p>
			<select name="monk_post_default_language">
			<?php
				foreach ( $available_languages as $lang_code => $lang_name ) :
					if ( in_array( $lang_code, $active_languages ) ) :
			?>
				<option value="<?php echo esc_attr( $lang_name ); ?>"<?php selected( $post_default_language, $lang_name ); ?>>
					<?php echo esc_html( $lang_name ); ?>
				</option>
			<?php endif; endforeach; ?>
			</select>
		</p>
	</div>

	<?php else : ?>
	<div class="monk-post-meta-text">
		<h2><?php echo __( 'Post in ', 'monk' ) . $post_default_language; ?></h2>
		<label for="monk-post-add-translation"><?php _e( 'Translations', 'monk' ); ?></label>
		<a class="edit-post-status hide-if-no-js"><span aria-hidden="true" class="monk-add-translation">Add+</span> <span class="screen-reader-text">Add new translation</span></a>
	</div>
	<div class="monk-post-meta-add-translation">
		<select name="monk_post_add_translation[]">
			<?php
				foreach ( $available_languages as $lang_code => $lang_name ) :
				$lang_id = sanitize_title( $lang_code );
					if ( in_array( $lang_code, $active_languages ) && $post_default_language != $lang_name ) : ?>
					<option  value="<?php echo $lang_code ?>" <?php selected( in_array( $lang_code, $post_translations) ); ?> />
						<?php echo $lang_name; ?>
					</option>
			<?php endif; endforeach; ?>
		</select>		
	</div>
<?php 
endif;
