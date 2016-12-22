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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( empty( $monk_id ) ) {
	if ( isset( $_GET['lang'] ) ) {
		$lang    = sanitize_text_field( wp_unslash( $_GET['lang'] ) );
	} else {
		$lang    = $site_default_language;
	}

	if ( isset( $_GET['monk_id'] ) ) {
		$monk_id = sanitize_text_field( wp_unslash( $_GET['monk_id'] ) );
	} else {
		$monk_id = $post->ID;
	}
}
?>
<input type="hidden" name="monk-id" value="<?php echo esc_attr( $monk_id ); ?>" />
	<?php if ( 'add' === $current_screen->action || '' === $post_default_language ) : ?>
	<div>
		<h4><?php esc_html_e( 'Default post language', 'monk' ); ?></h4>
		<p>
			<select name="monk_post_language">
			<?php
			foreach ( $active_languages as $lang_code ) :
				if ( array_key_exists( $lang_code, $monk_languages ) ) :
					$lang_name = $monk_languages[ $lang_code ]['name'];
			?>
				<option value="<?php echo esc_attr( $lang_code ); ?>" <?php selected( $lang, $lang_code ); ?>>
					<?php echo esc_html( $lang_name ); ?>
				</option>
			<?php
				endif;
				endforeach;
			?>
			</select>
		</p>
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
				Add<strong>+</strong>
			</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Add new translation', 'monk' ); ?></span>
		</a>
		<?php endif; ?>
	</div>
	<div class="monk-post-meta-add-translation">
		<?php if ( count( $active_languages ) !== $translation_counter ) : ?>
			<select name="monk_post_translation_id">
				<?php
				$post_type = get_post_type( $post->ID );
				if ( 'post' !== $post_type ) :
					foreach ( $active_languages as $lang_code ) :
						$language_url = add_query_arg( array(
							'post_type' => $post_type,
							'lang'      => $lang_code,
							'monk_id'   => $monk_id,
						), $monk_translation_url );
						$lang_id = sanitize_title( $lang_code );
						if ( array_key_exists( $lang_code, $monk_languages ) && ! array_key_exists( $lang_code, $post_translations ) ) :
							$lang_name = $monk_languages[ $lang_code ]['name'];
					?>
							<option value="<?php echo esc_url( $language_url ); ?>"/>
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
							$lang_name = $monk_languages[ $lang_code ]['name'];
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
			<button class="monk-submit-translation button"><?php esc_html_e( 'Ok', 'monk' ); ?></button>
			<a class="monk-cancel-submit-translation hide-if-no-js button-cancel"><?php esc_html_e( 'Cancel', 'monk' ); ?></a>
		<?php endif; ?>
	</div>
	<ul class="monk-translated-to">
		<li>
			<span id="current-language"><?php echo esc_html( $monk_languages[ $post_default_language ]['name'] ); ?></span>

			<!-- 
				Gives the option to alter the current post language
				When the user select this feature,
				the option containig translations is updated
			-->
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
							<?php echo esc_html( $monk_languages[ $post_default_language ]['name'] ); ?>
						</option>
						<?php
						foreach ( $active_languages as $lang_code ) :
							if ( array_key_exists( $lang_code, $monk_languages ) && ! array_key_exists( $lang_code, $post_translations ) ) :
								$lang_name = $monk_languages[ $lang_code ]['name'];
						?>
								<option  value="<?php echo esc_attr( $lang_code ); ?>"/>
									<?php echo esc_html( $lang_name ); ?>
								</option>
						<?php
							endif;
						endforeach;
						?>
					</select>
					<button class="button"><?php esc_html_e( 'Ok', 'monk' ); ?></button>
					<a class="monk-cancel-language-change hide-if-no-js button-cancel"><?php esc_html_e( 'Cancel', 'monk' ); ?></a>
				<?php endif; ?>
			</div>
		</li>
		<?php
		if ( isset( $post_translations ) && $post_translations ) :
			foreach ( $post_translations as $lang_code => $monk_id ) :
				if ( strval( $monk_id ) !== $post->ID ) :
					$language_url = get_edit_post_link( $monk_id ); ?>
					<li>
						<a href="<?php echo esc_url( $language_url ); ?>"><?php echo esc_html( $monk_languages[ $lang_code ]['name'] ); ?></a>
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
