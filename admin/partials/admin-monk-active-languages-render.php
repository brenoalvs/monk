<?php
/**
 * Provide the view for the monk_active_languages_render function
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

$monk_languages = monk_get_available_languages();

$active_languages = $this->active_languages;
$default_language = $this->default_language;
?>
<fieldset>
	<?php foreach ( $monk_languages as $lang_code => $lang_names ) : ?>
		<?php
		$id = sanitize_title( $lang_code );

		if ( $active_languages ) {
			$is_checked = $default_language === $lang_code || in_array( $lang_code, $active_languages, true ) ? true : false;
		} else {
			$is_checked = false;
		}

		$is_default = $default_language === $lang_code ? true : false;
		$disabled   = $is_default ? 'option-disabled' : '';
		?>
		<label for="<?php echo esc_attr( 'monk-' . $id ); ?>" class="monk-label <?php echo esc_attr( $disabled ); ?>">
			<input type="checkbox"
			<?php if ( $is_checked ) : ?>
			class="monk-saved-language"
			<?php endif; ?>
			name="monk_active_languages[]"
			id="<?php echo esc_attr( 'monk-' . $id ); ?>"
			value="<?php echo esc_attr( $lang_code ); ?>"
			<?php checked( $is_checked ); ?>
			/>
			<?php echo esc_html( $lang_names['english_name'] ); ?>
			<span class="monk-description">
			<?php echo esc_html( $lang_names['native_name'] ); ?>
			</span>
		</label>
	<?php endforeach; ?>
</fieldset>
