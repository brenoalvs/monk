<?php
/**
 * Provide the view for the monk_post_meta_box_field_render function
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

?>
<input type="hidden" name="monk_id" value="<?php echo esc_attr( $monk_id ); ?>" />
<input type="hidden" class="current-post-id" value="<?php echo esc_attr( $post->ID ); ?>">
	<?php if ( 'add' === $current_screen->action || '' === $post_default_language ) : ?>
	<div>
		<strong><?php esc_html_e( 'Post language', 'monk' ); ?></strong>
			<p>
				<select name="monk_post_language">
				<?php
				$option_current_name = 'monk_post_translations_' . $monk_id;
				$post_translations   = get_option( $option_current_name, false );
				if ( $post_translations ) {
					foreach ( $active_languages as $lang_code ) {
						if ( ! array_key_exists( $lang_code, $post_translations ) ) {
							$available_languages[] = $lang_code;
						}
					}
				} else {
					$available_languages = $active_languages;
				}

				foreach ( $available_languages as $lang_code ) :
						$lang_name = $monk_languages[ $lang_code ]['english_name'];
				?>
					<option value="<?php echo esc_attr( $lang_code ); ?>" <?php selected( $lang, $lang_code ); ?>>
						<?php echo esc_html( $lang_name ); ?>
					</option>
				<?php
					endforeach;
				?>
				</select>
			</p>
			<?php if ( $post_translations && $post->ID !== $monk_id ) : ?>
				<?php if ( get_the_title( $monk_id ) ) : ?> 
					<?php $title = get_the_title( $monk_id ); ?>
				<?php else : ?>
					<?php $title = get_the_title( reset( $monk_translations ) ); ?>
				<?php endif; ?>
				<?php /* translators: This is a message to display the post being translated */ ?>
				<p><?php echo esc_html( sprintf( __( 'Translating "%s".', 'monk' ), $title ) ); ?></p>
			<?php endif; ?>
		</div>
	<?php else :
	$translation_counter = 0;
	$option_current_name = 'monk_post_translations_' . $monk_id;
	$post_translations   = get_option( $option_current_name );
	foreach ( $active_languages as $code ) {
		if ( $post_translations && array_key_exists( $code , $post_translations ) ) {
			$translation_counter = $translation_counter + 1;
		}
	}
	?>
	<div class="monk-post-meta-text">
		<label for="monk-post-add-translation"><?php esc_html_e( 'Translations', 'monk' ); ?></label>
		<?php if ( count( $active_languages ) !== $translation_counter ) : ?>
		<a class="edit-post-status hide-if-no-js">
			<span aria-hidden="true" class="monk-add-translation">
				<?php esc_html_e( 'Add+', 'monk' ); ?>
			</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Add new translation', 'monk' ); ?></span>
		</a>
		<?php endif; ?>
	</div>
	<div class="monk-post-meta-add-translation">
		<?php if ( count( $active_languages ) !== $translation_counter ) : ?>
			<select name="monk_post_translation_id" class='monk-lang'>
				<?php
				$post_type = get_post_type( $post->ID );
				if ( 'post' !== $post_type && 'attachment' !== $post_type ) :
					foreach ( $active_languages as $lang_code ) :
						$language_url = add_query_arg( array(
							'post_type' => $post_type,
							'lang'      => $lang_code,
							'monk_id'   => $monk_id,
						), $monk_translation_url );
						$lang_id = sanitize_title( $lang_code );
						if ( array_key_exists( $lang_code, $monk_languages ) && ! array_key_exists( $lang_code, $post_translations ) ) :
							$lang_name = $monk_languages[ $lang_code ]['english_name'];
					?>
							<option value="<?php echo esc_url( $language_url ); ?>"/>
								<?php echo esc_html( $lang_name ); ?>
							</option>
					<?php
						endif;
					endforeach;
				elseif ( 'attachment' === $post_type ) :
					$monk_translation_url = admin_url( 'media-new.php' );
					foreach ( $active_languages as $lang_code ) :
						$lang_id = sanitize_title( $lang_code );
						if ( array_key_exists( $lang_code, $monk_languages ) && ! array_key_exists( $lang_code, $post_translations ) ) :
							$lang_name = $monk_languages[ $lang_code ]['english_name'];
					?>
							<option value="<?php echo esc_attr( $lang_code ); ?>"/>
								<?php echo esc_html( $lang_name ); ?>
							</option>
					<?php
						endif;
					endforeach;
				else :
					foreach ( $active_languages as $lang_code ) :
						$language_url = add_query_arg( array(
							'lang'      => $lang_code,
							'monk_id'   => $monk_id,
						), $monk_translation_url );
						$lang_id = sanitize_title( $lang_code );
						if ( array_key_exists( $lang_code, $monk_languages ) && ! array_key_exists( $lang_code, $post_translations ) ) :
							$lang_name = $monk_languages[ $lang_code ]['english_name'];
					?>
							<option value="<?php echo esc_url( $language_url ); ?>"/>
								<?php echo esc_html( $lang_name ); ?>
							</option>
					<?php
						endif;
					endforeach;
				endif;
				?>
			</select>
			<?php
			$attach = ( 'attachment' === $post_type ) ? 'monk-attach' : '';
			if ( $attach ) :
				?>
				<input type="hidden" name="monk_id" class="monk-id" value="<?php echo esc_attr( $monk_id ); ?>">
				<input type="hidden" class="current-post-id" value="<?php echo esc_attr( $post->ID ); ?>">
				<button class="button <?php echo esc_attr( $attach ); ?>"><?php esc_html_e( 'Ok', 'monk' ); ?></button>
				<?php
			else :
			?>
				<button class="monk-submit-translation button" id="<?php echo esc_attr( $attach ); ?>"><?php esc_html_e( 'Ok', 'monk' ); ?></button>
			<?php endif; ?>
			<a class="monk-cancel-submit-translation hide-if-no-js button-cancel"><?php esc_html_e( 'Cancel', 'monk' ); ?></a>
		<?php endif; ?>
	</div>
	<ul class="monk-translated-to">
		<li>
			<span id="current-language"><?php echo esc_html( $monk_languages[ $post_default_language ]['english_name'] ); ?></span>

			<!-- 
				Gives the option to alter the current post language
				When the user select this feature,
				the option containig translations is updated
			-->
			<?php if ( $is_available_languages ) : ?>
				<a class="edit-post-status hide-if-no-js">
					<span aria-hidden="true" class="monk-change-language">
						<?php esc_html_e( 'Edit', 'monk' ); ?>
					</span>
					<span class="screen-reader-text"><?php esc_html_e( 'Change current language', 'monk' ); ?></span>
				</a>
				<div class="monk-change-current-language">
					<?php if ( count( $active_languages ) !== $translation_counter ) : ?>
						<select name="monk_post_language" id="new-post-language">
							<option value="<?php echo esc_attr( $post_default_language ); ?>" selected>
								<?php echo esc_html( $monk_languages[ $post_default_language ]['english_name'] ); ?>
							</option>
							<?php
							foreach ( $active_languages as $lang_code ) :
								if ( array_key_exists( $lang_code, $monk_languages ) && ! array_key_exists( $lang_code, $post_translations ) ) :
									$lang_name = $monk_languages[ $lang_code ]['english_name'];
							?>
									<option  value="<?php echo esc_attr( $lang_code ); ?>"/>
										<?php echo esc_html( $lang_name ); ?>
									</option>
							<?php
								endif;
							endforeach;
							?>
						</select>
						<button class="monk-change-post-language button"><?php esc_html_e( 'Ok', 'monk' ); ?></button>
						<a class="monk-cancel-language-change hide-if-no-js button-cancel"><?php esc_html_e( 'Cancel', 'monk' ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</li>
		<?php
		if ( isset( $post_translations ) && $post_translations ) :
			foreach ( $post_translations as $lang_code => $monk_id ) :
				if ( strval( $monk_id ) !== $post->ID ) :
					$language_url = get_edit_post_link( $monk_id ); ?>
					<li>
						<a href="<?php echo esc_url( $language_url ); ?>"><?php echo esc_html( $monk_languages[ $lang_code ]['english_name'] ); ?></a>
					</li>
		<?php
				endif;
			endforeach;
		endif;
		if ( isset( $post_translations ) && count( $post_translations ) === 1 ) : ?>
			<span class="monk-add-translation">
				<?php esc_html_e( 'Not translated, add one', 'monk' ); ?>
			</span>
		<?php endif; ?>
	</ul>
<?php
endif;
