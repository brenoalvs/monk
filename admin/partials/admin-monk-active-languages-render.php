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

$monk_languages = get_transient( 'monk_languages' );

$active_languages = get_option( 'monk_active_languages', false );
$default_language = get_option( 'monk_default_language', false );
?>
<fieldset>
<?php
foreach ( $monk_languages as $lang_code => $lang_names ) :
	$id = sanitize_title( $lang_code );

	if ( $active_languages ) {
		$is_checked = $default_language === $lang_code || in_array( $lang_code, $active_languages, true ) ? true : false;
	} else {
		$is_checked = false;
	}

	$is_default = $default_language === $lang_code ? true : false;
	$disabled   = $is_default ? 'option-disabled': '';
?>
<label for="<?php echo esc_attr( 'monk-' . $id ); ?>" class="monk-label <?php echo esc_attr( $disabled ); ?>">
	<input type="checkbox" <?php if ( $is_checked ) : ?> class="monk-saved-language" <?php endif; ?> name="monk_active_languages[]" id="<?php echo esc_attr( 'monk-' . $id ); ?>" value="<?php echo esc_attr( $lang_code ); ?>" <?php checked( $is_checked ); ?> />
	<?php echo esc_html( $lang_names['native_name'] ); ?>
</label>
<?php endforeach; ?>
</fieldset>
