<?php
/**
 * Provide the view for the monk_add_menu_translation_fields function
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $monk_languages;
?>
<!-- The button to create a new translation -->
<span class="add-menu-translation">
	<input type="submit" class="button" id="add-menu-translation" value="Add translation +">
</span>
<!-- The select with the available languages to choose from -->
<fieldset class="menu-settings-group menu-language">
	<legend class="menu-settings-group-name howto">Language</legend>
	<div class="menu-settings-input">
		<select name="monk_language" id="menu-language">
		<?php foreach ( $active_languages as $locale ) : ?>
			<?php if ( ! array_key_exists( $locale, $menu_translations ) ) : ?>
			<option value="<?php echo esc_html( $locale ); ?>"><?php echo esc_html( $monk_languages[ $locale ]['name'] ); ?></option>
		<?php
			endif;
			endforeach;
		?>
		</select>
	</div>
</fieldset>
<!-- The flag showing witch language we are on -->
<span class="menu-flag flag-icon flag-icon-<?php echo esc_attr( $monk_languages[ $menu_language ]['slug'] ); ?>"></span>
<!-- A list with the selected menu translations -->
<div class="menu-settings">
	<h3>Menu Translations</h3>
	<ul class="current-menu-translations">
		<?php foreach ( $menu_translations as $locale => $id ) : ?>
			<?php if ( $locale !== $menu_language ) : ?>
				<li>
					<a href="#"><?php echo esc_html( $monk_languages[ $locale ]['name'] ); ?></a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>
<!-- Necessary hidden fields -->
<input type="hidden" id="new-menu-link" value="<?php echo esc_attr( $new_translation_url ); ?>">
<?php
