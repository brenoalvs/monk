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
<?php
foreach ( $active_languages as $lang_code ) :
	$id = sanitize_title( $lang_code );

	$is_default = $default_language === $lang_code ? true : false;
	$disabled   = $is_default ? 'option-disabled' : '';
?>
<label for="<?php echo esc_attr( 'monk-' . $id ); ?>" class="monk-label <?php echo esc_attr( $disabled ); ?>">
	<input type="checkbox"
	class="monk-saved-language"
	name="monk_active_languages[]"
	id="<?php echo esc_attr( 'monk-' . $id ); ?>"
	value="<?php echo esc_attr( $lang_code ); ?>"
	<?php checked( true ); ?>
	/>
	<?php echo esc_html( $monk_languages[ $lang_code ]['english_name'] ); ?>
	<span class="monk-description">
	<?php echo esc_html( $monk_languages[ $lang_code ]['native_name'] ); ?>
	</span>
</label>
<?php endforeach; ?>
<?php
foreach ( $monk_languages as $lang_code => $lang_names ) :
	$id = sanitize_title( $lang_code );

	if ( ! in_array( $lang_code, $active_languages, true ) ) :
?>
<label for="<?php echo esc_attr( 'monk-' . $id ); ?>" class="monk-label">
	<input type="checkbox"
	name="monk_active_languages[]"
	id="<?php echo esc_attr( 'monk-' . $id ); ?>"
	value="<?php echo esc_attr( $lang_code ); ?>"
	/>
	<?php echo esc_html( $lang_names['english_name'] ); ?>
	<span class="monk-description">
	<?php echo esc_html( $lang_names['native_name'] ); ?>
	</span>
</label>
<?php endif; ?>
<?php endforeach; ?>
</fieldset>
<?php
