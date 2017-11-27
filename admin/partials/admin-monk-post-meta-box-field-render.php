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
			<p>
			<?php
				/* translators: This is a message to display the post being translated */
				echo esc_html( sprintf( __( 'Translating "%s".', 'monk' ), $title ) );
			?>
			</p>
		<?php endif; ?>
	</div>
	<?php else : ?>
	<?php
	$translation_counter = 0;
	$option_current_name = 'monk_post_translations_' . $monk_id;
	$post_translations   = get_option( $option_current_name, false );
	foreach ( $active_languages as $code ) {
		if ( $post_translations && array_key_exists( $code, $post_translations ) ) {
			$translation_counter = $translation_counter++;
		}
	}
	?>
	<div>
		<ul class="monk-translated-to">
			<li>
				<div class="monk-inner-section-elements">
					<span id="current-language"><?php echo esc_html( $monk_languages[ $post_default_language ]['english_name'] ); ?></span>

				<!--
					Gives the option to alter the current post language
					When the user select this feature,
					the option containig translations is updated
				-->
				<?php if ( $is_available_languages ) : ?>
					<a class="edit-post-status hide-if-no-js">
						<span aria-hidden="true" class="monk-change-language">
							<?php esc_html_e( 'Change', 'monk' ); ?>
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
				</div>
			</li>
			<?php
			if ( isset( $post_translations ) && $post_translations ) :
			?>
			<li>
				<div>
					<strong><?php esc_html_e( 'Translations', 'monk' ); ?></strong>
					<div class="monk-inner-section-elements">
					<?php
					foreach ( $post_translations as $lang_code => $translation_id ) :
						if ( strval( $translation_id ) !== $post->ID ) :
							$language_url = get_edit_post_link( $translation_id );
							$title        = ucwords( get_the_title( $translation_id ) );
					?>
						<span><?php echo esc_html( $monk_languages[ $lang_code ]['english_name'] . ': ' ); ?></span>
						<a href="<?php echo esc_url( $language_url ); ?>"><?php echo esc_html( $title ); ?></a>
					<?php endif; ?>
					<?php endforeach; ?>
					<?php endif; ?>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<div>
		<?php if ( count( $active_languages ) !== $translation_counter ) : ?>
		<strong><?php esc_html_e( 'Add a translation', 'monk' ); ?></strong>
		<span class="screen-reader-text"><?php esc_html_e( 'Add new translation', 'monk' ); ?></span>
	</div>
	<?php endif; ?>
<?php
endif;
