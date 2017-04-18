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
<span class="hide-if-no-js add-menu-translation">
	<?php if ( $menu_language ) : ?>
		<?php if ( count( $active_languages ) !== $translation_counter ) : ?>
			<input type="submit" class="button" id="add-menu-translation" value="Add translation +">
		<?php else : ?>
			<?php esc_html_e( 'No more languages available.', 'monk' ) ?>
		<?php endif; ?>
	<?php else : ?>
		<?php esc_html_e( 'This menu has no language, save it to set a language.', 'monk' ) ?>
	<?php endif; ?>
</span>
<!-- The select element with the available languages to choose from -->
<fieldset class="hide-if-no-js menu-settings-group menu-language">
	<legend class="menu-settings-group-name howto">Language</legend>
	<div class="menu-settings-input">
		<?php if ( empty( $menu_language ) ) : ?>
			<select name="monk_language" id="menu-language">
			<?php foreach ( $active_languages as $locale ) : ?>
				<?php if ( ! array_key_exists( $locale, $menu_translations ) ) : ?>
				<option value="<?php echo esc_html( $locale ); ?>"><?php echo esc_html( $monk_languages[ $locale ]['name'] ); ?></option>
			<?php
				endif;
				endforeach;
			?>
			</select>
		<?php else : ?>
			<span class="menu-flag flag-icon flag-icon-<?php echo esc_attr( $monk_languages[ $menu_language ]['slug'] ); ?>"></span>
			<span><?php echo esc_html( $monk_languages[ $menu_language ]['name'] ) ?></span>
			<input type="hidden" name="monk_language" value="<?php echo esc_attr( $menu_language ); ?>">
		<?php endif; ?>
	</div>
</fieldset>
<!-- A list with the selected menu translations -->
<div class="hide-if-no-js menu-translations">
	<h3>Menu Translations</h3>
	<?php if ( 1 === count( $menu_translations ) ) : ?>
	<p>
		<?php esc_html_e( 'This menu does not have translations. ', 'monk' ); ?>
		<a href="<?php echo esc_attr( $new_translation_url ); ?>">
			<?php esc_html_e( 'You can add one here. ', 'monk' ); ?>
		</a>
	</p>
	<?php elseif ( 0 === count( $menu_translations ) ) : ?>
		<?php esc_html_e( 'This menu has no language, save it to set a language.', 'monk' ) ?>
	<?php else : ?>
	<ul class="current-menu-translations">
		<?php foreach ( $menu_translations as $locale => $id ) : ?>
			<?php if ( $locale !== $menu_language ) : ?>
				<li>
					<a href="<?php echo esc_attr( admin_url( 'nav-menus.php?action=edit&menu=' . $id ) ); ?>">
						<?php echo esc_html( $monk_languages[ $locale ]['name'] ); ?>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</div>
<!-- Necessary hidden fields -->
<input class="hide-if-no-js" type="hidden" id="new-menu-link" value="<?php echo esc_attr( $new_translation_url ); ?>">
<?php
