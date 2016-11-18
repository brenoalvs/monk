<?php
/**
 * Provide the view for the monk_active_languages_render function
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

global $monk_languages;
$active_languages    = get_option( 'monk_active_languages' );
$default_language    = get_option( 'monk_default_language' );
?>
<fieldset>
<?php

	/**
	 * Walks through each value in the active languages array creating the checkboxes
	 * based on the values, making possible and more reliable updating that list
	 */
foreach ( $monk_languages as $lang_code => $lang_names ) :
	$id          = sanitize_title( $lang_code );
	$is_checked  = $default_language === $lang_code || in_array( $lang_code, $active_languages, true ) ? true : false;
	$is_default  = $default_language === $lang_code ? true : false;
?>
<label for="<?php echo esc_attr( 'monk-' . $id ); ?>" <?php if ( $is_default ) : ?> class="option-disabled" <?php endif; ?>>
	<input type="checkbox" <?php if ( $is_checked ) : ?> class="monk-saved-language" <?php endif; ?> name="monk_active_languages[]" id="<?php echo esc_attr( 'monk-' . $id ); ?>" value="<?php echo esc_attr( $lang_code ); ?>" <?php checked( $is_checked ); ?> />
	<?php echo esc_html( $lang_names['name'] ); ?>
</label>
<br />
<?php endforeach; ?>
</fieldset>
