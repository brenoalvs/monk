<?php
/**
 * Provide the view for the monk_add_menu_translation_fields function
 *
 * @since      0.3.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $monk_languages;
?>
<!-- The select with the available languages to choose from -->
<select name="monk_language" class="new-menu-language">
	<?php foreach ( $active_languages as $locale ) : ?>
		<?php if ( ! array_key_exists( $locale, $menu_translations ) ) : ?>
			<option value="<?php echo esc_html( $locale ); ?>"><?php echo esc_html( $monk_languages[ $locale ]['name'] ); ?></option>
		<?php endif; ?>
	<?php endforeach; ?>
</select>

<!-- Necessary hidden fields -->
<input type="hidden" name="monk_id" value="<?php echo esc_attr( $monk_id ); ?>">
<?php
